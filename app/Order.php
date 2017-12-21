<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';


    public function orderable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function confirmation()
    {
        return $this->hasOne('App\Confirmation');
    }

    public function isSteamOffer()
    {
        return $this->orderable_type == 'App\SteamOrder';
    }

}
