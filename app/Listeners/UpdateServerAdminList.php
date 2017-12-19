<?php

namespace App\Listeners;

use App\Confirmation;
use App\Events\ConfirmationGenerated;
use App\Events\Event;
use App\Http\Controllers\DaemonController;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class UpdateServerAdminList
{
    /**
     * Create the event listener.
     *
     * @return void|mixed
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ConfirmationGenerated $confirmationGenerated
     * @return void
     */
    public function handle(ConfirmationGenerated $confirmationGenerated)
    {
        DaemonController::consoleLog('Generating_new_admins_simple');

        $now = Carbon::now();

        $confirmations = Confirmation::where([
            ['start_period', '<', $now],
            ['end_period', '>', $now]
        ])->with('order.user')->get();

        $steamid = [];

        foreach ($confirmations as $confirmation) {
            $steam2 = DaemonController::getSteam2ID($confirmation->order->user->steamid);
            $steamid[] = [
                'id' => $steam2,
                'confirmation' => $confirmation,
            ];
        }

        $view = View::make('admins_simple_ini', [
            'list' => $steamid
        ]);

        Storage::put('admins_simple.ini', $view);

        DaemonController::updateSourceMod();
    }
}