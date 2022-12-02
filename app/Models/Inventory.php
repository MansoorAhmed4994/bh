<?php

namespace App\Models;


use App\Models\Products;
use Illuminate\Database\Eloquent\Model; 

class Inventory extends Model
{ 
    // public $timestamps = false;
    // protected $fillable = ['products_id','warehouse_id','customer_id','stock_status','qty','reference_id','stock_type','cost','sale','discount','created_by','updated_by','status'];
     protected $guarded = ['id'];
    public function Products()
    {
        return $this->belongsTo(Products::class, 'id'); 
    }
    
    public function order_details()
    {
        return $this->hasMany(Order_details::class);
    }
    
    protected static function boot()
    {

        parent::boot();

        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->id;
            }
        });
    }
}
