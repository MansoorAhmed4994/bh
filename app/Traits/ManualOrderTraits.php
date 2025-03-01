<?php

namespace App\Traits; 
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;

use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator; 

use App\Models\User;
use Carbon\Carbon;
use DB;

trait ManualOrderTraits {
    
    public function CreateOrder(Request $request)
    { 
        
        
        $status = $request->order_addition;
        $customer_id = '';
        $manualorders_id = '';
        $validated = $request->validate([

            'images' => 'required',
            'first_name' => 'required',
            'number' => 'required',
            'address' => 'required', 

        ]);
        // dd($this->AssignOrderToUser());
        
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
            $customers->created_by = Auth::id();
            $customers->updated_by = Auth::id();
            $customers->loyality_count = 1;
            $customers->status = 'active'; 
            $customers->save();
            ///$customers = $customers->save();
            // dd($customers->id);
            
            
            $manual_orders = new ManualOrders();
            //$manual_orders->customer_id = $customers->id;
            $manual_orders->receiver_name = $request->first_name;
            $manual_orders->receiver_number = $request->number;
            if($request->city != '')
            {
                $manual_orders->cities_id = $request->city;
            }
            $manual_orders->reciever_address = $request->address;
            $manual_orders->images = implode("|",$images);
            $manual_orders->total_pieces = '';
            $manual_orders->weight = '';
            $manual_orders->price = 0;
            $manual_orders->cod_amount = 0;
            $manual_orders->advance_payment = 0;
            $manual_orders->date_order_paid = '';
            $manual_orders->description = $request->description;
            $manual_orders->reference_number = '';
            $manual_orders->service_type = '';
            $manual_orders->assign_to = Auth::id();
            
            $manual_orders->created_by = Auth::id();
            $manual_orders->updated_by = Auth::id();
            $manual_orders->status = 'pending';
            //$manual_orders = $manual_orders->save();
             $status = $customers->manual_orders()->save($manual_orders);
            $manualorders_id = $status->id;
            $customer_id = $customer_id->first()->id;
            if($status->id)
            {
                create_activity_log(['table_name'=>'manual_orders','ref_id'=>$manualorders_id,'activity_desc'=>'New order placed','created_by'=>Auth::id(),'method'=>'insert','route'=>route('ManualOrders.store')]);
                create_activity_log(['table_name'=>'customers','ref_id'=>$customer_id,'activity_desc'=>'New Customer placed','created_by'=>Auth::id(),'method'=>'insert','route'=>route('ManualOrders.store')]);
                return 'Order id:  '.$status->id.'  Successfully Placed';
                
            }
            else
            {
                return 'Some thing went wrong';
            }
        }
        else
        {
            
            $images=array();
            if($files=$request->file('images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    
                    $file->move($this->images_path,$name);
                    $images[]=$this->images_path.$name;
                }
            }
            
            if($status == 'addition')
            {
                $order_id = $request->order_id;
                $manual_orders = ManualOrders::find($order_id);
                // dd($manual_orders);
                if($manual_orders->status == 'dispatched' ||  $manual_orders->status == 'confirmed')
                {
                    toastr()->error('Order cannot convert to dispatch!');
                    return back();
                }
                if($images != null)
                {
                    $manual_orders->images = $manual_orders->images.'|'.(implode("|",$images));
                }
                if($manual_orders->status == 'pending')
                {
                    $manual_orders->status = 'pending';
                }
                else
                {
                    
                    $manual_orders->status = $status;
                }
                $status = $customer_id->first()->manual_orders()->save($manual_orders);
                 //dd(); 
                $manualorders_id = $status->id; 
                 
                if($status->id)
                {
                    //dd();
                    create_activity_log(['table_name'=>'manual_orders','ref_id'=>$manualorders_id,'activity_desc'=>'Order Updated successfully','created_by'=>Auth::id(),'method'=>'insert','route'=>route('ManualOrders.store')]);
    
                    return 'Order id:  '.$status->id.'  Successfully Added';
                }
                // dd($manual_orders->images);
            }
            else
            {
                // $manual_orders = ManualOrders::where('receiver_number',$request->number)->where('status');
                
                $manual_orders = new ManualOrders();
                //$manual_orders->customer_id = $customers->id;
                $manual_orders->receiver_name = $request->first_name;
                $manual_orders->receiver_number = $request->number;
                if($request->city != '')
                {
                    $manual_orders->cities_id = $request->city;
                }
                
                $manual_orders->reciever_address = $request->address;
                $manual_orders->images = implode("|",$images);
                $manual_orders->total_pieces = '';
                $manual_orders->weight = '';
                $manual_orders->price = 0;
                $manual_orders->cod_amount = 0;
                $manual_orders->advance_payment = 0;
                $manual_orders->date_order_paid = '';
                $manual_orders->description = $request->description;
                $manual_orders->reference_number = '';
                $manual_orders->service_type = '';
                $manual_orders->created_by = Auth::id();
                $manual_orders->updated_by = Auth::id(); 
                $manual_orders->assign_to = Auth::id();
                $manual_orders->status = 'pending';
                //$manual_orders = $manual_orders->save();
                $status = $customer_id->first()->manual_orders()->save($manual_orders);
                $customers_update = $customer_id->first();
                $customers_update->loyality_count = $customers_update->loyality_count+1;
                // dd(->update(['loyality_count' => $order_status]));
                 //dd(); 
                $manualorders_id = $status->id; 
                $customers_update->save();
                 
                if($status->id)
                {
                    //dd();
                    create_activity_log(['table_name'=>'manual_orders','ref_id'=>$manualorders_id,'activity_desc'=>'New order placed','created_by'=>Auth::id(),'method'=>'insert','route'=>route('ManualOrders.store')]);
    
                    return 'Order id:  '.$status->id.'  Successfully Placed';
                }
            }
        }
    }
    
    public function AssignOrderToUser()
    {
        $users_parcel  = array();
        $Users = User::whereHas(
            'roles', function($q){
                $q->where('name', 'calling');
            }
        )->get()->pluck('id')->toArray();
        
        $user_ids = implode(', ', $Users);
        $data = DB::select('select `users`.`id` as `id`, (SELECT COUNT(*)  FROM manual_orders where manual_orders.assign_to = users.id) as `items` from `users` where `users`.`id` in ('.$user_ids.') order by items ASC ');
        
        // dd($data);
    
        return ($data[0]->id);
        
        
                 
    }
        
    public function GetOrdersByIds($order_ids)
    { 
        $ManualOrders = ManualOrders::whereIn('id',$order_ids)->get();
        return $ManualOrders;
    }
        
    public function UpdateStatusByIdIds($explode_id,$status)
    { 
        $ManualOrder = ManualOrders::whereIn('id',$explode_id)->update(['status' => $status]);
        
        foreach($explode_id as $explode_ids)
        {
            create_activity_log(['table_name'=>'manual_orders','ref_id'=>$explode_ids,'activity_desc'=>'Status column updated to: '.$status,'created_by'=>Auth::id(),'method'=>'update','route'=>route('ManualOrders.store')]);

        }
        return redirect()->route('ManualOrders.index')->with('success', 'Order dispatched succussfully ');
    }
        
    public function UpdateReferenceNumberByOrderIds($order_ids)
    {
        $ManualOrders = ManualOrders::whereIn('id',$order_ids)->update(['manual_orders.reference_number' => DB::raw("concat('(',`id`,')(',`updated_at`,')')")]); 
        return $ManualOrders;
    }
    
    
    
    
}