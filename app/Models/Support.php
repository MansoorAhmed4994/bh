<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client\Customers;

class Support extends Model
{
    protected $guarded = [];
    
    public function customers()
    {
        return $this->belongsTo(Customers::class,'customers_id','id');
    }
    
    public function manual_orders()
    {
        return $this->hasMany(ManualOrders::class,'order_id','id');
    }
    //
}
