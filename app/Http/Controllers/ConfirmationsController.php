<?php

namespace App\Http\Controllers;

use App\Classes\Daemon;
use App\Confirmation;
use App\Events\ConfirmationGenerated;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ConfirmationsController extends Controller
{
    use FormBuilderTrait;

    public function generate(Order $order)
    {
        // Retrieve confirmation count for given order
        $confirmationCount = $order->confirmation()->count();

        // Check if confirmation exists first
        if ($confirmationCount == 1) {
            flash()->error('We already have a confirmation for this order is our database!');

            return redirect()->route('orders.show', $order);
        }

        // Check if order is validated and ready to generate a confirmation

        if (!$order->canGenerateConfirmation(true)) {
            return redirect()->route('orders.view', $order);
        }

        /*if ($order->isSteamOffer()) {
            // Check if steam order is accepted
            $steamOrder = $order->orderable()->first();
            if (!$steamOrder || !$steamOrder->accepted()) {
                flash()->error('You must accept the trade offer before creating a confirmation!');

                return redirect()->route('home');
            }
        } else {
            $tokenOrder = $order->orderable()->first();

            if (!$tokenOrder || !$tokenOrder->token()->exists()) {
                flash()->error('Your order must have a valid token associated with to generate a confirmation!');

                return redirect()->route('home');
            }
        }*/

        // Get last confirmation generated for the User
        $latestConfirmation = Auth::user()->confirmations()->notExpired()->orderBy('end_period', 'asc')->first();

        // The base period for the Confirmation should be now or the last valid confirmation
        if ($latestConfirmation) {
            $basePeriod = $latestConfirmation->end_period;
        } else {
            $basePeriod = Carbon::now();
        }

        // Start creating Confirmation entry
        $confirmation = Confirmation::make();

        $confirmation->public_id = substr(md5(microtime()), 0, \Setting::get('public-id-size'));
        $confirmation->baseOrder()->associate($order);
        $confirmation->user()->associate(Auth::user());
        $confirmation->start_period = $basePeriod;
        $confirmation->end_period = $basePeriod->addDays($order->duration);

        $confirmed = $confirmation->save();

        // Check if we confirmation was set to database and trigger event
        if ($confirmed) {
            event(new ConfirmationGenerated($confirmation));
            flash()->success('Confirmation created with success!');
        } else {
            flash()->error('Error saving confirmation to database!');
        }

        // Redirect to updated order
        return redirect()->route('orders.show', $order);
    }

    public function view(Request $request)
    {
        // Cache user
        $user = Auth::user();

        // If Admin show every Confirmation in database, if normal user, only the ones related to them
        if ($user->isAdmin()) {
            $confirmations = Confirmation::query();
            if ($request->has('trashed')) {
                $confirmations->withTrashed();
            }
        } else {
            $confirmations = Auth::user()->confirmations()->withTrashed();
        }

        // Eager load relationships
        $confirmations->with('user', 'baseOrder');

        // Render table
        return view('confirmations.index', [
            'confirmations' => $confirmations->get(),
            'isAdmin'       => $user->isAdmin(),
            'highlight'     => $request->get('highlight'),
        ]);
    }

    public function edit(Confirmation $confirmation)
    {
        $form = $this->form('App\Forms\ConfirmationForm', [
            'method' => 'PATCH',
            'route'  => ['confirmations.update', $confirmation],
            'model'  => $confirmation,
        ]);

        return view('confirmations.form', [
            'form' => $form,
        ]);
    }

    public function update(Confirmation $confirmation, Request $request)
    {
        if ($request->has('start_period')) {
            $confirmation->start_period = $request->input('start_period');
        }
        if ($request->has('end_period')) {
            $confirmation->end_period = $request->input('end_period');
        }

        $saved = $confirmation->save();

        if ($saved) {
            flash()->success("Confirmation {$confirmation->public_id} was updated!");
        } else {
            flash()->error("Could not update confirmation {$confirmation->public_id}!");
        }

        return redirect()->route('confirmations.index');
    }

    public function syncServer()
    {
        // Call daemon to re-sync server
        $result = Confirmation::syncServer();

        // We reached the Daemon, consider success and send user to Home
        if ($result === true) {
            flash()->success('Server synced!');

            return redirect()->route('home');
        } else {
            return $result;
        }
    }

    public function delete(Confirmation $confirmation)
    {
        $deleted = $confirmation->delete();

        if ($deleted) {
            flash()->success("Confirmation {$confirmation->public_id} deleted successfully!");
        } else {
            flash()->error("Error while trying to delete confirmation {$confirmation->public_id} from database!");
        }

        return redirect()->back();
    }

    public function restore(Confirmation $confirmation)
    {
        $restored = $confirmation->restore();

        if ($restored) {
            flash()->success("Confirmation {$confirmation->public_id} restored successfully!");
        } else {
            flash()->error("Error while trying to restore confirmation {$confirmation->public_id} from database!");
        }

        return redirect()->back();
    }

    public function generateAdminsSimple()
    {
        // Caches Carbon::now();
        $now = Carbon::now();

        // Get valid confirmations
        $confirmations = Confirmation::valid()->with('baseOrder.user')->get();

        // Array of SteamID2 to Confirmation
        $steamid = [];

        // Parses each valid confirmation and adds to array
        foreach ($confirmations as $confirmation) {
            $steam2 = Daemon::getSteam2ID($confirmation->baseOrder->user->steamid);

            // If Steam2 could not be generated
            if ($steam2 === false) {
                return redirect()->route('home');
            }

            $steamid[] = [
                'id'           => $steam2,
                'confirmation' => $confirmation,
            ];
        }

        // Render admin_simple.ini
        return view('admins_simple', [
            'list' => $steamid,
            'html' => true,
        ]);
    }

    public function viewAdminsSimple()
    {
        // Caches Carbon::now();
        $now = Carbon::now();

        // Get valid confirmations
        $confirmations = Confirmation::valid()->with('baseOrder.user')->get();

        // Array of SteamID2 to Confirmation
        $steamid = [];

        // Parses each valid confirmation and adds to array
        foreach ($confirmations as $confirmation) {
            $steam2 = Daemon::getSteam2ID($confirmation->baseOrder->user->steamid);

            // If Steam2 could not be generated
            if ($steam2 === false) {
                return redirect()->route('home');
            }

            // Create object with only the needed info
            $steamid[] = [
                'id'           => $steam2,
                'confirmation' => $confirmation,
            ];
        }

        // Render admin_simple.ini
        return view('admins_simple_preview', [
            'list' => $steamid,
            'html' => true,
        ]);
    }
}
