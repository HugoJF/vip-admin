<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OPSkinsCache extends Model
{
    protected $table = 'opskins-cache';

    protected $fillable = ['name', 'price'];
}
