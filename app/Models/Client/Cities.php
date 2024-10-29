<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

use App\Models\Client\ManualOrders;
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
