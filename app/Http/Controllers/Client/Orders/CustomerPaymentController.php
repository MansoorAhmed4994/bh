<?php

namespace App\Http\Controllers\Client\Orders;

use App\Http\Controllers\Controller;

//Models
use App\Models\Client\CustomerPayments; 
use App\Models\Client\ManualOrders;

//Libraries
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class CustomerPaymentController extends Controller
{
    private $images_path =  'storage/images/orders/customer_payments/';
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
            'amount' => 'required',
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
                return redirect()->route('customer.payments.index')->with('errors','Transfer to / Date time / sender name is same');
            }
            
            $images=array();
            if($files=$request->file('images')){
                foreach($files as $file){
                    
                    
                    $name=uniqid().$file->getClientOriginalName();
                    $finaly_path = $this->images_path;
                    $file->move($finaly_path,$name);
                    $images[]=$finaly_path.$name;
                }
            } 
            
    
            $customer_payments = new CustomerPayments(); 
            $customer_payments->order_id = $request->order_id;
            $customer_payments->transaction_id = $request->transaction_id;
            $customer_payments->sender_name = $request->sender_name;
            $customer_payments->amount = $request->amount; 
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
                $amount = CustomerPayments::where('customer_payments.order_id',$request->order_id)->sum('amount');
                $ManualOrder = ManualOrders::where('id',$request->order_id)->update(['advance_payment' => $amount]);
                
                if($ManualOrder)
                {
                    
                    return redirect()->route('customer.payments.index')->with('success','Payment id:  '.$request->transaction_id.'  Successfully Added');
                }
                else
                {
                    return redirect()->route('customer.payments.index')->with(['errors'=>'Advance payment not update but payment S.S uploaded']);
                }
                // return 'Payment id:  '.$request->transaction_id.'  Successfully Added';
            }
            else
            {
                return redirect()->route('customer.payments.index')->with(['errors'=>'Some thing went wrong! please try again']);
            }
        }
        else
        {
            
            return redirect()->route('customer.payments.index')->with(['errors'=>'Transaction id already exist']);
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
    public function edit(CustomerPayments $customerpayment)
    {
        // $delete_query = CustomerPayments::find($id);
        return response()->json(['success' => 'successfully get','data'=>$customerpayment]); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    { 
        $amount = CustomerPayments::where('customer_payments.order_id',$request->edit_order_id)->sum('amount');
        $ManualOrder = ManualOrders::where('id',$request->edit_order_id)->update(['advance_payment' => $amount]);
        
        // dd($request->file('edit_images'));
        $images=array();
            if($files=$request->file('edit_images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    
                    $file->move($this->images_path,$name);
                    $images[]=$this->images_path.$name;
                }
            }
            // dd($images);
            // if($images != null)
            // {
                
            // }
        $CustomerPayments = CustomerPayments::find($request->edit_customer_payment_id);
            
            $CustomerPayments->order_id =$request->edit_order_id;
            $CustomerPayments->transaction_id =$request->edit_transaction_id;
            $CustomerPayments->sender_name = $request->edit_sender_name;
            $CustomerPayments->amount = $request->edit_amount;
            $CustomerPayments->datetime =$request->edit_datetime; 
            $CustomerPayments->transfer_to = $request->edit_transfer_to;
            $CustomerPayments->description = $request->edit_description;
            if(!empty($images))
            {
                // dd($images);
                $CustomerPayments->images = $images[0];
            }
            $CustomerPayments->updated_by = Auth::id();
            $CustomerPayments->save(); 
            // if(!empty($CustomerPayments))
            // {
            //     dd($CustomerPayments);
            // }
            // else
            // {
            //     dd('not working');
            // }
            
            if(!empty($CustomerPayments))
            {
                return response()->json(['success' => '1','messege'=>'Payment Updated successfully']); 
                
            }
            else
            {
                return response()->json(['error' => '1','messege'=>'Payment not saved please contact admin']);
            }
            // dd($action_status);
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
        $delete_query = CustomerPayments::find($id);
        $order_id = $delete_query->order_id;
        $status = ManualOrders::find($order_id)->status;
        $update_status = false;
        
        
        if($status != 'dispatched' )
        {
            $update_status = true; 
        } 
        
        if($status == 'dispatched' && Auth::guard('user')->check())
        {
            $update_status = false;
            return response()->json([
                'messege' => 'Order is dispatched! Only Admin can delete this payment',
                'error' => 1
                ]);
        }
        elseif($status == 'dispatched' && Auth::guard('admin')->check())
        {
            $update_status = true;
        }
        
        if($update_status == true)
        {
            $status = $delete_query->delete();
            if($status)
            {
                $amount = CustomerPayments::where('customer_payments.order_id',$order_id)->sum('amount');
                $ManualOrder = ManualOrders::where('id',$order_id)->update(['advance_payment' => $amount]);
                if($ManualOrder)
                {
                    return response()->json([
                    'messege' => 'deleted',
                    'error' => 0
                    ]);
                }
                else
                {
                    return response()->json([
                    'messege' => 'Manualorders payment not updated',
                    'error' => 1
                    ]);
                }
            }
            else
            {
                return response()->json([
                'messege' => 'Customer payment not deleted',
                'error' => 1
                ]);
            }   
        }
            

        //
    }
    
    
    public function GetCustomerPayments(Request $request)
    {
        // dd('work');
        $data='';
        $customerPayments;
        $customerPayments = CustomerPayments::query();
        if($request->search_order_id == '')
        {
            $customerPayments = $customerPayments->where('customer_payments.status','approval pending');
        }
        else
        {
            
            $customerPayments = $customerPayments->where('customer_payments.order_id',$request->search_order_id);
        }
        
        if($request->search_transaction_id != '')
        {
            $customerPayments = $customerPayments->where('customer_payments.transaction_id',$request->search_transaction_id);
        }
        
        if($request->search_sender_name != '')
        {
            $customerPayments = $customerPayments->where('customer_payments.sender_name','like','%'.$request->search_sender_name.'%');
        }
        
        if($request->search_amount != '')
        {
            $customerPayments = $customerPayments->where('customer_payments.amount',$request->search_amount);
        }
        
        if($request->search_date != '')
        {
            $customerPayments = $customerPayments->where('customer_payments.datetime',$request->search_date);
        }
        
        if($request->search_transfer_to != '')
        {
            $customerPayments = $customerPayments->where('customer_payments.transfer_to',$request->search_transfer_to);
        }
        // dd($request->search_transfer_to);
        if($request->search_payment_status != '')
        {
            $customerPayments = $customerPayments->where('customer_payments.status',$request->search_payment_status);
        }
        
        
        $customerPayments = $customerPayments->orderBy('customer_payments.id', 'DESC')->get();
        // dd($customerPayments);
        // dd($customerPayments);
        // $ManualOrders = ManualOrders::where('customers.number',$request->number)->get();
        // dd($customerPayments);
        
        $data .=  '
         <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">S.S</th>
                    <th scope="col">Order ID</th>
                    <th scope="col">Transaction id</th>
                    <th scope="col">Sender Name</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Datetime</th>
                    <th scope="col">Transfer To</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>';
                     
        
        $data .= ' 
                  <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>';
            foreach($customerPayments as $customerPayment)
            { 
                $image_url = asset($customerPayment->images);
                $data .= '<tr> 
                    <th>'.$customerPayment->id.'</th> 
                    <th><img class="previouse_order_images" id="'.$customerPayment->transaction_id.'" onclick="open_image_modal('.$customerPayment->transaction_id.')" src="'.$image_url.'"/ width="100"></th>
                    <td>'.$customerPayment->order_id.'</td>
                    <td>'.$customerPayment->transaction_id.'</td>
                    <td>'.$customerPayment->sender_name.'</td>
                    <td>'.$customerPayment->amount.'</td>
                    <td>'.$customerPayment->datetime.'</td>
                    <td>'.$customerPayment->transfer_to.'</td>
                    <td>'.$customerPayment->description.'</td>
                    <td>'.$customerPayment->status.'</td> 
                    ';
                
                // if($customerPayment->status == 'approval pending')
                // {
                //     if(Auth::guard('admin')->check())
                //     {
                //         $data .=  '<td><button class="btn btn-danger" onclick="actionpaymentapproval('.$customerPayment->id.',"approved")">Approval Pending</button></td>';
                //     }
                //     else
                //     {
                //         $data .=  '<td><button class="btn btn-danger" disable>Approval Pending</button></td>';
                //     }
                // }
                // else
                // {
                //     $data .=  '<td><button class="btn btn-success" disable>Approved</button></td>';
                // }
                
               
                $data .=  
                '<td><select class="btn btn-primary" onchange="actionpaymentapproval('.$customerPayment->id.',this.value)">';
                
                $data .=  '<option value="">Select Action</option>';
                $data .=  '<option value="delete">Delete</option>';
                if(Auth::guard('admin')->check())
                {
                    $data .=  '<option value="approval pending">Remove Approval</option>';
                    $data .=  '<option value="approved">Approved</option>';
                    $data .=  '<option value="edit">Edit</option>';
                }
                $data .=  '</select></td>';
                
                
                  
                $data .= '</tr>'; 
            }
        $data .= '</tbody>'; 
        
        return response()->json([
            'messege' => $data
            ]);
    }
    
    
    public function CheckPaymentApproval($order_id)
    {
        
    }
    
    public function ChangePaymentStatus($id,$status)
    {
        // dd($id,$status);
        if(Auth::guard('admin')->check())
        { 
            $status = CustomerPayments::where('id',$id)->update(['status' => $status]); 
            if($status)
            {
                return response()->json([
                'messege' => 'updated status to '.$status,
                'error' => 0
                ]);
            }
        }
        else
        {
            return response()->json([
            'messege' => 'Only Admin can change the status',
            'error' => 1
            ]);
        }
            
        
                
    }
}
