<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
