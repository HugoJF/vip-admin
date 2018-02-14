<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $guarded = ['extra_tokens'];

    public function orderable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getRouteKeyName()
    {
        return 'public_id';
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
