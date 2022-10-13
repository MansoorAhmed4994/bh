<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Riders;
use App\Models\Inventory;
use App\Models\Orderpayments;
use App\Models\Order_details;
use App\Models\Client\Customers;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Traits\MNPTraits;
use App\Traits\TraxTraits;
use App\Traits\ManualOrderTraits;
use Carbon\Carbon;
use DB;

class AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     

    use ManualOrderTraits;
    use MNPTraits;
    use TraxTraits;
     public function OrderFieldList()
    {
        return array(
            'manual_orders.consignment_id',
            'manual_orders.id',
            'manual_orders.payment_status',
            'manual_orders.customers_id',
            'manual_orders.description',
            'manual_orders.receiver_number',
            'customers.first_name',
            'manual_orders.reciever_address',
            'customers.last_name',
            'customers.number',
            'customers.address',
            'manual_orders.price',
            'manual_orders.images',
            'manual_orders.total_pieces',
            'manual_orders.date_order_paid',
            'manual_orders.status',
            'manual_orders.created_at',
            'manual_orders.updated_at',
            'manual_orders.status_reason',
            'manual_orders.fare',
            'manual_orders.price',
            'manual_orders.advance_payment',
            'manual_orders.cod_amount',
            'manual_orders.payment_status',
            
            
            'orderpayments.amount',
            'orderpayments.charges',
            'orderpayments.gst',
            'orderpayments.payment_id',
            'orderpayments.payable'); 

    }
    public function index(Request $request)
    {
        //  $order_id = $request->search_order_id;
        // $search_text = $request->search_text;
        // $order_status = 'dispatched';
        // //dd($request);
        // if($order_id != '')
        // {
        //     $search_test = $request->search_text;
        //     $order_status = $request->order_status;
        //     $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.id',$order_id)
        //     ->select($this->OrderFieldList())
        //     ->paginate(20);
        // }
        // else if($search_text != '')
        // {
        // $search_test = $request->search_text;
        
        // $order_status = $request->order_status;
        // $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->
        // where(function ($query) use ($search_test) {
        //     $query->where('customers.first_name','like',$search_test.'%')
        //             ->orWhere('customers.first_name','like','%'.$search_test.'%')
        //             ->orWhere('customers.first_name','like','%'.$search_test)
        //             ->orWhere('customers.last_name','like',$search_test.'%')
        //             ->orWhere('customers.last_name','like','%'.$search_test.'%')
        //             ->orWhere('customers.last_name','like','%'.$search_test)
        //             ->orWhere('customers.number','like','%'.$search_test) 
        //             ->orWhere('customers.number','like',$search_test.'%')
        //             ->orWhere('customers.number','like','%'.$search_test.'%')
        //             ->orWhere('manual_orders.id','like','%'.$search_test.'%')
        //             ->orWhere('manual_orders.consignment_id','like','%'.$search_test.'%');
        //     })->where('manual_orders.status','like',$order_status.'%')
        //     ->orderBy('manual_orders.id', 'DESC')
        //     ->select($this->OrderFieldList())
        //     ->paginate(20);
            
        // }
        // else if($order_status != '')
        // {
        //     $query = Customers::query();
            
        //     $query = $query->rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id');
        //     if($order_status != 'all')
        //     {
        //         $query = $query->where('manual_orders.status',$order_status);
        //     } 
        //     $list = $query->orderBy('manual_orders.id', 'DESC')
        //     ->select($this->OrderFieldList())
        //     ->paginate(20); 
        // }
        // else
        // {
        //     $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')
        //     ->where('manual_orders.status','pending')
        //     ->orderBy('manual_orders.id', 'DESC')
        //     ->select($this->OrderFieldList())
        //     ->paginate(20);
        // }
        //dd(Customers::select('*')->manual_orders()->first()->id);
        // dd(ManualOrders::leftJoin('orderpayments', 'orderpayments.order_id', '=', 'manual_orders.id')->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->select($this->OrderFieldList())->where('manual_orders.consignment_id','>','0')->paginate(20));
        $list = ManualOrders::leftJoin('orderpayments', 'orderpayments.order_id', '=', 'manual_orders.id')->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')->select($this->OrderFieldList())->where([['manual_orders.consignment_id','>','0'],['manual_orders.status','=','dispatched']])->paginate(20);
        // dd($list);
        return view('admin.accounts.orders')->with('list',$list);  
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
     
     
     
    public function UpdateShipmentPaymentStatus(Request $request,$id,$order_id)
    {
        //dd($request->consignment_id);
        $data = $this->GetShipmentPaymentStatus($id);
        //return response()->json(['messege' => $data]);
        //dd($id);
        if($data->status == 0)
        {
            // if($data->current_payment_status == "Payment - Paid")
            // { 
                // dd($data);
                
                $matchThese = ['consignment_id' => $id, 'order_id' => $order_id, 'payment_id' => $data->payments[0]->id];
                $orderpayment = Orderpayments::where($matchThese)->first();
                //  dd()/
                if(!$orderpayment)
                { 
                    $Orderpayments = Orderpayments::create([
                    'order_id' => $order_id,
                    'consignment_id' => $id,
                    'cash_handling_charges' => (isset($data->charges->cash_handling_charges) ? $data->charges->cash_handling_charges : ''),  
                    'fuel_surcharge' => $data->charges->fuel_surcharge,  
                    'weight_charges' => (isset($data->charges->weight_charges) ? $data->charges->weight_charges : '') ,  
                    'current_payment_status' => (isset($data->current_payment_status) ? $data->current_payment_status  : ''),  
                    'message' => $data->message,  
                    'amount' => $data->payments[0]->amount,  
                    'charges' => $data->payments[0]->charges,  
                    'datetime' => $data->payments[0]->datetime,  
                    'gst' => $data->payments[0]->gst,  
                    'payment_id' => $data->payments[0]->id,  
                    'payable' => $data->payments[0]->payable,  
                    'type' => $data->payments[0]->type,      
                    'created_by' => Auth::id(),  
                    'updated_by' => Auth::id(),  
                    'status' => 'active',
                    ]);
                    // dd($data->current_payment_status);
                    $ManualOrders = ManualOrders::find($order_id);
     
                    $ManualOrders->payment_status = $data->current_payment_status;
                     
                    $ManualOrders->save();
                }
            
            // }
            
            return response()->json(['messege' => $data]);
        }
        return response()->json(['messege' => $data]);
        
    } 
    
    
    public function CroneUpdateShipmentPaymentStatuss()
    {
        // dd('w');
        //dd($request->consignment_id);
        $ManualOrdersLists = ManualOrders::select('consignment_id','id')->where([['consignment_id','>','0'],['payment_status','!=' ,'Payment - Paid']])->paginate(10);
        // dd($ManualOrdersLists);
        // foreach($ManualOrdersLists as $ManualOrdersList)
        // {
        //     echo $ManualOrdersList->consignment_id;
        //     $data = $this->GetShipmentPaymentStatus($ManualOrdersList->consignment_id);
        //     echo "<pre>"; print_r($data);
        // }
        // dd();
        
        //dd($data = $this->GetShipmentPaymentStatus(20222316864998));
        foreach($ManualOrdersLists as $ManualOrdersList)
        {
            
            $id = $ManualOrdersList->consignment_id;
            $order_id = $ManualOrdersList->id;
            // echo $ManualOrdersList->id."<br>";
        
            $data = $this->GetShipmentPaymentStatus($id);
            //return response()->json(['messege' => $data]);
            //dd($id);
            echo $order_id.'<br>';
            if($data->status == 0)
            {
                // if($data->current_payment_status == "Payment - Paid")
                // { 
                    // dd($data);
                    
                    $matchThese = ['consignment_id' => $id, 'order_id' => $order_id, 'payment_id' => $data->payments[0]->id];
                    $orderpayment = Orderpayments::where($matchThese)->first();
                    //  dd()/
                    if(!$orderpayment)
                    { 
                        
                        // dd($data);
                        $Orderpayments = Orderpayments::create([
                        'order_id' => $order_id,
                        'consignment_id' => $id,
                        'cash_handling_charges' => (isset($data->charges->cash_handling_charges) ? $data->charges->cash_handling_charges : '0'),  
                        'fuel_surcharge' => $data->charges->fuel_surcharge,  
                        'weight_charges' => (isset($data->charges->weight_charges) ? $data->charges->weight_charges : '0') ,  
                        'current_payment_status' => (isset($data->current_payment_status) ? $data->current_payment_status  : '0'),  
                        'message' => $data->message,  
                        'amount' => $data->payments[0]->amount,  
                        'charges' => $data->payments[0]->charges,  
                        'datetime' => (string)$data->payments[0]->datetime,  
                        'gst' => $data->payments[0]->gst,  
                        'payment_id' => $data->payments[0]->id,  
                        'payable' => $data->payments[0]->payable,  
                        'type' => $data->payments[0]->type,      
                        'created_by' => Auth::id(),  
                        'updated_by' => Auth::id(),  
                        'status' => 'active',
                        ]);
                        
                        $ManualOrders = ManualOrders::find($order_id);
         
                        $ManualOrders->payment_status = $data->current_payment_status;
                         
                        $status_save = $ManualOrders->save();
                        //print_r($status_save)."<br>";
                    }
                
                
                // }
                //return response()->json(['messege' => $data]);
            }
            
        }
        dd('work');
        // return response()->json(['messege' => $data]);
        
    }
    
    
    public function create()
    {
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
