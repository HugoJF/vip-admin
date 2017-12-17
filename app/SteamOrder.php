<?php

namespace App;

use App\Http\Controllers\DaemonController;
use Illuminate\Database\Eloquent\Model;

class SteamOrder extends Model
{
    protected $table = 'steam-orders';

    protected $fillable = ['tradeoffer_id', 'tradeoffer_state'];

    public function baseOrder()
    {
        return $this->morphOne('App\Order', 'orderable');
    }

    public function refresh()
    {
        $id = $this->attributes['tradeoffer_id'];

        $offer = DaemonController::getTradeOffer($id);

        if ($offer === false || !property_exists($offer, 'state')) {
            return false;
        }
        $this->attributes['tradeoffer_state'] = $offer->state;

        $this->save();

        return $offer;
    }

    public function accepted()
    {
        return $this->attributes['tradeoffer_state'] === 3;
    }

    public function active()
    {
        return $this->attributes['tradeoffer_state'] === 2;
    }

    public function disabled()
    {
        return !$this->accepted() && !$this->active();
    }

    public function notSent()
    {
        return $this->attributes['tradeoffer_id'] == null;
    }

    public function stateText()
    {
        $state = $this->attributes['tradeoffer_state'];

        if ($state) {
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
            }
        } else {
            return 'Inexistent';
        }
    }
}
