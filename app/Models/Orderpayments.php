<?php

namespace App\Models;
use App\Models\Client\ManualOrders;
use Illuminate\Database\Eloquent\Model;

class Orderpayments extends Model
{
    
    public $timestamps = false;
    protected $fillable = ['consignment_id','order_id','cash_handling_charges','fuel_surcharge','weight_charges','current_payment_status','message','amount','charges','datetime','gst','payment_id','payable','type','created_by','updated_by','status'];
    
    
    public function manual_orders()
    {
        return $this->belongsTo(ManualOrders::class,'order_id');
    }
     
    //
}
