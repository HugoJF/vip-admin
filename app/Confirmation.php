<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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

    public function scopeValid($query)
    {
        $now = Carbon::now();

        return $query->where([
            ['start_period', '<', $now],
            ['end_period', '>', $now],
        ]);
    }

    public function isValid()
    {
        $now = Carbon::now();

        return $this->start_period < $now && $this->end_period > $now;
    }
}
