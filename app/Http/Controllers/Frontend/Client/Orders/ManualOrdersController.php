<?php

namespace App\Http\Controllers\Frontend\Client\Orders;

use App\Http\Controllers\Controller; 

use App\Traits\ManualOrderTraits;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ManualOrdersController extends Controller
{
    private $images_path =  'storage/images/orders/manual-orders/';
    use ManualOrderTraits;
    
    
    public function __construct()
    {
        //dd();
        //$this->middleware('auth');
    }
    
    
    public function store(Request $request)
    { 
        
        //dd($ifexist);
            
        $status = $this->CreateOrder($request);
            
        //dd($status);
        return redirect()->route('Frontend.ManualOrders.create')->with('success', $status);
    }
    
    public function create()
    {
        return view('frontend.client.orders.manual-orders.create');
        //
    }
    
    //
}
