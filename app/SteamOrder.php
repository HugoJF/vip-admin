<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SteamOrder extends Model
{
    protected $table = 'steam-orders';

    protected $fillable = ['tradeoffer_id'];

    public function baseOrder()
    {
        return $this->morphOne('App\Order', 'orderable');
    }
}
