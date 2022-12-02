<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    // protected $fillable = ['sku','slug','category_id','sale_price','discount_price','name','wieght','weight_type','created_by','updated_by','updated_at','status'];
     
        protected $guarded = ['id'];
        
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    } 
    
    public function remaining_inventories()
    {
        return $this->hasMany(remaining_inventories::class);
    }
    
    public function category()
    {
        return $this->hasMany(Category::class);
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
