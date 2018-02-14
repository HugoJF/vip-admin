<?php

namespace App;

use App\Classes\Daemon;
use Illuminate\Database\Eloquent\Model;

class SteamOrder extends Model
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

    public function refresh()
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

    public function cancel()
    {
        Daemon::cancelTradeOffer($this->tradeoffer_id);
        $this->refresh();
        $this->save();
    }

    public function accepted()
    {
        return $this->tradeoffer_state === 3;
    }

    public function active()
    {
        return $this->tradeoffer_state === 2;
    }

    public function disabled()
    {
        return !$this->accepted() && !$this->active();
    }

    public function notSent()
    {
        return $this->tradeoffer_id == null;
    }

    public function currentStep()
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

    public function stateText()
    {
        $state = $this->tradeoffer_state;
        $confirmed = $this->baseOrder->confirmation != null;

        if ($confirmed) {
            return 'Confirmed';
        } elseif ($state) {
            switch ($state) {
                case 1:
                    return 'Invalid';
                    break;
                case 2:
                    return 'Active';
                    break;
                case 3:
                    return 'Accepted';
                    break;
                case 4:
                    return 'Countered';
                    break;
                case 5:
                    return 'Expired';
                    break;
                case 6:
                    return 'Canceled';
                    break;
                case 7:
                    return 'Declined';
                    break;
                case 8:
                    return 'InvalidItems';
                    break;
                case 9:
                    return 'CreatedNeedsConfirmation';
                    break;
                case 10:
                    return 'CanceledBySecondFactor';
                    break;
                case 11:
                    return 'InEscrow';
                    break;
                default:
                    return 'Unknown';
                    break;
            }
        } else {
            return 'Trade Offer not sent';
        }
    }

    public function stateClass()
    {
        $state = $this->tradeoffer_state;
        $confirmed = $this->baseOrder->confirmation != null;

        if ($confirmed) {
            return 'success';
        } elseif ($state) {
            switch ($state) {
                case 2:
                    // return 'Active';
                    return 'primary';
                    break;
                case 3:
                    // return 'Accepted';
                    return 'success';
                    break;

                case 1:  // return 'Invalid';
                case 4:  // return 'Countered';
                case 5:  // return 'Expired';
                case 6:  // return 'Canceled';
                case 7:  // return 'Declined';
                case 8:  // return 'InvalidItems';
                case 9:  // return 'CreatedNeedsConfirmation';
                case 10: // return 'CanceledBySecondFactor';
                case 11: // return 'InEscrow';
                default: // return 'Unknown';
                    return 'danger';
                    break;
            }
        } else {
            return 'info';
        }
    }
}
