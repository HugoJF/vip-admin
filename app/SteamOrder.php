<?php

namespace App;

use App\Classes\Daemon;
use App\Interfaces\IOrder;
use Illuminate\Database\Eloquent\Model;

class SteamOrder extends Model implements IOrder
{
    protected $table = 'steam-orders';

    protected $dates = ['tradeoffer_sent'];

    protected $fillable = ['tradeoffer_id', 'tradeoffer_state'];

    public function baseOrder()
    {
        return $this->morphOne('App\Order', 'orderable')->withTrashed();
    }

    public function getRouteKeyName()
    {
        return 'public_id';
    }

    public function type($type)
    {
        $types = ['App\SteamOrder', 'SteamOrder', 'Steam'];

        return in_array($type, $types);
    }

    public function recheck()
    {
        $id = $this->attributes['tradeoffer_id'];

        $this->touch();

        $this->save();

        $offer = Daemon::getTradeOffer($id);

        if ($offer === false || !property_exists($offer, 'state')) {
            return false;
        }

        $this->attributes['tradeoffer_state'] = $offer->state;

        $this->save();

        return $offer;
    }

    public function canGenerateConfirmation($flashError = false)
    {
        $should = $this->paid();

        if (!$should && $flashError) {
            flash()->error(__('messages.model-steam-orders-cannot-generate-confirmation'));
        }

        return $should;
    }

    public function paid()
    {
        return $this->accepted();
    }

    public function step()
    {
        $step = 1;

        if ($this->tradeoffer_id) {
            $step++;
        } else {
            return $step;
        }

        if ($this->tradeoffer_state == 3) {
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

    public function status()
    {
        $state = $this->tradeoffer_state;

        if (isset($this->baseOrder->confirmation)) {
            $state = 'confirmed';
        }

        return [
            'status' => $state,
            'text'   => $this->stateText($state),
            'class'  => $this->stateClass($state),
        ];
    }

    public function cancel()
    {
        Daemon::cancelTradeOffer($this->tradeoffer_id);
        $this->refresh();
        $this->save();
    }

    private function accepted()
    {
        return $this->tradeoffer_state === 3;
    }

    private function active()
    {
        return $this->tradeoffer_state === 2;
    }

    private function disabled()
    {
        return !$this->accepted() && !$this->active();
    }

    private function notSent()
    {
        return $this->tradeoffer_id == null;
    }

    public function confirmed()
    {
        return $this->status()['status'] == 'confirmed';
    }

    private function stateText($state)
    {
        switch ($state) {
            case 'confirmed':
                return 'Confirmed';
            case 1:
                return 'Invalid';
            case 2:
                return 'Active';
            case 3:
                return 'Accepted';
            case 4:
                return 'Countered';
            case 5:
                return 'Expired';
            case 6:
                return 'Canceled';
            case 7:
                return 'Declined';
            case 8:
                return 'InvalidItems';
            case 9:
                return 'CreatedNeedsConfirmation';
            case 10:
                return 'CanceledBySecondFactor';
            case 11:
                return 'InEscrow';
            default:
                return 'TradeOfferNotSent';
        }
    }

    private function stateClass($state)
    {
        switch ($state) {
            case 'confirmed':
                return 'success';
            case 2:
                return 'primary'; // return 'Active';
            case 3:
                return 'success'; // return 'Accepted';
            case 1:  // return 'Invalid';
            case 4:  // return 'Countered';
            case 5:  // return 'Expired';
            case 6:  // return 'Canceled';
            case 7:  // return 'Declined';
            case 8:  // return 'InvalidItems';
            case 9:  // return 'CreatedNeedsConfirmation';
            case 10: // return 'CanceledBySecondFactor';
            case 11: // return 'InEscrow';

                return 'danger';
            default: // return 'Unknown';

                return 'warning';
        }
    }
}
