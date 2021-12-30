<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ManualOrders extends Model
{
    public function customers()
    {
        return $this->belongsToOne(Customers::class,'customers_id','id');
    }
    //
}
