<?php

namespace App;

use App\Interfaces\IOrder;
use Illuminate\Database\Eloquent\Model;

class TokenOrder extends Model implements IOrder
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

    public function status()
    {
        return [
            'text'  => $this->stateText(),
            'class' => $this->stateClass(),
        ];
    }

    public function paid()
    {
        return true;
    }

    public function type($type)
    {
        $types = ['App\TokenOrder', 'TokenOrder', 'Token'];

        return in_array($type, $types);
    }

    public function canGenerateConfirmation($flashError = false)
    {
        $should = $this->token()->exists();

        if (!$should && $flashError) {
            flash()->error(__('messages.model-token-orders-cannot-generate-confirmation'));
        }

        return $should;
    }

    public function recheck()
    {
        return true;
    }

    public function step()
    {
        $step = 1;

        if ($this->token()->exists()) {
            $step++;
        } else {
            return $step;
        }

        if ($this->baseOrder && $this->confirmed()) {
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

    private function stateText()
    {
        if ($this->confirmed()) {
            return 'Confirmed';
        } elseif ($this->token()->exists()) {
            return 'Token used';
        } else {
            return 'Missing token';
        }
    }

    private function stateClass()
    {
        $s = [
            'Confirmed'     => 'success',
            'Token used'    => 'primary',
            'Missing token' => 'danger',
        ];

        $state = $this->stateText();

        if (array_key_exists($state, $s)) {
            return $s[$state];
        } else {
            return 'danger';
        }
    }

    /**
     * Check if Order is confirmed.
     *
     * @return mixed
     */
    public function confirmed()
    {
        return isset($this->baseOrder->confirmation);
    }
}
