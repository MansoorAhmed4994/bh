<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    public $timestamps = false;
    protected $fillable = ['sku','created_by','updated_by','status'];
    public function Products()
    {
        return $this->belongsToOne(Products::class,'product_id','id');
    }
    
    public function order_details()
    {
        return $this->hasMany(Order_details::class);
    }
}
