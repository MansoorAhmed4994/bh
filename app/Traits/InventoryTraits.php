<?php

namespace App\Traits; 
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

//Models
use App\Models\Client\ManualOrders;
use App\Models\Inventory;
use App\Models\Client\Customers;


trait InventoryTraits {
    
    
    
    public function updateorderprice($order_id)
    {
        $inventories = Inventory::leftJoin('products', 'inventories.products_id', '=', 'products.id')->select('reference_id','products_id','inventories.id as id','products.name as name','inventories.sale as sale')->where(['inventories.reference_id'=>$order_id,'inventories.stock_status' => 'out'])->get();
        $price = 0; 
        foreach($inventories as $inventory)
        {
            $price += $inventory->sale;
        } 
        $price = $price;
        $manualorders = ManualOrders::find($order_id);
        // dd($order_id);
        $manualorders->price = ($price); 
        
        
        $manualorders->save(); 
        return $price; 
    }
    
    
}