<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ManualOrders extends Model
{
    public function customers()
    {
        return $this->belongsToOne(Customers::class,'customers_id','id');
    }
    
    
    public function riders()
    {
        return $this->belongsToOne(Riders::class,'riders_id','id');
    }
    //
}
