<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','updated_by','status'];
     
    
    public function Products()
    {
        return $this->belongsToOne(Products::class,'product_id','id');
    }
}
