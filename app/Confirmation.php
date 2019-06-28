<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Confirmation extends Model
{
    use SoftDeletes;

    protected $dates = [
        'start_period',
        'end_period',
        'created_at',
        'updated_at',
    ];

    public function baseOrder()
    {
        return $this->belongsTo('App\Order', 'order_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }

    public function getRouteKeyName()
    {
        return 'public_id';
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();

        return $query->where([
            ['start_period', '<=', $now],
            ['end_period', '>=', $now],
        ]);
    }

    public function scopeNotExpired($query)
    {
        $now = Carbon::now();

        return $query->where([
            ['end_period', '>=', $now],
        ]);
    }

    public function status()
    {
        return [
            'text'  => $this->statusText(),
            'class' => $this->statusClass(),
        ];
    }

    private function statusText()
    {
        $now = Carbon::now();

        if ($this->isValid()) {
            return 'Valid';
        } else {
            if ($this->end_period > $now) {
                return 'Valid, not used';
            } else {
                return 'Expired';
            }
        }
    }

    private function statusClass()
    {
        if ($this->statusText() == 'Expired') {
            return 'danger';
        } else {
            return 'success';
        }
    }

    public function isValid()
    {
        $now = Carbon::now();

        return $this->start_period <= $now && $this->end_period >= $now;
    }
}
