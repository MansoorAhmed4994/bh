<?php
 
namespace App\Http\Controllers\Client\Orders;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

//Models
use App\Models\Client\ManualOrders;
use App\Models\Riders;
use App\Models\Inventory;
use App\Models\Client\Cities;
use App\Models\Order_details;
use App\Models\Client\Customers;
use App\Models\ActivityLogs;
use App\Models\Category;
use App\Models\Client\CustomerPayments; 
use App\Models\User; 

//Traits
use App\Traits\MNPTraits;
use App\Traits\TraxTraits;
use App\Traits\LeopordTraits;
use App\Traits\ManualOrderTraits;
use App\Traits\InventoryTraits;


use Illuminate\Support\Picqer\Barcode;

class ManualOrdersController extends Controller 
{ 
    private $images_path =  'storage/images/orders/manual-orders/';

    /**
     * Display a listing of the resource.
     
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

        // dd(Auth::guard('admin')->check());
        //$this->middleware('auth');
    }

    use ManualOrderTraits;
    use MNPTraits;
    use LeopordTraits;
    use TraxTraits;
    use InventoryTraits;
    
    public function OrderFieldList()
    {
        return array(
            'manual_orders.id',
            'customers.first_name',
            'customers.last_name',
            'customers.number',
            'customers.address',
            'customers.loyality_count',
            'manual_orders.consignment_id',
            'manual_orders.advance_payment',
            'manual_orders.cod_amount',
            'manual_orders.customers_id',
            'manual_orders.description',
            'manual_orders.receiver_number',
            'manual_orders.reciever_address',
            'manual_orders.cities_id',
            'manual_orders.price',
            'manual_orders.images',
            'manual_orders.total_pieces',
            'manual_orders.date_order_paid',
            'manual_orders.status',
            'manual_orders.created_at',
            'manual_orders.updated_at',
            'manual_orders.shipment_company',
            DB::raw("(select count(*) from manual_orders where customers.id = manual_orders.customers_id and status = 'return') as return_count"), 
            DB::raw("(select count(*) from manual_orders where customers.id = manual_orders.customers_id and status = 'dispatched') as dispatched_count"), 
            DB::raw("CONCAT(t.first_name,'') as updated_by"), 
            DB::raw("CONCAT(users.first_name,'') as created_by"), 
            'manual_orders.status_reason', 
            'manual_orders.assign_to'
            ); 

    }
    
    public function index(Request $request)
    {
        $order_id = $request->search_order_id;
        $search_text = $request->search_text;
        $order_status = $request->order_status;
        $order_by = $request->order_by;
        $date_from = $request->date_from;
        $date_to =  $request->date_to;
        // $customer_d =   $request->search_order_id;
        
        $date_by = 'created_at';
        $query = ManualOrders::query();
        $query = $query
        ->leftJoin('customers', 'manual_orders.customers_id', '=', 'customers.id')
        ->leftJoin('users', 'manual_orders.created_by', '=', 'users.id') 
        ->leftJoin('users as t', 'manual_orders.updated_by', '=', 't.id') 
        ->select($this->OrderFieldList()); 
        if($order_id != '')
        { 
            // $query = $query->where('manual_orders.id',$order_id);
            $query = $query->
            where(function ($query) use ($order_id) {
                $query->where('manual_orders.id',$order_id)
                    ->orWhere('manual_orders.customers_id',$order_id);
            });
        }
        else if($search_text != '')
        {
            $query = $query->
            where(function ($query) use ($search_text) {
                $query->where('customers.first_name','like',$search_text.'%')
                    ->orWhere('customers.first_name','like','%'.$search_text.'%')
                    ->orWhere('customers.first_name','like','%'.$search_text)
                    ->orWhere('customers.last_name','like',$search_text.'%')
                    ->orWhere('customers.last_name','like','%'.$search_text.'%')
                    ->orWhere('customers.last_name','like','%'.$search_text)
                    ->orWhere('customers.number','like','%'.$search_text) 
                    ->orWhere('customers.number','like',$search_text.'%')
                    ->orWhere('customers.number','like','%'.$search_text.'%')
                    ->orWhere('manual_orders.id','like','%'.$search_text.'%')
                    ->orWhere('manual_orders.consignment_id','like','%'.$search_text.'%');
            });
            
        }
        
        //========================filter on date
        if($request->date_by != '')
        { 
            $date_by = $request->date_by;
        }
        
        if($date_from != '' && $date_to == '')
        { 
        
            $query = $query->where(DB::raw("(DATE_FORMAT(manual_orders.".$date_by.",'%Y-%m-%d'))") ,$date_from); 
        }
        elseif($date_from == '' && $date_to != '')
        { 
            $query = $query->where(DB::raw("(DATE_FORMAT(manual_orders.".$date_by.",'%Y-%m-%d'))"),$date_to);
            
            
        }
        elseif($date_from != '' && $date_to != '' && $date_from == $date_to )
        { 
            $query = $query->where(DB::raw("(DATE_FORMAT(manual_orders.".$date_by.",'%Y-%m-%d'))"),$date_to);
            
        }
        elseif($date_from != '' && $date_to != '')
        {
            $query = $query->whereBetween("manual_orders.".$date_by ,[$date_from,$date_to]);
            
        }
        
        
        //========================get user roles
        $user_id = User::find(auth()->user()->id);
        $user_roles = $user_id->roles()->get()->pluck('name')->toArray();

        
        //========================filter on assign column
        if(in_array('author', $user_roles) || in_array('admin', $user_roles))
        { 
            // dd('');
        } 
        elseif(in_array('user', $user_roles))
        { 
            $query = $query->where("manual_orders.assign_to" ,$user_id->id);
        }
        
        if($order_by != '')
        {
            $query = $query->orderBy($order_by, 'ASC');
        }
        else
        {
            $query = $query->orderBy('manual_orders.id', 'DESC');
        }
        
        
        
        //========================filter on status column
        if($order_status != '')
        {
            // dd(in_array('calling', $user_roles));
            if(in_array('calling', $user_roles))
            {
                $query->where('manual_orders.status','!=','pending'); 
            } 
            elseif($order_status == 'all')
            {
                $query->where('manual_orders.status','like','%%'); 
            }  
            else
            {
                $query = $query->where('manual_orders.status',$order_status);
            } 
        }
        elseif($order_status == 'all')
        {
            if(in_array('calling', $user_roles))
            {
                $query->where('manual_orders.status','!=','pending'); 
            } 
            else
            {
                $query->where('manual_orders.status','like','%%'); 
            }
        }
        
        
        $users = User::select('*')->get();
        $list = $query->paginate(20); 
        $statuses = get_active_order_status_list();
        $catgories = product_child_categories();
        // dd($list->first());
        
        return view('client.orders.manual-orders.list')->with(['list'=>$list,'users'=>$users,'statuses'=>$statuses,'catgories'=>$catgories]); 
    }
    
    public function InActiveCustomers(Request $request)
    {
        $from_date = Carbon::now()->subDays(90)->toDateTimeString(); 
        $views_customer_data = DB::table('manual_orders')
            ->select('customers.id', 
            DB::raw('count(*) as total_purchase'),
            'customers.first_name', 
            'customers.status',
            'customers.number as receiver_number',
            'customers.whatsapp_number as number',
            'customers.created_at',
            'customers.description',
            'customers.address as reciever_address',
            'customers.remarks')
            ->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')
            ->where('manual_orders.created_at', '<',$from_date) 
            
            ->groupBy('customers.id','customers.first_name','customers.status','customers.number','customers.whatsapp_number','customers.created_at','customers.description','customers.address','customers.remarks')
            ->orderBy('total_purchase', 'ASC')
            ->paginate(100);
            // dd($views_customer_data);
        return view('client.orders.manual-orders.inactive_customers')->with('list',$views_customer_data); 
            
        // dd($views_customer_data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $cities = $this->LeopordGetCities()->city_list;
        // dd($cities[0]->shipment_type);
        // $cities = $this->LeopordGetCities()->cities;
        return view('client.orders.manual-orders.create')->with([ 'cities'=>$cities]);
        //
    }
    
    public function search_order( Request $request)
    {
        $order_id = $request->search_order_id;
        if($order_id != '')
        {
            $search_test = $request->search_text;
            $order_status = $request->order_status;
            $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.id',$order_id)
            ->select($this->OrderFieldList())
            ->paginate(20);
        }
        else
        {
            $search_test = $request->search_text;
            $order_status = $request->order_status;
            $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->
            where(function ($query) use ($search_test) {
                $query->where('customers.first_name','like',$search_test.'%')
                    ->orWhere('customers.first_name','like','%'.$search_test.'%')
                    ->orWhere('customers.first_name','like','%'.$search_test)
                    ->orWhere('customers.last_name','like',$search_test.'%')
                    ->orWhere('customers.last_name','like','%'.$search_test.'%')
                    ->orWhere('customers.last_name','like','%'.$search_test)
                    ->orWhere('customers.number','like','%'.$search_test) 
                    ->orWhere('customers.number','like',$search_test.'%')
                    ->orWhere('customers.number','like','%'.$search_test.'%')
                    ->orWhere('manual_orders.id','like','%'.$search_test.'%')
                    ->orWhere('manual_orders.consignment_id','like','%'.$search_test.'%');
            })->where('manual_orders.status','like',$order_status.'%')
            ->orderBy('manual_orders.id', 'DESC')
            ->select($this->OrderFieldList())
            ->paginate(20);
            
        }
        //dd($list);
        $statuses = get_active_order_status_list();
        return view('client.orders.manual-orders.list')->with(['list'=>$list,'statuses'=>$statuses]);
    }
    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        
        $status = $this->CreateOrder($request);
        toastr()->success('Order has been saved successfully!');
        return back();
        // return redirect()->route('ManualOrders.create')->with('success', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'manual_orders.customers_id')->where('manual_orders.id',$id)->first();
        //dd(ManualOrders::leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')); 
       // dd($ManualOrder) ;
        return view('client.orders.manual-orders.view')->with('ManualOrder',$ManualOrder);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    public function details($Manualorders = null)
    {
        return view('client.orders.manual-orders.details');
    }
     
    public function edit($ManualOrder)
    { 
        $user_id = User::find(auth()->user()->id);
        $user_roles = $user_id->roles()->get()->pluck('name')->toArray();

        if(ManualOrders::find($ManualOrder)->status == 'dispatched')
        {
            if(Auth::guard('admin')->check())
            {
                
            }
            else
            {
                toastr()->error('Parcel is dispatched only admin can edit this order');
                return back();
            }
        }

        if(in_array('author', $user_roles) || in_array('admin', $user_roles) || Auth::guard('admin')->check())
        { 
            // dd('');
        } 
        elseif(auth()->user()->id == (ManualOrders::find($ManualOrder)->assign_to))
        { 
            
            
        }
        else
        { 
            toastr()->error('This is not your parcel, You Dont Have Permission to edit this order, contact Admin');
            return back();
        }
        
        // $cities = $this->get_trax_cities();
        $cities = $this->LeopordGetCities()->city_list;
        
        $leopordCities = $this->LeopordGetCities()->city_list;
        
        
        // $cities = $this->LeopordGetCities()->city_list;
        // dd($cities);
        $order_id = $ManualOrder;
        $this->UpdateReferenceNumberByOrderIds([$ManualOrder]);
        // dd($Manualorders);
        $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.id',$ManualOrder)->first();
        // dd($ManualOrder);
        if($ManualOrder == null)
        {
            dd('no such order id found');
        }
        
        $inventory = Inventory::leftJoin('products', 'inventories.products_id', '=', 'products.id')->
        select(
        'inventories.id as id',
        'products.sku as sku', 
        'products.name as name',
        'products.sale_price as sale'
        )
        ->where(['inventories.reference_id'=>$ManualOrder->id,'inventories.stock_status' => 'out'])->get();
        
        $advance_payment_status='';
        $advance_payments = CustomerPayments::where('order_id',$order_id)->get();
        // dd($advance_payment);
        if($advance_payments->isEmpty())
        {
            $advance_payment_status = ' No payment Found ';
        }
        else
        {
            $count = 1;
            foreach($advance_payments as $advance_payment)
            {
                if($advance_payment->status == 'approved')
                { 
                    $advance_payment_status .= ' Payment # '.$count.' Approved ';
                }
                else
                {
                    $advance_payment_status .= ' Payment # '.$count.' Not Approved ';
                    
                } 
                
            }
        } 
         $statuses = get_active_order_status_list();
        
        return view('client.orders.manual-orders.edit')->with(['ManualOrder'=>$ManualOrder, 'LeopordCities' => $leopordCities,'cities'=>$cities,'inventories'=>$inventory,'product_price'=>$this->updateorderprice($ManualOrder->id),'advance_payment_status'=>$advance_payment_status,'statuses'=>$statuses]);

        // if($Manualorders != null)
        // {
         
        // }
        // else
        // {
        //     return view('client.orders.manual-orders.edit')->with(['cities'=>$cities]);

        // }
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManualOrders $ManualOrder)
    {
        // dd($ManualOrder);
        $order_id = $ManualOrder->id;
        $validated = $request->validate([
 
            'first_name' => 'required',
            'number' => 'required',
            'address' => 'required',

        ]); 
        // $images_path =  'public/images/orders/manual-orders/';
        $images=array();
        if($files=$request->file('images')){
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                
                $file->move($this->images_path,$name);
                $images[]=$this->images_path.$name;
            }
        }  
        
        $customers = Customers::find($ManualOrder->customers_id);
        //dd($customers);
        $customers->first_name = $request->first_name;
        $customers->last_name = $request->last_name;
        $customers->address = $request->address;
        $customers->number = $request->number;
        $customers->email = '';
        $customers->whatsapp_number = $request->whatsapp_number;
        $customers->updated_by = Auth::id();
        $customers->status = 'active'; 
        $customers->save(); 
        $act_sta = create_activity_log(['table_name'=>'customers','ref_id'=>$ManualOrder->customers_id,'activity_desc'=>'Edit customer data','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.update',$order_id)]);

        
        //$manual_orders = new ManualOders();
        $ManualOrder->receiver_name = $request->receiver_name;
        $ManualOrder->receiver_number = $request->receiver_number;
        $ManualOrder->reciever_address = $request->reciever_address;  
        if($images != null)
        {
            if($ManualOrder->images != null)
            { 
                $ManualOrder->images = $ManualOrder->images.'|'.(implode("|",$images));
            }
            else
            {
                $ManualOrder->images = (implode("|",$images));
                
            }
            // dd($ManualOrder->images);
            // dd($ManualOrder->images.'|'.(implode("|",$images)));
        }      
        //payment details
        $ManualOrder->product_price = $request->product_price;
        $ManualOrder->dc = $request->dc;
        $ManualOrder->packaging_cost = $request->packaging_cost;
        $ManualOrder->price = $request->price;
        $ManualOrder->cod_amount = $request->cod_amount;
        $ManualOrder->advance_payment = $request->advance_payment;
        
        //Product details
        $ManualOrder->total_pieces = $request->total_pieces;
        $ManualOrder->weight = $request->weight;
        $ManualOrder->description = $request->description; 
        $ManualOrder->status = $request->order_status; 
        
        $traxdata = []; 
        $leoporddata = [];
        //Shipment details
        if($request->submit == "save")
        {
            if($request->shipment_type == 'trax')
            {
                $ManualOrder->service_type = $request->shipping_mode_id; 
                $ManualOrder->shipment_company = $request->shipment_type;
                $ManualOrder->cities_id = $request->city;
            }
            else if($request->shipment_type == 'leopord')
            {
                
                $ManualOrder->service_type = $request->leopord_shipment_type_id; 
                $ManualOrder->shipment_company = $request->shipment_type;
                $ManualOrder->cities_id = $request->leopord_city;
            }
            
            $status = $ManualOrder->save();
            $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$order_id,'activity_desc'=>'Edit order data','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.update',$order_id)]);

            // dd($status);
            if($status)
            {
                return redirect()->route('ManualOrders.index')->with('success', 'Order Updated Successfully');
            }
        }
        elseif($request->submit == 'saveandprint')
        { 
        
            if($request->shipment_type == 'trax')
            {
                $mytime = Carbon::now();
                $current_date_time = $mytime->toDateTimeString();
                $pickup_address_id = $this->get_trax_pickup_address(); 
                $reference_number= '('.$ManualOrder->id.')('.$current_date_time.')';
                // dd(trim($request->price));
                $traxdata['order_id'] = $ManualOrder->id;
                $traxdata['service_type_id'] = 1;
                $traxdata['pickup_address_id'] = $pickup_address_id;
                $traxdata['information_display'] = 0;
                $traxdata['consignee_city_id'] = $request->city;
                $traxdata['consignee_name'] = trim($request->receiver_name);
                $traxdata['consignee_address'] = trim($request->reciever_address);
                $traxdata['consignee_phone_number_1'] = trim($request->receiver_number);
                $traxdata['consignee_email_address'] = trim('orderstesting@brandhub.com');
                $traxdata['item_product_type_id'] = 1;
                $traxdata['item_description'] = trim($request->description);
                $traxdata['item_quantity'] = (int)trim($request->total_pieces);
                $traxdata['item_insurance'] = 0;
                $traxdata['item_price'] = (int)trim($request->price);
                $traxdata['parcel_value'] = (int)trim($request->price);
                $traxdata['pickup_date'] = $mytime;
                $traxdata['special_instructions'] = trim('Nothing');
                $traxdata['estimated_weight'] = trim($request->weight);
                $traxdata['shipping_mode_id'] = (int)trim($request->shipping_mode_id);
                $traxdata['amount'] = (int)trim($request->cod_amount);
                $traxdata['shipper_reference_number_1'] = $reference_number;
                $traxdata['payment_mode_id'] = 1;
                $traxdata['charges_mode_id'] = 4;
                
                $ApiResponse = $this->CreateBooking($traxdata);
                // dd($ApiResponse);
                // echo '1';
                if($ApiResponse->status == 0)
                { 
                    $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$order_id,'activity_desc'=>'Trax booking created','created_by'=>Auth::id(),'method'=>'create','route'=>route('ManualOrders.update',$order_id)]);

                    // echo '2';
                    $id = array();
                    array_push($id, $ApiResponse->tracking_number);
                    // dd($id);
                    $ManualOrder->date_order_paid = $request->date_order_paid;
                    $ManualOrder->reference_number = $request->reference_number;
                    $ManualOrder->consignment_id = $ApiResponse->tracking_number;
                    $ManualOrder->service_type = $request->shipping_mode_id; 
                    $ManualOrder->shipment_company = $request->shipment_type;
                    $ManualOrder->cities_id = $request->city;
                    $ManualOrder->status = 'dispatched';  
                    // echo '3';
                    if(check_customer_advance_payment($order_id) > 0)
                    {
                        dd('payment not approved');
                    }
                    // echo '4';
                    $status = $ManualOrder->save();
                    $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$order_id,'activity_desc'=>'Edit order data','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.update',$order_id)]);
                    $check_status = check_order_status_for_print($order_id); 
                    if( $check_status['row_count'] > 0)
                    {
                        // echo '5'; re
                        toastr()->error('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched','Error');
                        return back();
                        // dd();
                    }
                    
                    $slips = $this->print_trax_slips($id);
                    return view('client.orders.manual-orders.trax.print_trax_slip')->with('slips',$slips);
                }
                
                else
                {
                    toastr()->error('These shipments not created! Please contact Admin','Error');
                    return back(); 
                }
                
            }
            else if($request->shipment_type == 'leopord')
            {
                
                if(check_customer_advance_payment($ManualOrder->id) > 0)
                {
                    dd('payment not approved');
                } 
                $mytime = Carbon::now();
                $current_date_time = $mytime->toDateTimeString();
                // $pickup_address_id = $this->get_trax_pickup_address(); 
                $reference_number= '('.$ManualOrder->id.')('.$current_date_time.')'; 
                // dd('work');
                // dd($this->GetShippingCharges('KI752931139'));
                // dd($this->LeopordTrackBookedPacket('KI752931139'));
                // dd($this->GetShipperDetails());
                // $traxdata['order_id'] = $ManualOrder->id;
                // $traxdata['service_type_id'] = 1;
                // $traxdata['pickup_address_id'] = $pickup_address_id;
                // $traxdata['information_display'] = 0;
                // $traxdata['consignee_city_id'] = $request->city;
                // $traxdata['consignee_name'] = trim($request->receiver_name);
                // $traxdata['consignee_address'] = trim($request->reciever_address);
                // $traxdata['consignee_phone_number_1'] = trim($request->receiver_number);
                // $traxdata['consignee_email_address'] = trim('orderstesting@brandhub.com');
                // $traxdata['item_product_type_id'] = 1;
                // $traxdata['item_description'] = trim($request->description);
                // $traxdata['item_quantity'] = (int)trim($request->total_pieces);
                // $traxdata['item_insurance'] = 0;
                // $traxdata['item_price'] = (int)trim($request->price);
                // $traxdata['parcel_value'] = (int)trim($request->price);
                // $traxdata['pickup_date'] = $mytime;
                // $traxdata['special_instructions'] = trim('Nothing');
                // $traxdata['estimated_weight'] = trim($request->weight);
                // $traxdata['shipping_mode_id'] = (int)trim($request->shipping_mode_id);
                // $traxdata['amount'] = (int)trim($request->cod_amount);
                // $traxdata['shipper_reference_number_1'] = $reference_number;
                // $traxdata['payment_mode_id'] = 1;
                // $traxdata['charges_mode_id'] = 4;
                
                
                $leoporddata = json_encode(array(
                'api_key'                       => (env('LEOPORD_API_KEY')),
                'api_password'                  => (env('LEOPORD_API_PASSWORD')),
                'booked_packet_weight'          => (trim($request->weight)*1000),                 // Weight should in 'Grams' e.g. '2000'
                'booked_packet_vol_weight_w'    => $request->booked_packet_vol_weight_w,                 // Optional Field (You can keep it empty), Volumetric Weight Width
                'booked_packet_vol_weight_h'    => $request->booked_packet_vol_weight_h,                 // Optional Field (You can keep it empty), Volumetric Weight Height
                'booked_packet_vol_weight_l'    => $request->booked_packet_vol_weight_l,                 // Optional Field (You can keep it empty), Volumetric Weight Length
                'booked_packet_no_piece'        => (int)trim($request->total_pieces),                 // No. of Pieces should an Integer Value
                'booked_packet_collect_amount'  => (int)trim($request->cod_amount),                 // Collection Amount on Delivery
                'booked_packet_order_id'        => $ManualOrder->id,            // Optional Filed, (If any) Order ID of Given Product
                
                'origin_city'                   => env('LEOPORD_ORIGIN_CITY'),            /** Params: 'self' or 'integer_value' e.g. 'origin_city' => 'self' or 'origin_city' => 789 (where 789 is Lahore ID)
                                                                         * If 'self' is used then Your City ID will be used.
                                                                         * 'integer_value' provide integer value (for integer values read 'Get All Cities' api documentation)
                                                                         */
                
                'destination_city'              => $request->leopord_city,            /** Params: 'self' or 'integer_value' e.g. 'destination_city' => 'self' or 'destination_city' => 789 (where 789 is Lahore ID)
                                                                         * If 'self' is used then Your City ID will be used.
                                                                         * 'integer_value' provide integer value (for integer values read 'Get All Cities' api documentation) 
                                                                         */
                
                'shipment_id'                   => 1648479,
                'shipment_name_eng'             => 'self',            // Params: 'self' or 'Type any other Name here', If 'self' will used then Your Company's Name will be Used here
                'shipment_email'                => 'self',            // Params: 'self' or 'Type any other Email here', If 'self' will used then Your Company's Email will be Used here
                'shipment_phone'                => 'self',            // Params: 'self' or 'Type any other Phone Number here', If 'self' will used then Your Company's Phone Number will be Used here
                'shipment_address'              => 'self',            // Params: 'self' or 'Type any other Address here', If 'self' will used then Your Company's Address will be Used here
                
                
                'consignment_name_eng'          => trim($request->receiver_name),            // Type Consignee Name here
                'consignment_email'             => '',            // Optional Field (You can keep it empty), Type Consignee Email here
                'consignment_phone'             => trim($request->receiver_number),            // Type Consignee Phone Number here
                'consignment_phone_two'         => '',            // Optional Field (You can keep it empty), Type Consignee Second Phone Number here
                'consignment_phone_three'       => '',            // Optional Field (You can keep it empty), Type Consignee Third Phone Number here
                'consignment_address'           => trim($request->reciever_address),            // Type Consignee Address here
                'special_instructions'          => trim($request->description),            // Type any instruction here regarding booked packet
                'shipment_type'                 => $request->leopord_shipment_type_id,            // Optional Field (You can keep it empty so It will pick default value i.e. "overnight"), Type Shipment type name here
                'custom_data'                   => '',        // Optional Field (You can keep it empty), [{"key1":"value1","key2":value2,.....}]
                'return_address'                => '',            // Optional Field (You can keep it empty) - If 'return_address' is empty, then the address of shipper will be added as return address
                'return_city'                   => '',               // Optional Field (You can keep it empty) - If 'return_city' is empty, then shipper's origin city will ne return city
                'is_vpc'                        => 1,               
                ));
                // dd($this->LeopordGetShipperDetails());
                // echo "<pre>"; 
                // print_r($leoporddata);
                // die;
                $ApiResponse = $this->LeopordCreateBooking($leoporddata);
                // dd($ApiResponse);
                $order_id=$ManualOrder->id;
                
                // dd($ApiResponse);
                
                // echo '1';
                if($ApiResponse->status == 1)
                { 
                    $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$order_id,'activity_desc'=>'Trax booking created','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.update',$order_id)]);

                    // echo '2';
                    $id = array();
                    array_push($id, $ApiResponse->track_number);
                    // dd($id);
                    $ManualOrder->date_order_paid = $request->date_order_paid;
                    $ManualOrder->reference_number = '';
                    $ManualOrder->consignment_id = $ApiResponse->track_number; 
                    $ManualOrder->service_type = $request->leopord_shipment_type_id; 
                    $ManualOrder->shipment_company = $request->shipment_type;
                    $ManualOrder->shipment_slip = $ApiResponse->slip_link;
                    $ManualOrder->cities_id = $request->city;
                    $ManualOrder->status = 'dispatched';  
                    // echo '3';
                    
                    // echo '4';
                    $status = $ManualOrder->save();
                    $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$order_id,'activity_desc'=>'Edit order data','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.update',$order_id)]);
                    $check_status = check_order_status_for_print($order_id); 
                    if( $check_status['row_count'] > 0)
                    {
                        // echo '5'; re
                        toastr()->error('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched','Error');
                        return back();
                        // dd();
                    }
                    // return view('client.orders.manual-orders.leopord.print_slip')->with(['slip'=>$ApiResponse->slip_link]);
                    return redirect()->away($ApiResponse->slip_link);
                    // return view('client.orders.manual-orders.trax.print_trax_slip')->with('slips',$slips);
                }
                
                else
                {
                    toastr()->error('These shipments not created! Please contact Admin','Error');
                    return back(); 
                }
                
            }
            else if($request->shipment_type == 'local')
            {
                // dd($ManualOrder); 
                $ManualOrder->status = 'dispatched';
                if(check_customer_advance_payment($order_id) > 0)
                {
                    toastr()->error('payment not approved','Error');
                    return back(); 
                    // dd('payment not approved');
                }
                
                
                
                
                $ManualOrder->shipment_company = $request->shipment_type;
                $ManualOrder->status = 'dispatched';
                $status = $ManualOrder->save();
                // dd($status);
                $check_status = check_order_status_for_print($order_id); 
                if( $check_status['row_count'] > 0)
                {
                    toastr()->error('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched','Error');
                    return back(); 
                }
                $ManualOrder = ManualOrders::select('*')->where('manual_orders.id',$ManualOrder->id)->get();
                // foreach($ManualOrder as $ManualOrders)
                // {
                //     create_activity_log(['table_name'=>'manual_orders','ref_id'=>$ManualOrders->id,'activity_desc'=>'Local Slip','created_by'=>Auth::id(),'method'=>'print','route'=>route('ManualOrders.order.action')]);
        
                // }
                
                
                
                return view('client.orders.manual-orders.print_slip')->with('ManualOrders',$ManualOrder);
            }
        }
        

        // return redirect()->route('ManualOrders.index')->with('success', 'Order Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManualOrders $ManualOrder)
    { 
        $ManualOrder->status = 'deleted';
        $ManualOrder->save();
        return redirect()->route('ManualOrders.index')->with('success', 'Order Deleted');
        //
    }
    public function order_status($status, ManualOrders $ManualOrder)
    { 
        $ManualOrder->status = $status;
        $ManualOrder->save();
        return redirect()->route('ManualOrders.index')->with('success', 'Order '.$status);
        //
    }
    
    

    public function delete_order_image(Request $request)
    {  
        //File::delete($request->delete_path);
        // File::delete($request->delete_path)
        if(file_exists($request->delete_path))
        {
            if(File::delete($request->delete_path))
            {
                $manual_orders = ManualOrders::find($request->order_id); 
                $manual_orders->images = $request->images;
                $status =$manual_orders->save();
                if($status)
                {
                    return response()->json(['success'=>'1','messege' => 'successfully deleted']); 
                }
            }  
            else
            {
                return response()->json(['error'=>'1','messege' => 'Image not removed from server please check'.File::delete($request->delete_path)]); 
                // return 'Some thing went wrong';
            } 
        }
        else
        {
            $manual_orders = ManualOrders::find($request->order_id); 
            
            $manual_orders->images = $request->images;
             $status =$manual_orders->save();
                if($status)
                {
                    return response()->json(['success'=>'1','messege' => 'Order Image successfully deleted']); 
                }
                else
                {
                    return response()->json(['error'=>'1','messege' => 'Order Image not  deleted']); 
                }
        }   
    }
    
    public function order_action(Request $request)
    { 
        $order_action = $request->order_action;
        $order_ids = $request->order_ids;
       
        if($order_action == 'print')
        {
            $explode_id = explode(',', $order_ids); 
            
            $ManualOrder = ManualOrders::select('*')->whereIn('manual_orders.id',$explode_id)->get();
            if(check_customer_advance_payment($ManualOrders->id) > 0)
            {
                toastr()->error('payment not approved','Error');
                return back();
            }
            $check_status = check_order_status_for_print($ManualOrders->id); 
            if( $check_status['row_count'] > 0)
            { 
                toastr()->error('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched','Error');
                return back(); 
            }
            foreach($ManualOrder as $ManualOrders)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$ManualOrders->id,'activity_desc'=>'Local Slip','created_by'=>Auth::id(),'method'=>'print','route'=>route('ManualOrders.order.action')]);
    
            }
            return view('client.orders.manual-orders.print_slip')->with('ManualOrders',$ManualOrder);
                //dd($order_ids);
        }
        elseif($order_action == 'prepared')
        {
            $explode_id = explode(',', $order_ids);
            // dd($explode_id);  
            foreach($explode_id as $explode_ids)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$explode_ids,'activity_desc'=>'Order status updated to prepared','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.store')]);
    
            }
            $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'prepared']);
            //dd($ManualOrder);
        }
        elseif($order_action == 'cancel')
        {
            $explode_id = explode(',', $order_ids);
            // dd($explode_id);  
            foreach($explode_id as $explode_ids)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$explode_ids,'activity_desc'=>'Order status updated to cancel','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.order.action')]);
    
            }
            $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'cancel']);
            //dd($ManualOrder);
        }
        
        elseif($order_action == 'confirmed')
        {
            $explode_id = explode(',', $order_ids);
            //dd($explode_id);
            foreach($explode_id as $explode_ids)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$explode_ids,'activity_desc'=>'Order status updated to confirmed','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.store')]);
    
            }
            $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'confirmed']);
        }
        elseif($order_action == 'dispatched')
        {
            $explode_id = explode(',', $order_ids);
            //dd($explode_id);
            foreach($explode_id as $explode_ids)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$explode_ids,'activity_desc'=>'Order status updated to dispatched','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.store')]);
    
            }
            $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'dispatched']);
            return redirect()->route('ManualOrders.index')->with('success', 'Order dispatched succussfully ');
            //return Redirect::back()->with('success', 'Order dispatched succussfully ');

        }
        elseif($order_action == 'print_mnp_slips')
        {
            $explode_id = explode(',', $order_ids);
            //dd($explode_id);
            $ManualOrders = ManualOrders::whereIn('id',$explode_id)->update(['manual_orders.reference_number' => DB::raw("concat('(',`id`,')(',`updated_at`,')')")]);
            $ManualOrders = ManualOrders::whereIn('id',$explode_id)->get();
            foreach($ManualOrders as $ManualOrder)
            {
                if($ManualOrder->status == 'dispatched')
                {
                    if(Auth::guard('admin')->check())
                    {
                        
                    }
                    else
                    {
                        
                        toastr()->error('Order Id: '.$ManualOrder->id.' is already dispacthed only admin can print this slip');
                        return back();
                    }
                }
            }
            $cities = $this->get_mnp_cities();
            //$ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'dispatched']);
            return view('client.orders.manual-orders.mnp.create')->with(['ManualOrders'=>$ManualOrders, 'cities'=>$cities]);
            
            // $this->print_mnp_slips($ManualOrders);
            // dd($ManualOrder);
            
        }
        elseif($order_action == 'print_trax_slips')
        {
            $explode_id = explode(',', $order_ids);
            $this->UpdateReferenceNumberByOrderIds($explode_id);
            $ManualOrders = $this->GetOrdersByIds($explode_id);
            
            foreach($ManualOrders as $ManualOrder)
            {
                if($ManualOrder->status == 'dispatched')
                {
                    if(Auth::guard('admin')->check())
                    {
                        
                    }
                    else
                    {
                        
                        toastr()->error('Order Id: '.$ManualOrder->id.' is already dispacthed only admin can print this slip');
                        return back();
                    }
                }
            }
            $cities = $this->get_trax_cities();
            
            return view('client.orders.manual-orders.trax.create')->with(['ManualOrders'=>$ManualOrders, 'cities'=>$cities]);
            
            // $this->print_mnp_slips($ManualOrders);
            // dd($ManualOrder);
            
        }
        elseif($order_action == 'print_pos_slips')
        {
            
            $explode_id = explode(',', $order_ids); 
            $ManualOrders = Manualorders::whereIn('manual_orders.id',$explode_id)->get();
            // dd($ManualOrders->first()->id);
            $clc = $this->CheckCustomerLoyalityStatus($ManualOrders->first()->id);
            
            // dd($customer_loyality_status->first());
            // dd($customer_loyality_value);
            foreach($ManualOrders as $ManualOrder)
            {
                if($ManualOrder->status == 'dispatched')
                {
                    if(Auth::guard('admin')->check())
                    {
                        
                    }
                    else
                    {
                        
                        toastr()->error('Order Id: '.$ManualOrder->id.' is already dispacthed only admin can print this slip');
                        return back();
                    }
                }
            }
            foreach($ManualOrders as $ManualOrder)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$ManualOrder->id,'activity_desc'=>'pos slip','created_by'=>Auth::id(),'method'=>'print','route'=>route('ManualOrders.order.action')]);
    
            }
            
            return view('client.orders.manual-orders.print_pos_slips')->with(['ManualOrders'=>$ManualOrders,'clc'=>$clc]);
                //dd($order_ids);
        }
        
        elseif($order_action == 'duplicate_orders')
        {
            $explode_id = explode(',', $order_ids); 
            $ManualOrders = $this->GetOrdersByIds($explode_id);
            
            //dd($ManualOrders->first()->id);
            $ManualOrdersMaster = ManualOrders::find($ManualOrders->first()->id);
            $images = [];
            $price=0;
            $duplicate_ids=[];
            $ids_match = $ManualOrders->first()->receiver_number;
            foreach($ManualOrders as $ManualOrder)
            {
                if($ids_match != $ManualOrder->receiver_number)
                {
                    return  redirect()->route('ManualOrders.index')->with('errors', 'Reciever Number not Match, edit the reciever Number to merge!');
                }
                
                $duplicate_ids[] = $ManualOrder->id;
                $timages = (explode("|",$ManualOrder->images));
                foreach($timages as $image)
                {
                    $images[] = $image;
                }
                
                // $images .= '|'.$ManualOrder->images;
                $price = $price+(int)($ManualOrder->price);
                // $ManualOrder->
                // $man
            }
            $images = implode("|",$images);
            $success_msg = 'Order Merged Successfully Order IDs: '.implode(",",$duplicate_ids);
            array_shift($duplicate_ids);
            //dd($duplicate_ids);
            $ManualOrdersMaster->images = $images;
            $ManualOrdersMaster->price = $price; 
            $ManualOrdersMaster->status = 'pending';
            $ManualOrdersMaster->save();
            $ids_update = ManualOrders::whereIn('id',$duplicate_ids)->update(['status' => 'duplicate']);
        
            create_activity_log(['table_name'=>'manual_orders','ref_id'=>$ManualOrdersMaster->id,'activity_desc'=>'two orders merge from '.$ManualOrdersMaster->id.' to '.$duplicate_ids[0],'created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.order.action')]);
            
            return redirect()->route('ManualOrders.index')->with('success', $success_msg);
            dd($ManualOrdersMaster,$ids_update);
            dd($images,$price,implode(",",$duplicate_ids));
        }
        
        
        //dd($request->order_action);
    }
    
    public function previouse_order_history(Request $request)
    { 
        // dd($request->number);
        // dd(date('Y-m-d h:i:s', strtotime("-3 days")));
        if(Auth::guard('admin')->check())
        {
            
        }
        else
        {
            $findpreviouseorder = ManualOrders::select('created_at as date','id')
            ->where('receiver_number',$request->number)
            ->where( 'created_at', '>', (date('Y-m-d h:i:s', strtotime("-3 days"))))
            ->first();
            // dd($findpreviouseorder);
            // if(!$findpreviouseorder->isEmpty())
            
            if($findpreviouseorder != null)
            {
                // dd($findpreviouseorder->id);
                return response()->json([
                'error' => '2',
                'messege' => 'Order already placed at date:'.$findpreviouseorder->date.' and order ID: '.$findpreviouseorder->id, 
                ]);  
            } 
                
        }
           
        
        $query = ManualOrders::query();
        $number = $request->number;
        $order_add;
        // if($request->number[0] == '0')
        // {
        //     $number = substr_replace($request->number, '', 0, 1);
            // dd($number);
            $query = $query->where('manual_orders.receiver_number','like','%'.$number);
            $query = $query->where('manual_orders.status','!=','dispatched')->where('manual_orders.status','!=','confirmed')->where('manual_orders.status','!=','cancel')->where('manual_orders.status','!=','return');
            // where(function ($query) use ($number) {
            //     $query->Where('manual_orders.status','!=','dispatched')
            //         ->orWhere('manual_orders.status','!=','confirmed');
            // });
            // where(function ($query) use ($number) {
            //     $query
            //         ->Where('manual_orders.status','not like','%dispatched%')
            //         ->orWhere('manual_orders.status','not like','%confirmed%');
            // });
        // }
        
        $data='';
        $ManualOrders = $query->orderBy('manual_orders.id', 'DESC')->get();
        // $ManualOrders = ManualOrders::where('customers.number',$request->number)->get();
 
        
        if($ManualOrders->isEmpty())
        {
            return response()->json([
            'error' => '1',
            'messege' => 'no record Found', 
            ]); 
            // dd($ManualOrders);
        } 
        // dd($ManualOrders->first());
        $city = '';
        if($ManualOrders->first()->cities != null)
        {
            $city = $ManualOrders->first()->cities->id;
        }
        $data =  '
         <thead>
                <tr>
                  <th scope="col"></th>
                  <th scope="col">Images</th>
                  <th scope="col">order_id</th>
                  <th scope="col">First</th>
                  <th scope="col">number</th>
                  <th scope="col">date</th>
                  <th scope="col">Address</th>
                  <th scope="col">status</th>
                </tr>
                </thead>
                <tbody>';
                foreach($ManualOrders as $ManualOrder)
                {
                    // dd($ManualOrder);
                    
                    $onclick = "'".$ManualOrder->first_name."','".$ManualOrder->reciever_address."'";
                    $data .= '<tr><td>';
                    
                    
                    $images  = explode('|',$ManualOrder->images);
                    foreach($images as $image)
                    {
                        $data .= '<img class="previouse_order_images" src="'.asset($image).'"/ width="100">'; 
                    }
                    $data .= '</td>
                        <td><button class="btn btn-primary" onclick="fetch_data('.$ManualOrder->id.')">Fetch Data</button></td>
                        <td>'.$ManualOrder->id.'</td>
                        <td>'.$ManualOrder->receiver_name.'</td>
                        <td>'.$ManualOrder->receiver_number.'</td>
                        <td>'.$ManualOrder->created_at.'</td>
                        <td>'.$ManualOrder->reciever_address.'</td>
                        <td>'.$ManualOrder->status.'</td>';
                }
            $data .= '</tbody>';
        //dd($data);
        
        return response()->json([
            'messege' => $data,
            'field_values'=> $ManualOrders->first(),
            'address'=>$ManualOrders->first()->customers->address,
            'city'=>$city,
            ]); 
        // return view('client.orders.manual-orders.view')->with('ManualOrder',$ManualOrder);
    }
    
    public function status_order_list( Request $request)
    {
        //dd($request->status); 
        $status = $request->status;
        
        $list_order = 'ASC';
        if($status == 'pending'  )
        {
            $list_order = 'ASC';
        } 
        $query = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')
        ->leftJoin('users', 'manual_orders.created_by', '=', 'users.id') 
        ->leftJoin('users as t', 'manual_orders.updated_by', '=', 't.id')->where('manual_orders.status','like',$status.'%');
        $query = $query->orderBy('manual_orders.updated_at', $list_order)->select($this->OrderFieldList());
         
        $duplicate_check = DB::table('manual_orders')
        ->select('receiver_number', DB::raw('COUNT(*) as `count`'))
        ->where('manual_orders.status','like',$status.'%')
        ->groupBy('receiver_number')->havingRaw('COUNT(*) > 1')->get();
        
        // dd($query);
        
        $query = $query->paginate(20);
        $users = User::select('*')->get();
            //dd($list);
            //dd($list);  
            //$list = $list->all();
            //dd($list->all());
        $statuses = get_active_order_status_list();
        return view('client.orders.manual-orders.list')->with(['list'=>$query,'duplicate_check' => $duplicate_check,'users'=>$users,'statuses'=>$statuses]);
        
    } 
    
    public function dispatch_bulk_orders()
    {
        $riders = Riders::where('status','active')->get();
        //dd($riders);
        return view('client.orders.manual-orders.dispatch_bulk_orders')->with(['riders'=> $riders]);
    }
    
    public function get_order_details( $ManualOrder)
    { 
        
        if(check_customer_advance_payment($ManualOrder) > 0)
        {
            return response()->json(['error'=>'1','messege' => 'payment not approved order id #'.$ManualOrder]);
        } 
        
        $ManualOrder = ManualOrders::find($ManualOrder);
        if($ManualOrder != null)
        {
            return response()->json(['success'=>'1','messege' => $ManualOrder]);
        }
        else
        {
            return response()->json(['error'=>'1','messege' => 'No order found ! order #: '.$ManualOrder]);
        }
         
    }
    
    
    public function ChangeOrderStatus(Request $request)
    {
        $manualorder = ManualOrders::find($request->order_id);
        if($manualorder->status == 'dispatched')
        {
            if(Auth::guard('admin')->check())
            {
                $manualorder->status = $request->status;
                $update_status = $manualorder->save();
                $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$request->order_id,'activity_desc'=>'Status changed : '.$request->status,'created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.change.status')]);
                // dd($act_sta);
                if($update_status)
                {
                    return response()->json(['success'=>'1','messege' => 'Order Status changed to '.$request->status]);
                }
                else
                {
                    return response()->json(['error'=>'1','messege' => 'Cant change the order status please contact admin']);
                }
                // $action_status = ManualOrders::where('id',$order_id)->update(['status' => 'dispatched', 'riders_id'=> $request->riders]);
            }
            else
            {
                return response()->json(['error'=>'1','messege' => 'You dont have permission to change status']);
            }
        }
        else
        {
            $manualorder->status = $request->status;
            $update_status = $manualorder->save();
            $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$request->order_id,'activity_desc'=>'Status changed to: '.$request->status,'created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.change.status')]);
            // dd($act_sta);
            if($update_status)
            {
                return response()->json(['success'=>'1','messege' => 'Order Status changed to '.$request->status]);
            }
            else
            {
                return response()->json(['error'=>'1','messege' => 'Cant change the order status please contact admin']);
            }
        }
        
    }
    
    
    public function QuickEditOrder(ManualOrders $ManualOrder)
    { 
        // dd($ManualOrder->id);
        // //dd($ManualOrder); 
        // $ManualOrder = ManualOrders::where('manual_orders.id',$ManualOrder)->first();
        // //dd(ManualOrders::leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')); 
        // //dd($ManualOrder) ; 
        
        return response()->json(['success'=>'1','messege' => $ManualOrder]); 
        //
    }
    
    public function QuickEditOrderUpdate(Request $request, ManualOrders $ManualOrder)
    { 
        //dd($ManualOrder);
        $order_id = $ManualOrder->id;
        $ManualOrder->receiver_name = $request->QuickEdit_receiver_name;
        $ManualOrder->receiver_number = $request->QuickEdit_receiver_number;
        //dd($request->receiver_number);
        $ManualOrder->reciever_address = $request->QuickEdit_reciever_address;  
        $ManualOrder->price = $request->QuickEdit_price;
        $ManualOrder->cod_amount = $request->QuickEdit_cod_amount;
        $ManualOrder->advance_payment = $request->QuickEdit_advance_payment;
        $ManualOrder->status = $request->QuickEdit_status;
        $ManualOrder->updated_by = Auth::id();
        $ManualOrder->assign_to = $request->QuickEdit_assign_to;
        $ManualOrder->status_reason = $request->QuickEdit_status_reason;
        
        $status = $ManualOrder->save();
        
        if($status)
        {
            $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$order_id,'activity_desc'=>'Quick Edit order data','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.update.quick.edit.order',$order_id)]);

            
            return response()->json(['success'=>'1','messege' => 'Record Successfully updated']);
        }
        else
        {
            
            return response()->json(['error'=>'1','messege' => 'Record not updated Please Contact Admin']);
        }
    }
    
    public function print_order_slip($ManualOrder_id)
    { 
        $ManualOrder = ManualOrders::select('*')->where('manual_orders.id',$ManualOrder_id)->get();
        //dd($ManualOrder);
        if(check_customer_advance_payment($ManualOrder_id) > 0)
        {
            toastr()->error('payment not approved','Error');
            return back(); 
            // dd('payment not approved'); 
        }
        $check_status = check_order_status_for_print($ManualOrder_id); 
        // dd($check_status);
        if( $check_status['row_count'] > 0)
        {
            dd('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched');
        }
        $act_sta = create_activity_log(['table_name'=>'manual_orders','ref_id'=>$ManualOrder_id,'activity_desc'=>'Print Local Slip : ','created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.print.order.slip',$ManualOrder_id)]);

        return view('client.orders.manual-orders.print_slip')->with('ManualOrders',$ManualOrder);
    }
    
    public function get_trax_pickup_address()
    {
        $response = $this->GetPickupAddresses();
        if($response->status == 0)
        {
            return($response->pickup_addresses[0]->id);
        }
        // $baseUrl = "https://sonic.pk/"; 
        // $apiUrl = $baseUrl."api/pickup_addresses";
        // $headers = ['Authorization:'.env('TRAX_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        // $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, $apiUrl);
        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        // $result = curl_exec($ch);
        
        dd($response); 
    }
    
    
    public function trax_create_booking_store(Request $request)
    {
        
        //dd();
        
        $pickup_address_id = $this->get_trax_pickup_address();
        
        //dd($request->item_product_type_id);
        $mytime = Carbon::now();
        $current_date_time = $mytime->toDateTimeString();
        $id = array();

        for ($x = 0; $x < sizeof($request->reference_number); $x++) 
        {
            //prepare parameter for create booking
            //dd($request->shipping_mode_id);
            $data = [];
            
            $receiver_name= $request->receiver_name[$x];
            $receiver_number= $request->receiver_number[$x];
            $reciever_address= $request->reciever_address[$x];
            $city = $request->city[$x];
            $total_pieces= $request->total_pieces[$x];
            $weight= $request->weight[$x];
            $price= $request->price[$x];
            $fare = $request->fare[$x];
            $data['order_id'] = $request->id[$x];
            $data['service_type_id'] = 1;
            $data['pickup_address_id'] = $pickup_address_id;
            $data['information_display'] = 0;
            $data['consignee_city_id'] = $city;
            $data['consignee_name'] = trim($receiver_name);
            $data['consignee_address'] = trim($reciever_address);
            $data['consignee_phone_number_1'] = trim($receiver_number);
            $data['consignee_email_address'] = trim('orderstesting@brandhub.com');
            $data['item_product_type_id'] = 1;
            $data['item_description'] = trim($request->item_description[$x]);
            $data['item_quantity'] = (int)trim($total_pieces);
            $data['item_insurance'] = 0;
            $data['item_price'] = trim($request->price[$x]);
            $data['pickup_date'] = $mytime;
            $data['special_instructions'] = trim('Nothing');
            $data['estimated_weight'] = trim($request->weight[$x]);
            $data['shipping_mode_id'] = (int)trim($request->shipping_mode_id[$x]);
            $data['amount'] = (int)trim($request->price[$x]);
            $data['payment_mode_id'] = 1;
            $data['charges_mode_id'] = 4;
            
            //update manualorders
            $ManualOrder = ManualOrders::find($request->id[$x]);
            $reference_number= '('.$ManualOrder->id.')('.$current_date_time.')';
            $data['$shipper_reference_number_1'] = $reference_number;
            $ManualOrder->receiver_name = $receiver_name;
            $ManualOrder->receiver_number = $receiver_number;
            $ManualOrder->cities_id = $city;
            $ManualOrder->reciever_address = $reciever_address;
            $ManualOrder->total_pieces = $total_pieces;
            $ManualOrder->weight = $weight;
            $ManualOrder->price = $price;
            $ManualOrder->price = $fare;
            $ManualOrder->status = 'dispatched';
            $ManualOrder->description = trim($request->item_description[$x]);
            $ManualOrder->reference_number = $reference_number;
            $ManualOrder->updated_by = Auth::id();
            $status = $ManualOrder->save();
            
            
            
        
        //echo 'working';
            if($status);
            {
                $ApiResponse = $this->CreateBooking($data);
                //dd($ApiResponse);
                
                //DD(env('MNP_API_USERNAME')); 
                if($ApiResponse->status == 0)
                {
                    // echo '<pre>';
                    // print_r(json_decode($resp)[0]);
                    // echo $price;
                    $ManualOrder = ManualOrders::find($request->id[$x]);
                    
                    if(check_customer_advance_payment($request->id[$x]) > 0)
                    {
                        dd('payment not approved',$request->id[$x]);
                    }
                    //dd($ManualOrder);
                    $ManualOrder->consignment_id = $ApiResponse->tracking_number;
                    
                    $check_status = check_order_status_for_print($ManualOrder_id); 
                    if( $check_status['row_count'] > 0)
                    {
                        dd('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched');
                    }
                    $status = $ManualOrder->save();
                    
                    if($status)
                    {   
                        //echo $ApiResponse->tracking_number;
                        array_push($id, $ApiResponse->tracking_number);
                        //return $this->print_trax_slips($id);
                    }
                    else
                    {
                        //dd($status);
                        //dd($ManualOrder);
                    }
                    //dd(json_decode($resp)[0]->orderReferenceId);
                }
                
            }
            
        }
        $slips = $this->print_trax_slips($id);
        return view('client.orders.manual-orders.trax.print_trax_slip')->with('slips',$slips);
        
        
        //dd();
    }
    
    public function get_trax_cities()
    {
        return $this->LeopordGetCities()->city_list;
        // $baseUrl = "https://sonic.pk/";
 
        // $apiUrl = $baseUrl."api/cities";

        // $headers = ['Authorization:'.env('TRAX_API_KEY'), 'Accepts:' . 'application/json'];
        // $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, $apiUrl);
        // curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        // $result = curl_exec($ch);
        
        // return $result;
    }
    
    public function print_trax_slips($ids)
    {
        $data=array();
        foreach($ids as $id)
        {
            $src = $this->PrintAirWayBill($id,'0');
            array_push($data, $src);
            
        }
        return $data;
        
        
    }
    
    public function print_slip_by_scan()
    { 
       
        
        return view('client.orders.manual-orders.print_slip_by_scan');
        
    }
    
    public function get_product_details($Sku)
    {
        $product = Inventory::select('products.id','inventories.id as inventory_id','products.sku', 'products.name', 'inventories.sale')->where('products.Sku', '=', $Sku)->join('products', 'products.id', '=', 'inventories.products_id')->firstOrFail();
        //dd($product->id);
        if($product != null)
        {
            return response()->json(['messege' => $product]);
        }
        else
        {
            return response()->json(['messege' => 'no order found']);
        }
    }
    
    public function print_slip_by_scan_store(Request $request)
    { 
        $count_products = count($request->product_ids);
        $products_insert_data=[];
        
        // $products = Products::create([
        //     'sku' => $request->sku,
        //     'name' => $request->name,
        //     'created_by' => '1',
        //     'updated_by' => '1',
        //     'status' => 'active' 
        //     ]);
            
        for($i=0; $i<$count_products; $i++)
        {
            $d= Order_details::create([
                'inventory_id' => $request->inventory_ids[$i],
                'sku' => $request->product_skus[$i],
                'order_id' => $request->order_id,
                'discount' => $request->discounts[$i],
                'quanity' => $request->qty[$i],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(), 
                'status' => 'active', 
                
                ]);
                
                $inventory = Inventory::find($d->inventory_id);
                $inventory->onhand = $inventory->onhand-$request->qty[$i];
                $status = $inventory->save();
                    //dd($status);
                //array_push($products_insert_data,$d);
        } 
        dd($products_insert_data); 
        
    }
    
    public function QuickSearch()
    {
        $statuses = get_active_order_status_list();
        return view('client.orders.manual-orders.quick_order_search')->with(['statuses'=>$statuses]);
    }
    
    public function QuickSearchActions(Request $request)
    {
        // dd($request->order_ids);
        $order_status = $request->order_status;
        $print_slips = $request->print_slips;
        $order_ids = $request->order_ids;
    //   dd($print_slips);
        if($order_status != null )
        {
            //  dd($order_ids);
            // $explode_id = explode(',', $order_ids);
            // dd($explode_id);
            $action_status = ManualOrders::whereIn('id',$order_ids)->update(['status' => $order_status]);
            foreach($order_ids as $order_id)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$order_id,'activity_desc'=>'Order status updated to '.$order_status,'created_by'=>Auth::id(),'method'=>'update','route'=>route('manualOrders.quick.search.actions')]);
    
            }
            
            //dd($ManualOrder);
        }
        
        if($print_slips == 'domestic')
        {
            foreach($order_ids as $order_id)
            {
                if(check_customer_advance_payment($order_id) > 0)
                {
                    dd('payment not approved',$order_id);
                }
                
                $check_status = check_order_status_for_print($order_id); 
                if( $check_status['row_count'] > 0)
                {
                    dd('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched');
                }
            }
            $ManualOrder = ManualOrders::select('*')->whereIn('manual_orders.id',$order_ids)->get();
            $ids = array();
            foreach($ManualOrder as $consignment_ids)
            {
                array_push($ids, $consignment_ids->consignment_id); 
            }
            
            // dd($ids);
            $slips = $this->print_trax_slips($ids);
            // dd($slips);
            return view('client.orders.manual-orders.trax.print_trax_slip')->with('slips',$slips);
            
        }
        elseif($print_slips == 'local')
        {
              
            $ManualOrder = ManualOrders::select('*')->whereIn('manual_orders.id',$order_ids)->get();
            foreach($order_ids as $order_id)
            {
                if(check_customer_advance_payment($order_id) > 0)
                {
                    dd('payment not approved',$order_id);
                }
                $check_status = check_order_status_for_print($order_id); 
                if( $check_status['row_count'] > 0)
                {
                    dd('Parcel Status is '.$check_status['status'].' and parcel cannot print slip until parcel status is confirmed OR Dispatched');
                }
            }
            
            return view('client.orders.manual-orders.print_slip')->with('ManualOrders',$ManualOrder);
        }
        elseif($print_slips == 'pos')
        {
        
            $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->whereIn('manual_orders.id',$order_ids)->get();
            return view('client.orders.manual-orders.print_pos_slips')->with('ManualOrders',$ManualOrder);
        }
        dd($print_slips);
        // $action_status = ManualOrders::whereIn('id',$request->order_ids)->update(['status' => 'dispatched', 'riders_id'=> $request->riders]);
        // if($action_status)
        // {
        //     return response()->json(['status' => '1', 'messege' => $action_status.'Succussfully Done']); 
        // }
        // else
        // {
        //     return response()->json(['status' => '0', 'messege' => 'some thing went wrong']); 
        // }
    }
    
    
    
    public function PrintedSlipsReport(Request $request)
    {
        $order_id = $request->search_order_id;
        $search_text = $request->search_text;
        $order_status = $request->order_status;
        $order_by = $request->order_by;
        $date_from =  $request->date_from;
        $date_to =  $request->date_to;
        $query = ActivityLogs::query();
        $query = $query->where('table_name' ,'=','manual_orders')->where('method','=','print');
        // dd($date_from,$date_to);
        // dd($query->toSql());
        if($date_from != '' && $date_to != '')
        {
            if($date_from == $date_to)
            {
                
                $query = $query->where("activity_logs.created_at" ,'like',$date_from.'%');
            }
            else
            {
                $query = $query->whereBetween("activity_logs.created_at" ,[$date_from,$date_to]);
            }
            
        }
        else if($date_to != '')
        {
            $query = $query->where("activity_logs.created_at" ,'like',$date_to.'%');
        }
        else if($date_from != '')
        {
            $query = $query->where("activity_logs.created_at" ,'like',$date_from.'%');
        }
        // dd($query->toSql());
        $query = $query->leftJoin('manual_orders', 'manual_orders.id', '=', 'activity_logs.ref_id')->select($this->OrderFieldList());
        $query = $query->leftJoin('customers', 'manual_orders.customers_id', '=', 'customers.id')->select($this->OrderFieldList());
        
        if($order_id != '')
        {
            $query = $query->where('manual_orders.id',$order_id);
        }
        else if($search_text != '')
        {
            $query = $query->
            where(function ($query) use ($search_text) {
                $query->where('customers.first_name','like',$search_text.'%')
                    ->orWhere('customers.first_name','like','%'.$search_text.'%')
                    ->orWhere('customers.first_name','like','%'.$search_text)
                    ->orWhere('customers.last_name','like',$search_text.'%')
                    ->orWhere('customers.last_name','like','%'.$search_text.'%')
                    ->orWhere('customers.last_name','like','%'.$search_text)
                    ->orWhere('customers.number','like','%'.$search_text) 
                    ->orWhere('customers.number','like',$search_text.'%')
                    ->orWhere('customers.number','like','%'.$search_text.'%')
                    ->orWhere('manual_orders.id','like','%'.$search_text.'%')
                    ->orWhere('manual_orders.consignment_id','like','%'.$search_text.'%');
            })->where('manual_orders.status','like',$order_status.'%');
            
        }
        else if($order_status != '')
        {
            if($order_status != 'all')
            {
                $query = $query->where('manual_orders.status',$order_status);
            }  
        }
        else
        {
            $query = $query->
            where(function ($query) use ($search_text) {
                $query->where('manual_orders.status','pending')
                ->orwhere('manual_orders.status','addition');
            });
            // $query = $query->where('manual_orders.status','pending');
        }
        
        // if($date_from != '' && $date_to != '')
        // {
        //     $query = $query->whereBetween("manual_orders.created_at" ,[$date_from,$date_to]);
            
        // }
        
        if($order_by != '')
        {
            $query = $query->orderBy($order_by, 'ASC');
        }
        else
        {
            $query = $query->orderBy('manual_orders.id', 'DESC');
        }
        
        
        $list = $query->paginate(20);
        return view('client.orders.manual-orders.reports.printed_slips')->with('list',$list); 
    } 
    
    public function PrintPosSlip($ManualOrder)
    {
        // dd($ManualOrder);
        // $order_id = $id; 
        $ManualOrder = Manualorders::where('manual_orders.id',$ManualOrder)->get();
        $ManualOrders = $ManualOrder->first();
        
        $clc = $this->CheckCustomerLoyalityStatus($ManualOrders->id);
        if($ManualOrders->status == 'dispatched' || $ManualOrders->status == 'confirmed')
        {
            toastr()->error('Parcel Status is '.$ManualOrders->status.' and cannot print pos slip cause parcel status is Dispatched','Error');
            return back();
        }
        if($ManualOrders->status == 'pending' || $ManualOrders->status == 'addition')
        {
            $ManualOrders->status = 'prepared';
            $ManualOrders->save();
            create_activity_log(['table_name'=>'manual_orders','ref_id'=>$ManualOrders->id,'activity_desc'=>'Status prepared from pending to prepared','created_by'=>Auth::id(),'method'=>'print','route'=>route('ManualOrders.print.pos.slip',$ManualOrders->id)]);
        }
        create_activity_log(['table_name'=>'manual_orders','ref_id'=>$ManualOrders->id,'activity_desc'=>'pos slip','created_by'=>Auth::id(),'method'=>'print','route'=>route('ManualOrders.order.action')]);

    // dd($ManualOrder);
        return view('client.orders.manual-orders.print_pos_slips')->with(['ManualOrders'=>$ManualOrder,'clc'=>$clc]);
    }
    
    public function CheckPosSlipDuplication(Manualorders $ManualOrder)
    {
        // dd('working');
        $from_date = Carbon::now()->subDays(7)->toDateTimeString(); 
        // dd($from_date);
        // $users = User::where('created_at','>=',$date)->get();
        $manual_orders = Manualorders::select('*')->where('receiver_number','=',$ManualOrder->receiver_number)->where('created_at','>=',$from_date)->get();
        // dd($query);
        $activitylog = ActivityLogs::select('*')->where('ref_id','=',$ManualOrder)->where('table_name','=','manual_orders')->where('activity_desc','=','pos slip')->get();
        // dd($activitylog);
        return response()->json(['status' => '1', 'messege' => 'success','activitylog'=>$activitylog,'manualorders'=>$manual_orders]);
    }
    
    public function GetAdvacePayment(Request $request)
    {
        
        $advance_payment = Manualorders::find($request->order_id)->advance_payment; 
        return response()->json(['advance_payment' => $advance_payment]);
        // dd($advance_payment);
    }
    
    
    
    
    public function testing()
    {
        $cities_arr= [];
        
        $cities = $this->get_trax_cities();
        // dd($cities[0]);
        foreach($cities as $city)
        {
            $cities_arr[] = ['id'=>$city->id,'name'=>$city->name];
        }
        Cities::insert($cities_arr);
        dd($cities_arr);
        $data= '20222319352805'; 
        // $urlreturn response()->json(['status' => '1', 'messege' => $action_status.'Succussfully Done']);= "https://sonic.pk/api/shipment/payments?tracking_number=20217219406041";
        // $headers = ['Authorization:'.env('TRAX_API_KEY'), 'Accepts:' . 'application/json',"real:json content"];
        // $response = $this->CurlGetRequest($apiUrl,$headers);
        
        
        dd($this->GetShipmentPaymentStatus($data));
        
        // $url = "https://api.nexmo.com/beta/messages";

        // $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        // $headers = array(
        //   "Content-Type: application/json",
        //   "Authorization: Bearer 12555f46-e716-4b31-96cb-f3600f41874e"
        // );
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        // $data = '{"from": "923330139993","to": "923412199313","channel": "whatsapp","whatsapp": {"policy": "deterministic","locale": "en-GB"}, "message_type": "template","template":{"name":"whatsapp:hsm:technology:nexmo:verify","parameters":["Vonage Verification","c3142209", "10"]} }';
        
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        // //for debug only!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        // $resp = curl_exec($curl);
        // curl_close($curl);
        // var_dump($resp);
        
        
        
//         curl -X POST \
//   https://api.nexmo.com/beta/messages \
//   -H 'Authorization: Bearer' $JWT \
//   -H 'Content-Type: application/json' \
//   -d '{
//   "from": "WHATSAPP_NUMBER",
//   "to": "TO_NUMBER",
//   "channel": "whatsapp",
//   "whatsapp": {
//      "policy": "deterministic",
//      "locale": "en-GB"
//   }
//   "message_type": "template",
//   "template":{
//       "name":"whatsapp:hsm:technology:nexmo:verify",
//       "parameters":[
//          "Vonage Verification",
//          "64873",
//          "10"
//       ]
//   }
// }'
        
    //     $url = "https://messages-sandbox.nexmo.com/v0.1/messages";
    // $params = ["to" => ["type" => "whatsapp", "number" => '923330139993'],
    //     "from" => ["type" => "whatsapp", "number" => "923330139993"],
    //     "message" => [
    //         "content" => [
    //             "type" => "text",
    //             "text" => "Hello from Vonage and Laravel :) Please reply to this message with a number between 1 and 100"
    //         ]
    //     ]
    // ];
    // $headers = ["Authorization" => "Basic " . base64_encode(env('NEXMO_API_KEY') . ":" . env('NEXMO_API_SECRET'))];

    // $client = new \GuzzleHttp\Client();
    // $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
    // $data = $response->getBody();
    // dd($data);

    // return view('thanks');
        $ManualOrder = ManualOrders::find(1234);
        //dd($ManualOrder);
        if($ManualOrder != null)
        {
            dd($ManualOrder);
            return response()->json(['messege' => $ManualOrder]);
        }
        else
        {
            return response()->json(['messege' => 'no order found']);
        }
    
        
    
        $url = "http://mnpcourier.com/mycodapi/api/Booking/InsertBookingData";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array(
          "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        $data = '{"username": "mansoor_4b459","password": "Mansoor1@3","consigneeName": "test","consigneeAddress": "test123","consigneeMobNo": "03330139993","consigneeEmail": "string","destinationCityName": "karachi","pieces": "2","weight": "1","codAmount": 1,"custRefNo": "12345689","productDetails": "string","fragile": "string","service": "overnight","remarks": "string","insuranceValue": "string","locationID": "string","AccountNo": "string","InsertType": "0"}';
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        $resp = curl_exec($curl);
        curl_close($curl);
        
        dd($resp);
    }
    
    public function UpdateAssignTo(ManualOrders $id, $assig_to)
    {
        // dd($id);
        $ManualOrder = $id->update(['assign_to' => $assig_to]);
        // dd($ManualOrder);
        if($ManualOrder)
        { 
            return response()->json(['success' => '1', 'messege' => 'Order Assign to User ID:'.$assig_to]);
            // return response()->json(['messege' => 'no order found']);
        }
        else
        {
            
            return response()->json(['error' => '1', 'messege' => 'order not updated please contact admin'.$ManualOrder]);
        }
    }
    
    public function GetShipmentCities($shipmentcompany)
    {
        if($shipmentcompany == 'trax')
        {
            $cities = $this->LeopordGetCities()->city_list;
        }
        else if($shipmentcompany == 'leopord')
        {
            $cities = $this->LeopordGetCities()->city_list;
        }
        
        
         
        return response()->json(['success' => '1', 'cities' => $cities]);
        
    }
        
    public function CheckCustomerLoyalityStatus($explode_id)
    {
        $customer_loyality_value='';
        $customer_loyality_status = Manualorders::select(
            DB::raw("(select count(*) from manual_orders where customers.id = manual_orders.customers_id and status = 'return') as return_count"), 
            DB::raw("(select count(*) from manual_orders where customers.id = manual_orders.customers_id and status = 'dispatched') as dispatched_count")
        )->leftJoin('customers', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.id',$explode_id)->get();
            // dd($customer_loyality_status);
        $do = (int)$customer_loyality_status->first()->dispatched_count;
        $ro = (int)$customer_loyality_status->first()->return_count;
        // $to = 1;
        // $ro = 2;
        $per=0;
        if($ro > 0 && $do >0)
        {
            $per = ($ro/($do))*100;
        }
        else
        {
            $per =0;
        } 
        // echo $per;
        // dd($ro,$do);
        // echo 'DO: '.$do.' RO: '.$ro; dd($per);
        if(($per) > 5)
        {
            $customer_loyality_value = 'Black List';
        }
        else
        {
            // customer_loyality_value = 'Lo';
        } 
        $data = array([
            'customer_status'=>$customer_loyality_value,
            'do'=>$do,
            'ro'=>$ro
            ]);
        return $data;
    }
    
    
        
    public function TestFileUpload(Request $request)
    {
        dd('working');
        $image = $request->file('file');
        dd($image);
        $imageName = time().'.'.$image->extension();
        $image->move(public_path('images'),$imageName);
        return response()->json(['success'=>$imageName]);
    }
    
    
 
}
