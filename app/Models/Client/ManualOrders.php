<?php

namespace App\Models\Client;


use App\Models\Orderpayments;
use App\Models\Client\Cities;
use Illuminate\Database\Eloquent\Model;

class ManualOrders extends Model
{
    public function customers()
    {
        return $this->belongsTo(Customers::class,'customers_id','id');
    }
    
    
    public function riders()
    {
        return $this->belongsToOne(Riders::class);
    }
    
    public function cities()
    {
        // return $this->belongsTo(Cities::class,'city','id');
        return $this->belongsTo(Cities::class,'cities_id','id'); 
        // return $this->hasMany(Cities::class,'cities_id','id'); 
        
    }
    
    
    
    public function orderpayments()
    {
        return $this->hasMany(Orderpayments::class,'order_id','id');
    }
    //
}
