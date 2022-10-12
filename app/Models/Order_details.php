<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_details extends Model
{
    public $timestamps = false;
    protected $fillable = ['inventory_id','sku','order_id','discount','qty','created_by','updated_by','status'];
    
    public function inventory()
    {
        return $this->belongsToOne(Inventory::class,'inventory_id','id');
    }
    
    public function manualorders()
    {
        return $this->belongsToOne(ManualOrders::class,'order_id','id');
    }
}
