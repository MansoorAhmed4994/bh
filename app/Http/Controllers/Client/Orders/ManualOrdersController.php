<?php

namespace App\Http\Controllers\Client\Orders;

use App\Http\Controllers\Controller;

use App\Traits\ManualOrderTraits;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Riders;
use App\Models\Client\Customers;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ManualOrdersController extends Controller 
{ 

    private $images_path =  'storage/images/orders/manual-orders/';
    /**
     * Display a listing of the resource.
     
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        //dd();
        //$this->middleware('auth');
    }

    use ManualOrderTraits;
    
    public function index()
    {
        // $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')->orderBy('manual_orders.created_at', 'DESC')->paginate(5);
        $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')
        ->where('manual_orders.status','pending')
        ->orderBy('manual_orders.id', 'ASC')
        ->select('manual_orders.id','manual_orders.customers_id','customers.first_name','manual_orders.receiver_number','manual_orders.description','manual_orders.reciever_address','customers.last_name','customers.number','customers.address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at')
        ->paginate(20);
        //dd($list);
        return view('client.orders.manual-orders.list')->with('list',$list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('client.orders.manual-orders.create');
        //
    }
    
    public function search_order( Request $request)
    {
        $search_test = $request->search_text;
        //$list = ManualOders::where('first_name','like',$request->search_text.'%')->orwhere('number','like',$request->search_text.'%')->where('status','pending')->orderBy('created_at', 'DESC')->paginate(5);
        // $list = ManualOders::where(function ($query) use ($search_test) {
        //     $query->where('first_name','like',$search_test.'%')
        //           ->orWhere('number','like',$search_test.'%');
        //     })->where('status','pending')->orderBy('created_at', 'DESC')->paginate(5);

        $order_status = $request->order_status;
        // if($request->order_status == 'all')
        // {
        //     $order_status='';
        // }
        // else
        // {
            
        // }
        //dd($order_status);
        $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->
        where(function ($query) use ($search_test) {
            $query->where('customers.first_name','like',$search_test.'%')
                    ->orWhere('customers.first_name','like','%'.$search_test.'%')
                    ->orWhere('customers.last_name','like',$search_test.'%')
                    ->orWhere('customers.last_name','like','%'.$search_test.'%')
                    ->orWhere('customers.number','like','%'.$search_test) 
                    ->orWhere('customers.number','like',$search_test.'%');
            })->where('manual_orders.status','like',$order_status.'%')
            ->orderBy('manual_orders.id', 'ASC')
            ->select('manual_orders.id','manual_orders.customers_id','manual_orders.description','manual_orders.receiver_number','customers.first_name','manual_orders.reciever_address','customers.last_name','customers.number','customers.address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at')
            ->paginate(20);
//dd($list);
        // $list = $list->all();
        // dd($list->all());
        return view('client.orders.manual-orders.list')->with('list',$list);
        //
    
        
        
    }
    
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        // $ifexist = ManualOrders::select('id')->where('receiver_number',$request->number)->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        // if($ifexist->isEmpty())
        // {
        // //dd($ifexist);
            
        //     $validated = $request->validate([
    
        //         'images' => 'required',
        //         'first_name' => 'required',
        //         'number' => 'required',
        //         'address' => 'required',
    
        //     ]); 
             
            
            
            
        //     $images=array();
        //     if($files=$request->file('images')){
        //         foreach($files as $file){
        //             $name=$file->getClientOriginalName();
                    
        //             $file->move($this->images_path,$name);
        //             $images[]=$this->images_path.$name;
        //         }
        //     }
    
        //   // dd();
        //     $customers = new Customers();
        //     $customers->first_name = $request->first_name;
        //     $customers->last_name = $request->last_name;
        //     $customers->address = $request->address;
        //     $customers->number = $request->number;
        //     $customers->whatsapp_number = $request->number;
        //     $customers->created_by = Auth::id();
        //     $customers->updated_by = Auth::id();
        //     $customers->status = 'active'; 
        //     $customers->save();
        //     ///$customers = $customers->save();
        //     // dd($customers->id);
            
            
    
        //     $manual_orders = new ManualOrders();
        //     //$manual_orders->customer_id = $customers->id;
        //     $manual_orders->receiver_name = $request->first_name;
        //     $manual_orders->receiver_number = $request->number;
        //     $manual_orders->city = '';
        //     $manual_orders->reciever_address = $request->address;
        //     $manual_orders->images = implode("|",$images);
        //     $manual_orders->total_pieces = '';
        //     $manual_orders->weight = '';
        //     $manual_orders->price = '';
        //     $manual_orders->cod_amount = '';
        //     $manual_orders->advance_payment = '';
        //     $manual_orders->date_order_paid = '';
        //     $manual_orders->description = $request->description;
        //     $manual_orders->reference_number = '';
        //     $manual_orders->service_type = '';
        //     $manual_orders->created_by = Auth::id();
        //     $manual_orders->updated_by = Auth::id();
        //     $manual_orders->status = 'pending';
        //     //$manual_orders = $manual_orders->save();
            
            
            //$post->comments()->save($manual_orders);
        //     if($this->CreateOrder($request))
        //     {
        //         return redirect()->route('ManualOrders.create')->with('success', 'Order Successfully placed');
        //     }
        // }
        // else
        // {
        //     dd('duplicate order please check');
        // }
        $status = $this->CreateOrder($request);
        return redirect()->route('ManualOrders.create')->with('success', $status);
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
    public function edit($ManualOrder)
    {
        
        //dd($ManualOrder);
        $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.id',$ManualOrder)->first();
        //dd(ManualOrders::leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')); 
       // dd($ManualOrder) ;
        return view('client.orders.manual-orders.edit')->with('ManualOrder',$ManualOrder);
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
        
        //$manual_orders = new ManualOders();
        $ManualOrder->receiver_name = $request->receiver_name;
        $ManualOrder->receiver_number = $request->receiver_number;
        $ManualOrder->city = $request->city;
        $ManualOrder->reciever_address = $request->reciever_address;  
        if($images != null)
        {
            $ManualOrder->images = $ManualOrder->images.'|'.(implode("|",$images));
        }      
        $ManualOrder->total_pieces = $request->total_pieces;
        $ManualOrder->weight = $request->weight;
        $ManualOrder->price = $request->price;
        $ManualOrder->cod_amount = $request->cod_amount;
        $ManualOrder->advance_payment = $request->advance_payment;
        $ManualOrder->date_order_paid = $request->date_order_paid;
        $ManualOrder->description = $request->description;
        $ManualOrder->reference_number = $request->reference_number;
        $ManualOrder->service_type = $request->service_type;
        $ManualOrder->updated_by = Auth::id();
        $ManualOrder->save();
        
        //dd($ManualOrder);c

        return redirect()->route('ManualOrders.index')->with('success', 'Order Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManualOrders $ManualOrder)
    { 
        //dd($ManualOrder);
        $ManualOrder->status = 'deleted';
        $ManualOrder->save();
        //dd($manual_orders);
        return redirect()->route('ManualOrders.index')->with('success', 'Order Deleted');
        //
    }
    public function order_status($status, ManualOrders $ManualOrder)
    { 
        //dd($ManualOrder);
        $ManualOrder->status = $status;
        $ManualOrder->save();
        //dd($manual_orders);
        return redirect()->route('ManualOrders.index')->with('success', 'Order '.$status);
        //
    }
    
    

    public function delete_order_image(Request $request)
    {  
        //File::delete($request->delete_path);
        //dd(file_exists($request->delete_path));
        // File::delete($request->delete_path)
        if(file_exists($request->delete_path))
        {
            if(File::delete($request->delete_path))
            {
                $manual_orders = ManualOrders::find($request->order_id); 
                //dd($manual_orders);
                $manual_orders->images = $request->images;
                $status =$manual_orders->save();
                if($status)
                {
                    return response()->json(['messege' => 'successfully deleted']); 
                }
            }  
            else
            {
                return 'Some thing went wrong';
            } 
        }
        else
        {
            // dd('working');
            $manual_orders = ManualOrders::find($request->order_id); 
            
            $manual_orders->images = $request->images;
            //dd($manual_orders->save());
             $status =$manual_orders->save();
                if($status)
                {
                    return response()->json(['messege' => 'successfully deleted']); 
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
            $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->whereIn('manual_orders.id',$explode_id)->get();
            //dd($ManualOrder);
            return view('client.orders.manual-orders.print_slip')->with('ManualOrders',$ManualOrder);
                //dd($order_ids);
        }
        elseif($order_action == 'prepared')
        {
            $explode_id = explode(',', $order_ids);
            //dd($explode_id);
            $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'prepared']);
            //dd($ManualOrder);
        }
        elseif($order_action == 'confirmed')
        {
            $explode_id = explode(',', $order_ids);
            //dd($explode_id);
            $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'confirmed']);
        }
        elseif($order_action == 'dispatched')
        {
            $explode_id = explode(',', $order_ids);
            //dd($explode_id);
            $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => 'dispatched']);
            return redirect()->route('ManualOrders.index')->with('success', 'Order dispatched succussfully ');
            //return Redirect::back()->with('success', 'Order dispatched succussfully ');

        }
        
        //dd($request->order_action);
    }
    
    
    
    public function print_order_slip($ManualOrder_id)
    {
        $ManualOrder = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.id',$ManualOrder_id)->get();
        //dd($ManualOrder);
        return view('client.orders.manual-orders.print_slip')->with('ManualOrders',$ManualOrder);
    }
    
    public function previouse_order_history(Request $request)
    {
        $data='';
        $ManualOrders = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->select('manual_orders.id','manual_orders.customers_id','manual_orders.receiver_number','customers.first_name','manual_orders.created_at','manual_orders.status')->where('customers.number',$request->number)->get();
        
        $data =  '
         <thead>
                <tr>
                  <th scope="col">order_id</th>
                  <th scope="col">First</th>
                  <th scope="col">number</th>
                  <th scope="col">date</th>
                  <th scope="col">status</th>
                </tr>
                </thead>
                <tbody>';
                foreach($ManualOrders as $ManualOrder)
                {
                    // dd($ManualOrder);
                    $data .= '<tr>
                      <th>'.$ManualOrder->id.'</th>
                      <td>'.$ManualOrder->first_name.'</td>
                      <td>'.$ManualOrder->receiver_number.'</td>
                      <td>'.$ManualOrder->created_at.'</td>
                      <td>'.$ManualOrder->status.'</td>';
                }
            $data .= '</tbody>';
        //dd($data);
        
        return response()->json(['messege' => $data]); 
        // return view('client.orders.manual-orders.view')->with('ManualOrder',$ManualOrder);
    }
    
    public function status_order_list($status)
    {
        $list_order = 'ASC';
        if($status == 'pending' || $status == 'confirmed' || $status == 'dispatched'  )
        {
            $list_order = 'DESC';
        }
        $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.status','like',$status.'%')
            ->orderBy('manual_orders.id', $list_order)
            ->select('manual_orders.id','manual_orders.customers_id','manual_orders.description','customers.first_name','customers.last_name','customers.number','manual_orders.receiver_number','manual_orders.reciever_address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at')
            ->paginate(20);
            //dd($list);
//dd($list);
        // $list = $list->all();
        // dd($list->all());
        return view('client.orders.manual-orders.list')->with('list',$list);
        
    }
    
    public function dispatch_bulk_orders()
    {
        $riders = Riders::where('status','active')->get();
        //dd($riders);
        return view('client.orders.manual-orders.dispatch_bulk_orders')->with('riders', $riders);
    }
    
    public function get_order_details( $ManualOrder)
    { 
        $ManualOrder = ManualOrders::find($ManualOrder);
        //dd($ManualOrder);
        if($ManualOrder != null)
        {
            return response()->json(['messege' => $ManualOrder]);
        }
        else
        {
            return response()->json(['messege' => 'no order found']);
        }
        return view('client.orders.manual-orders.dispatch');
    }
    
    public function popup_dispatch_edit($ManualOrder)
    {
        
        //dd($ManualOrder);
        $ManualOrder = ManualOrders::where('manual_orders.id',$ManualOrder)->first();
        //dd(ManualOrders::leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->where('manual_orders.status','pending')); 
       // dd($ManualOrder) ;
        return response()->json(['messege' => $ManualOrder]);
        //
    }
    
    public function popup_dispatch_update(Request $request, ManualOrders $ManualOrder)
    {
        //dd($ManualOrder);
        $ManualOrder->receiver_name = $request->receiver_name;
        $ManualOrder->receiver_number = $request->receiver_number;
        $ManualOrder->reciever_address = $request->reciever_address;  
        $ManualOrder->price = $request->price;
        $ManualOrder->cod_amount = $request->cod_amount;
        $ManualOrder->advance_payment = $request->advance_payment;
        $ManualOrder->status = $request->status;
        $ManualOrder->updated_by = Auth::id();
        $status = $ManualOrder->save();
        
        return response()->json(['messege' => $status]);
    }
    
    public function testing()
    {
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
    
        // $url = "http://mnpcourier.com/mycodapi/api/Booking/InsertBookingData";

        // $curl = curl_init($url);
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        // $headers = array(
        //   "Content-Type: application/json",
        // );
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        // $data = '{"username": "mansoor_4b459","password": "Mansoor1@3","consigneeName": "test","consigneeAddress": "test123","consigneeMobNo": "03330139993","consigneeEmail": "string","destinationCityName": "karachi","pieces": "2","weight": "1","codAmount": 1,"custRefNo": "12345689","productDetails": "string","fragile": "string","service": "overnight","remarks": "string","insuranceValue": "string","locationID": "string","AccountNo": "string","InsertType": "0"}';
        
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
        // //for debug only!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        
        // $resp = curl_exec($curl);
        // curl_close($curl);
        // var_dump($resp);
    }
    
 
}
