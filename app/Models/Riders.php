<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Riders extends Authenticatable
{
    public function manual_orders()
    {
        return $this->hasMany(ManualOrders::class);
    }
    //
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
    //
}
