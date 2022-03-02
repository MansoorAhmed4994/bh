<?php

namespace App\Http\Controllers\Frontend\Client\Orders;

use App\Http\Controllers\Controller; 

use App\Traits\ManualOrderTraits;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator; 
use Cookie;
// use Response;
use Carbon\Carbon;
use Session;

class ManualOrdersController extends Controller
{
    private $images_path =  'storage/images/orders/manual-orders/';
    use ManualOrderTraits;
    
    
    public function __construct()
    {
        //dd();
        //$this->middleware('auth');
    }
    
    
    
    public function index(Request $request)
    {
        $number = $request->cookie('number');
        if($request->cookie('number') == null)
        {
            return view('frontend.client.orders.manual-orders.authentication');
        }
        else
        {
        //dd($response);
        // $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')->orderBy('manual_orders.created_at', 'DESC')->paginate(5);
        $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')
        ->where('manual_orders.receiver_number',$number)
        ->orderBy('manual_orders.id', 'ASC')
        ->select('manual_orders.id','manual_orders.customers_id','customers.first_name','manual_orders.description','manual_orders.reciever_address','customers.last_name','customers.number','customers.address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at')
        ->paginate(200);
        //dd($list);
        return view('frontend.client.orders.manual-orders.list')->with('list',$list);
        }
    }
    
    public function verify_authentication(Request $request)
    {
        //dd($request->number);
        $response = new Response();
        $response->withCookie(cookie('number',$request->number,10));
        return $response;
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
    
    public function authentication()
    { 
        return view('frontend.client.orders.manual-orders.authentication');
    }
    
    
    
    // public function verify_authentication(Request $request)
    // { 
    //     $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.receiver_number',$request->number)
    //     ->orderBy('manual_orders.id', 'ASC')
    //     ->select('manual_orders.id','manual_orders.customers_id','customers.first_name','manual_orders.description','manual_orders.reciever_address','customers.last_name','customers.number','customers.address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at')
    //     ->first();
    //     //dd($ManualOrder);
    //     Cookie::queue(Cookie::make('number',$ManualOrder->receiver_number));
    //     session(['number' => $ManualOrder->receiver_number]);
    //     return view('frontend.client.orders.manual-orders.create')->with('list',$ManualOrder);
    // }
    
    public function show($ManualOrder)
    
    { 
         
        //return dd();
        // return Response()->json([
        //         "success" => false,
        //         "file" => ''
        //   ]);
        $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'manual_orders.customers_id')->where('customers.number',$ManualOrder)->get();
        if(!$ManualOrder->isEmpty())
        {
            return $ManualOrder->first();
        }
        else
        {
            return 'no valhhhhue';
        }
       
    }
    
    public function customer_order_confirmation($id)
    {
        $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'manual_orders.customers_id')->where('manual_orders.id',$id)->first();
        //dd(ManualOrders::leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')); 
       // dd($ManualOrder) ;
        return view('frontend.client.orders.manual-orders.view')->with('ManualOrder',$ManualOrder);
        //dd('eoub');
    }
    
    public function customer_order_confirmed(Request $request, ManualOrders $ManualOrder)
    {
        //dd($ManualOrder); 
        $ManualOrder->status = 'confirmed';
        dd($ManualOrder->save());
    //     $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'manual_orders.customers_id')->where('manual_orders.id',$id)->first();
    //     //dd(ManualOrders::leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')); 
    //   // dd($ManualOrder) ;
    //     return view('frontend.client.orders.manual-orders.view')->with('ManualOrder',$ManualOrder);
        //dd('eoub');
    }
    
    
}
