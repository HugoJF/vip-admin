<?php

namespace App;

use App\Http\Controllers\DaemonController;
use App\Http\Controllers\DaemonController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class Confirmation extends Model
{
    protected $dates = [
        'start_period',
        'end_period',
        'created_at',
        'updated_at',
    ];

    public function baseOrder()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();

        return $query->where([
            ['start_period', '<=', $now],
            ['end_period', '>=', $now],
        ]);
    }

    public static function syncServer()
    {
        DaemonController::consoleLog('Generating_new_admins_simple');

        $confirmations = self::valid()->with('baseOrder.user', 'baseOrder')->get();

        $steamid = [];

        foreach ($confirmations as $confirmation) {
            $steam2 = DaemonController::getSteam2ID($confirmation->baseOrder->user->steamid);
            $steamid[] = [
                'id'           => $steam2,
                'confirmation' => $confirmation,
            ];

            $confirmation->baseOrder->server_uploaded = true;
            $saved = $confirmation->baseOrder->save();
            if (!$saved) {
                flash()->error('Error saving confirmation details.');

                return redirect()->route('home');
            }
        }

        $view = View::make('admins_simple', [
            'list' => $steamid,
            'html' => false,
        ]);

        if (config('app.update_server') == 'true') {
            Storage::put('admins_simple.ini', $view);

            DaemonController::updateSourceMod();
        }

        return true;
    }

    public function isValid()
    {
        $now = Carbon::now();

        return $this->start_period <= $now && $this->end_period >= $now;
    }
}
