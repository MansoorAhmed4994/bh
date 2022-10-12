<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orderpayments extends Model
{
    
    public $timestamps = false;
    protected $fillable = ['cash_handling_charges','fuel_surcharge','weight_charges','current_payment_status','message','amount','charges','datetime','gst','payment_id','payable','type'];
    public function order_id()
    {
        return $this->belongsToOne(ManualOrders::class,'order_id','id');
    }
     
    //
}
