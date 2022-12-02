<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $timestamps = false;
    protected $fillable = ['first_name','last_name','address','number','email','whatsapp_number','description','remarks','status','created_by','updated_by'];
    public function manual_orders()
    {
        return $this->hasMany(ManualOrders::class,'customers_id','id');
    }
    //
}
