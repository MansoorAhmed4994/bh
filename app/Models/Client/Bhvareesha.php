<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Bhvareesha extends Model
{
    public function manual_orders()
    {
        return $this->hasMany(ManualOrders::class,'customers_id','id');
    }
    //
}
