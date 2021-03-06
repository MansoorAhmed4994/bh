<?php

namespace App\Traits; 
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator; 
use Carbon\Carbon;
use DB;

trait ManualOrderTraits {
    
    public function CreateOrder(Request $request)
    { 
        $validated = $request->validate([

            'images' => 'required',
            'first_name' => 'required',
            'number' => 'required',
            'address' => 'required',

        ]);
        $customer_id = Customers::where('number',$request->number);
        //dd($customer_id);
        if($customer_id->get()->isEmpty())
        {
            
            $images=array();
            if($files=$request->file('images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    
                    $file->move($this->images_path,$name);
                    $images[]=$this->images_path.$name;
                }
            }
    
           //dd();
            
            $customers = new Customers();
            $customers->first_name = $request->first_name;
            $customers->last_name = $request->last_name;
            $customers->address = $request->address;
            $customers->number = $request->number;
            $customers->whatsapp_number = $request->number;
            $customers->created_by = '1';
            $customers->updated_by = '1';
            $customers->status = 'active'; 
            $customers->save();
            ///$customers = $customers->save();
            // dd($customers->id);
            
            
    
            $manual_orders = new ManualOrders();
            //$manual_orders->customer_id = $customers->id;
            $manual_orders->receiver_name = $request->first_name;
            $manual_orders->receiver_number = $request->number;
            $manual_orders->city = '';
            $manual_orders->reciever_address = $request->address;
            $manual_orders->images = implode("|",$images);
            $manual_orders->total_pieces = '';
            $manual_orders->weight = '';
            $manual_orders->price = '0';
            $manual_orders->cod_amount = '0';
            $manual_orders->advance_payment = '0';
            $manual_orders->date_order_paid = '';
            $manual_orders->description = $request->description;
            $manual_orders->reference_number = '';
            $manual_orders->service_type = '';
            $manual_orders->created_by = '1';
            $manual_orders->updated_by = '1';
            $manual_orders->status = 'pending';
            //$manual_orders = $manual_orders->save();
             $status = $customers->manual_orders()->save($manual_orders);
             
            if($status->id)
            {
                return 'Order id:  '.$status->id.'  Successfully Placed';
            }
            else
            {
                return 'Some thing went wrong';
            }
        }
        else
        {
            //dd($customer_id->id);
            $images=array();
            if($files=$request->file('images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    
                    $file->move($this->images_path,$name);
                    $images[]=$this->images_path.$name;
                }
            }
            
            $manual_orders = new ManualOrders();
            //$manual_orders->customer_id = $customers->id;
            $manual_orders->receiver_name = $request->first_name;
            $manual_orders->receiver_number = $request->number;
            $manual_orders->city = '';
            $manual_orders->reciever_address = $request->address;
            $manual_orders->images = implode("|",$images);
            $manual_orders->total_pieces = '';
            $manual_orders->weight = '';
            $manual_orders->price = '';
            $manual_orders->cod_amount = '';
            $manual_orders->advance_payment = '';
            $manual_orders->date_order_paid = '';
            $manual_orders->description = $request->description;
            $manual_orders->reference_number = '';
            $manual_orders->service_type = '';
            $manual_orders->created_by = '1';
            $manual_orders->updated_by = '1';
            $manual_orders->status = 'pending';
            //$manual_orders = $manual_orders->save();
            $status = $customer_id->first()->manual_orders()->save($manual_orders);
             //dd();
             
            if($status->id)
            {
                //dd();
                return 'Order id:  '.$status->id.'  Successfully Placed';
            }
        }
    }
        
    public function GetOrdersByIds($order_ids)
    { 
        $ManualOrders = ManualOrders::whereIn('id',$order_ids)->get();
        return $ManualOrders;
    }
        
    public function UpdateStatusByIdIds($explode_id,$status)
    { 
        $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => $status]);
            return redirect()->route('ManualOrders.index')->with('success', 'Order dispatched succussfully ');
    }
        
    public function UpdateReferenceNumberByOrderIds($order_ids)
    {
        $ManualOrders = ManualOrders::whereIn('id',$order_ids)->update(['manual_orders.reference_number' => DB::raw("concat('(',`id`,')(',`updated_at`,')')")]); 
        return $ManualOrders;
    }
    
    
    
    
}