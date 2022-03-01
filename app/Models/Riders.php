<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Riders extends Model
{
    public function manual_orders()
    {
        return $this->hasMany(ManualOrders::class);
    }
    //
}
