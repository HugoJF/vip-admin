<?php

namespace App;

use App\Interfaces\IOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model implements IOrder
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

    public function status($status = null)
    {
        if ($status === null) {
            return $this->orderable->status();
        } else {
            return $this->orderable->status() == $status;
        }
    }

    public function recheck()
    {
        return $this->orderable->recheck();
    }

    public function step()
    {
        return $this->orderable->step();
    }

    public function type($type)
    {
        return $this->orderable->type($type);
    }

    public function canGenerateConfirmation($flashError = false)
    {
        return $this->orderable->canGenerateConfirmation($flashError);
    }
}
