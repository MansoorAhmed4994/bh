<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    // protected $guarded = ['id'];
    // public function manual_orders()
    // {
        
    //     // return $this->hasMany(ManualOrders::class,'cities_id','id');
    //     return $this->hasMany(ManualOrders::class);
    // }
    
    public function manual_orders()
    {
        return $this->hasMany(ManualOrders::class,'cities_id','id');
    }
    //
}
