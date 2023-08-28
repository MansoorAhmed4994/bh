<?php

namespace App\Http\Controllers\Client\Orders;

use App\Http\Controllers\Controller;
use App\Models\Client\CustomerPayments;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator; 
use Carbon\Carbon;
use DB;

class CustomerPaymentController extends Controller
{
    private $images_path =  'storage/images/orders/manual-orders/customer_payments';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('client.orders.CustomerPayments.create');
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        // dd($request); 
        $validated = $request->validate([


            'order_id' => 'required',
            'transaction_id' => 'required',
            'sender_name' => 'required',
            'datetime' => 'required',
            'transfer_to' => 'required',
            'description' => 'required',

        ]);
        $customer_id = CustomerPayments::where('transaction_id',$request->transaction_id);
        
        
        //dd($customer_id);
        if($customer_id->get()->isEmpty())
        {
            $check_again_transanction = CustomerPayments::where('datetime',$request->datetime)->where('transfer_to',$request->transfer_to)->where('sender_name',$request->sender_name);
            // dd($check_again_transanction);
            if($check_again_transanction->get()->isEmpty())
            {
                
            }
            else
            {
                return redirect()->route('customer.payments.index')->with('errors','There are some error please check error log!');
            }
            
            $images=array();
            if($files=$request->file('images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    
                    $file->move($this->images_path,$name);
                    $images[]=$this->images_path.$name;
                }
            } 
            
    
            $customer_payments = new CustomerPayments(); 
            $customer_payments->order_id = $request->order_id;
            $customer_payments->transaction_id = $request->transaction_id;
            $customer_payments->sender_name = $request->sender_name; 
            $customer_payments->datetime = $request->datetime; 
            $customer_payments->transfer_to = $request->transfer_to; 
            $customer_payments->description = $request->description;
            $customer_payments->images = implode("|",$images);
            $customer_payments->created_by = Auth::id();
            $customer_payments->updated_by = Auth::id();
            $customer_payments->status = 'approval pending';
            //$manual_orders = $manual_orders->save();
             $status = $customer_payments->save();
            //  dd($status);
            if($status)
            {
                return redirect()->route('customer.payments.index')->with('success','Payment id:  '.$request->transaction_id.'  Successfully Added');
                // return 'Payment id:  '.$request->transaction_id.'  Successfully Added';
            }
            else
            {
                return redirect()->route('customer.payments.index')->with('errors','Some thing went wrong! please try again');
            }
        }
        else
        {
            return redirect()->route('customer.payments.index')->with('errors','Transaction id already exist');
        }
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
    
    public function GetCustomerPayments(Request $request)
    {
        // dd('work');
        $data='';
        $customerPayments = CustomerPayments::where('customer_payments.order_id',$request->order_id)->orderBy('customer_payments.id', 'DESC')->get();
        // $ManualOrders = ManualOrders::where('customers.number',$request->number)->get();
        // dd($customerPayments);
        
        $data =  '
         <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">Order ID</th>
                    <th scope="col">Transaction id</th>
                    <th scope="col">Sender Name</th>
                    <th scope="col">Datetime</th>
                    <th scope="col">Transfer To</th>
                    <th scope="col">Description</th>
                    <th scope="col">Action Edit</th>';
                    
                    
                    if(Auth::guard('admin')->check())
                    {
                        
                        $data .=  '<th>Action Approved</td>';
                    } 
        
        $data = ' 
                  <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>';
            foreach($customerPayments as $customerPayment)
            { 
                $data .= '<tr> 
                    <th>'.$customerPayment->id.'</th>
                    <th>'.$customerPayment->images.'</th>
                    <td>'.$customerPayment->order_id.'</td>
                    <td>'.$customerPayment->transaction_id.'</td>
                    <td>'.$customerPayment->sender_name.'</td>
                    <td>'.$customerPayment->datetime.'</td>
                    <td>'.$customerPayment->transfer_to.'</td>
                    <td>'.$customerPayment->description.'</td>';
                
                if($customerPayment->status == 'approval pending')
                {
                    if(Auth::guard('admin')->check())
                    {
                        $data .=  '<td><button class="btn btn-danger" onclick="getpaymentapproval('.$customerPayment->id.')">Approval Pending</button></td>';
                    }
                    else
                    {
                        $data .=  '<td><button class="btn btn-danger" disable>Approved</button></td>';
                    }
                }
                else
                {
                    $data .=  '<td><button class="btn btn-success" disable>Approved</button></td>';
                }
                
                
                  
                $data .= '</tr>'; 
            }
        $data .= '</tbody>'; 
        
        return response()->json([
            'messege' => $data
            ]);
    }
}
