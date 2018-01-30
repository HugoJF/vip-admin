<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokenOrder extends Model
{
    protected $table = 'token-orders';

    public function baseOrder()
    {
        return $this->morphOne('App\Order', 'orderable');
    }

    public function token()
    {
        return $this->hasOne('App\Token');
    }

    public function stateText()
    {
        if($this->token()->exists()) {
            return 'Token used';
        } else {
            return 'Missing token';
        }
    }

    public function stateClass()
    {
        $s = [
            'Token used' => 'success',
            'Missing token' => 'danger',
        ];

        $state = $this->stateText();

        if(array_key_exists($state, $s)) {
            return $s[$state];
        } else {
            return 'danger';
        }
    }

    public function currentStep()
    {
        $step = 1;

        if ($this->token()->exists()) {
            $step++;
        } else {
            return $step;
        }

        if ($this->baseOrder && $this->baseOrder->confirmation()->first()) {
            $step++;
        } else {
            return $step;
        }

        if ($this->baseOrder->server_uploaded) {
            $step++;
        } else {
            return $step;
        }

        return $step;
    }
}
