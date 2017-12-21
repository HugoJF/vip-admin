<?php

namespace App\Http\Controllers;

use App\Confirmation;
use App\Events\ConfirmationGenerated;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConfirmationsController extends Controller
{
    public function createConfirmation($public_id)
    {
        // Retrieve order from which we are creating the Confirmation
        $order = Order::where([
            'public_id' => $public_id,
            'user_id' => Auth::id()
        ])->get()->first();

        // Check if Order with given public ID exists
        if (!$order) {
            flash()->error('Could not find order with ID #' . $public_id);
            return redirect()->route('home');
        }

        // Retrieve confirmation count for given order
        $confirmationCount = $order->confirmation()->count();

        // Check if confirmation exists first
        if ($confirmationCount != 0) {
            flash()->error('We already have a confirmation for this order is our database, please contact support!');
            return redirect()->route('home');
        }

        // Check if steam order is accepted
        $steamOrder = $order->orderable()->first();
        if($steamOrder->accepted()) {
            flash()->error('You must accept the trade offer before creating a confirmation!');
            return redirect()->route('home');
        }

        // Start creating Confirmation entry
        $confirmation = Confirmation::make();

        $confirmation->public_id = substr(md5(microtime()), rand(0, 26), config('app.public_id_size'));;
        $confirmation->baseOrder()->associate($order);
        $confirmation->start_period = Carbon::now();
        $confirmation->end_period = Carbon::now()->addDays($order->duration);

        $confirmed = $confirmation->save();

        // Check if we confirmation was set to database and trigger event
        if ($confirmed) {
            event(new ConfirmationGenerated($confirmation));
        } else {
            flash()->error('Error saving confirmation to database!');
        }

        // Redirect to updated order
        return redirect()->route('view-steam-order', $public_id);
    }

    public function generateAdminsSimple()
    {
        $now = Carbon::now();

        $confirmations = Confirmation::where([
            ['start_period', '<', $now],
            ['end_period', '>', $now]
        ])->with('order.user')->get();

        $steamid = [];

        foreach($confirmations as $confirmation) {
            $steam2 = DaemonController::getSteam2ID($confirmation->order->user->steamid);
            $steamid[] = [
                'id' => $steam2,
                'confirmation' => $confirmation,
            ];
        }

        return view('admins_simple', [
            'list' => $steamid
        ]);
    }
}
