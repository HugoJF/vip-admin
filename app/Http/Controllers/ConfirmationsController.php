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
        $order = Order::where([
            'public_id' => $public_id,
            'user_id' => Auth::id()
        ])->get()->first();

        if (!$order) {
            return redirect()->route('home');
        }

        //check if confirmation exists first
        // check if order is locked
        // check if steam order is accepted

        $confirmation = Confirmation::make();

        $confirmation->public_id = $rand = substr(md5(microtime()), rand(0, 26), config('app.public_id_size'));;
        $confirmation->baseOrder()->associate($order);
        $confirmation->start_period = Carbon::now();
        $confirmation->end_period = Carbon::now()->addDays($order->duration);

        $confirmed = $confirmation->save();

        if ($confirmed) {
            event(new ConfirmationGenerated($confirmation));
        } else {
            flash()->error('Error saving confirmation to database!');
        }

        return redirect()->route('view-steam-offer', $public_id);
    }

    public function viewConfirmation($public_id)
    {
        $confirmation = Confirmation::with('baseOrder')->where([
            'public_id' => $public_id
        ])->get()->first();

        if (!$confirmation) return redirect()->route('home');

        return view('confirmation', [
            'confirmation' => $confirmation,
            'order' => $confirmation->baseOrder,
        ]);
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
