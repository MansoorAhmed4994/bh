<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class remaining_inventories extends Model
{
    public $timestamps = false;
    protected $fillable = ['qty','cost'];
    public function Products()
    {
        return $this->belongsToOne(Products::class,'product_id','id');
    }
    //
}
