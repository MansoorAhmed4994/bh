<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_details extends Model
{
    public function inventory()
    {
        return $this->belongsToOne(Inventory::class,'inventory_id','id');
    }
    
    public function manualorders()
    {
        return $this->belongsToOne(ManualOrders::class,'order_id','id');
    }
}
