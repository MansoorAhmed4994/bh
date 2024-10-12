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

trait CustomerTraits {
    
    public function CreateCustomer($requests)
    {  
        foreach($requests as $request)
        { 
            $customer_check = Customers::select('id')->where('number',$request['number']); 
            
            if($customer_check->count() == 0) 
            { 
                // dd($customer_check->first());
                $customers = new Customers();
                $customers->first_name = $request['first_name'];
                $customers->last_name = $request['last_name'];
                $customers->address = $request['address'];
                $customers->number = $request['number'];
                $customers->whatsapp_number = $request['number'];
                $customers->created_by = $request['created_by'];
                $customers->updated_by = $request['updated_by'];
                $customers->loyality_count = $request['loyality_count'];
                $customers->status = $request['status'];
                $customers->save();
                
                return array(
                    
                    'error'=>0,
                    'data'=>$customers,
                    
                    );
            }
            else
            {
                return array(
                    
                    'error'=>1,
                    'data'=> $customer_check->first()->id,
                    
                    ); 
            }
        } 
        
          
    } 
    
    
}