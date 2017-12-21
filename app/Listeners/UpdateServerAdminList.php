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
    public function handle()
    {
        DaemonController::consoleLog('Generating_new_admins_simple');

        $now = Carbon::now();

        $confirmations = Confirmation::valid()->with('baseOrder.user')->get();

        $steamid = [];

        foreach ($confirmations as $confirmation) {
            $steam2 = DaemonController::getSteam2ID($confirmation->baseOrder->user->steamid);
            $steamid[] = [
                'id' => $steam2,
                'confirmation' => $confirmation,
            ];
            $confirmation->baseOrder->server_uploaded = true;
            $confirmation->baseOrder->save();
        }

        $view = View::make('admins_simple_ini', [
            'list' => $steamid,
            'html' => false,
        ]);
        if (env('UPDATE_SERVER') == 'true') {
            Storage::put('admins_simple.ini', $view);

            DaemonController::updateSourceMod();
        }

    }
}
