<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    protected $dates = [
        'start_period',
        'end_period',
        'created_at',
        'updated_at'
    ];

    public function baseOrder()
    {
        return $this->belongsTo('App\Order', 'order_id');
    }
}
