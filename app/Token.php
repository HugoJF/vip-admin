<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $table = 'tokens';

    protected $guarded = ['duration', 'expiration', 'token'];

    public function tokenOrder()
    {
        return $this->belongsTo('App\TokenOrder');
    }

    public function status()
    {
        $expiration_date = \Carbon\Carbon::now()->addHours($this->expiration);

        if ($this->tokenOrder()->exists()) {
            return 'Used';
        }

        if ($expiration_date->isPast()) {
            return 'Expired';
        } else {
            return 'Unused';
        }
    }

    public function statusClass()
    {
        $status = [
            'Expired' => 'danger',
            'Unused'  => 'success',
            'Used'    => 'info',
        ];

        $s = $this->status();

        if (array_has($status, $s)) {
            return $status[$s];
        } else {
            return 'danger';
        }
    }
}
