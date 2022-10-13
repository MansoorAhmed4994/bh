<?php

namespace App\Models\Client;


use App\Models\Orderpayments;
use Illuminate\Database\Eloquent\Model;

class ManualOrders extends Model
{
    public function customers()
    {
        return $this->belongsTo(Customers::class);
    }
    
    
    public function riders()
    {
        return $this->belongsToOne(Riders::class);
    }
    
    
    
    public function orderpayments()
    {
        return $this->hasMany(Orderpayments::class,'order_id','id');
    }
    //
}
