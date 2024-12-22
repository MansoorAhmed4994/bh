<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client\ManualOrders;
class LeopordCities extends Model
{
    public $timestamps = false;
    //
    
    public function manual_orders()
    {
        return $this->hasMany(ManualOrders::class,'cities_id','id');
    }
}
