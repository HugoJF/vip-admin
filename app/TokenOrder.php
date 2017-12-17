<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenOrder extends Model
{
    protected $table = 'token-orders';

    public function order()
    {
        return $this->morphOne('App\Order', 'orderable');
    }
}
