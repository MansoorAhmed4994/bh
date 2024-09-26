<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\LeopordTraits;
use App\Models\Client\Customers;
use Illuminate\Support\Facades\Cookie;  
use Illuminate\Support\Facades\File; 
use Session;
use Illuminate\Support\Facades\Storage;

use App\Models\Client\ManualOrders;
class CustomersController extends Controller
{
 use LeopordTraits;
    
    private $images_path =  'storage/images/orders/manual-orders/';
    private $coockie_duration = 600;
    public function Create(Request $request)
    { 
        // $query = '';
        // if($request->hasCookie('number') == true )
        // {
        //     if($request->hasCookie('images') == false)
        //     {
        //         $query = ManualOrders::Select('*')->where('manual_orders.customers_id',Cookie::get('id'))->where('manual_orders.status','pending')->orderBy('manual_orders.id','desc');
        //         if($query->count() >= 1)
        //         { 
        //             $this->ManualOrdersCoockie($order_id,$receiver_name,$receiver_number,$reciever_address,$images);
        //         }
        //     }
        // } 
        
        // dd($request->cookie()); 
        $cities = $this->LeopordGetCities()->city_list;  
        return view('frontend.client.orders.manual-orders.register_customer')->with([ 'cities'=>$cities]); 
    }
    
    public function Store(Request $request)
    {
        
        // Cookie::queue('examplecookie', 1, 525600);
        // return redirect()->route('customer.create')->with('success', 'you are registered successfully! Please upload your article screen shot, Thank you.');
        // return $this->next();
        // dd('12');
        $customers = new Customers();
        $customers->first_name = $request->first_name;
        $customers->last_name = $request->last_name;
        $customers->address = $request->address;
        $customers->number = $request->number;
        $customers->whatsapp_number = $request->whatsapp_number;
        $customers->created_by = 1;
        $customers->updated_by = 1;
        $customers->loyality_count = 1;
        $customers->status = 'active'; 
        $status = $customers->save(); 
        // dd($customers);
        if($status == true)
        {  
            
            Cookie::queue('id',$customers->id, $this->coockie_duration);
            Cookie::queue('first_name', $customers->first_name, $this->coockie_duration);
            Cookie::queue('last_name', $customers->last_name, $this->coockie_duration);
            Cookie::queue('address', $customers->address, $this->coockie_duration);
            Cookie::queue('email', $customers->email, $this->coockie_duration);
            Cookie::queue('number', $customers->number, $this->coockie_duration);
            Cookie::queue('whatsapp_number', $customers->whatsapp_number, $this->coockie_duration);
            Cookie::queue('created_at', $customers->created_a, $this->coockie_duration);
             
            return redirect()->route('customer.create')->with('success', 'you are registered successfully! Please upload your article screen shot, Thank you.');
        }
        else
        {
            return redirect()->route('customer.create')->with('errors', 'Oops! Some thing went wrong please try again, Thankyou.');
        }
        
        
        // dd($status); 
    }
    
    public function GetCustomerId(Request $request)
    { 
        $number = $request->number; 
        
        if($request->hasCookie('number') == true )
        {
            
            // dd($request->cookie());
            if(Cookie::get('number') == $request->number)
            {
                return response()->json(['success'=>1,'msg'=>'already login']);
                // dd($request->number);
            }
            else
            { 
                $id = Cookie::make('id', $query->first()->id, $this->coockie_duration);
                $first_name = Cookie::make('first_name', $query->first()->first_name, $this->coockie_duration);
                $last_name = Cookie::make('last_name', $query->first()->last_name, $this->coockie_duration);
                $address = Cookie::make('address', $query->first()->address, $this->coockie_duration);
                $email = Cookie::make('email', $query->first()->email, $this->coockie_duration);
                $number = Cookie::make('number', $query->first()->number, $this->coockie_duration);
                $whatsapp_number = Cookie::make('whatsapp_number', $query->first()->whatsapp_number, $this->coockie_duration);
                $created_at = Cookie::make('created_at', $query->first()->created_at,$this->coockie_duration); 
                
                 
                $customer_order_details = $this->GetCustomerOrderDetails($query->first()->id);
                // dd($customer_order_details);
                if($customer_order_details->count() >= 1)
                {
                    
                    // $this->ManualOrdersCoockie($customer_order_details->order_id,$customer_order_details->receiver_name,$customer_order_details->receiver_number,$customer_order_details->reciever_address,$customer_order_details->order_id);
                    
                    $receiver_name = Cookie::make('receiver_name', $customer_order_details->receiver_name, $this->coockie_duration);
                    $receiver_number = Cookie::make('receiver_number', $customer_order_details->receiver_number, $this->coockie_duration);
                    $reciever_address = Cookie::make('reciever_address', $customer_order_details->reciever_address,$this->coockie_duration); 
                    $order_id = Cookie::make('reciever_address', $customer_order_details->order_id,$this->coockie_duration); 
                    $images = Cookie::make('images', $customer_order_details->order_id,$this->coockie_duration); 
                    
                    return response()->json([
                        'success'=>1,
                        'id'=>$query->first()->id,
                        'first_name'=>$query->first()->first_name,
                        'last_name'=>$query->first()->last_name,
                        'address'=>$query->first()->address,
                        'email'=>$query->first()->email,
                        'number'=>$query->first()->number,
                        'whatsapp_number'=>$query->first()->whatsapp_number,
                        'created_at'=>$query->first()->created_at,
                        
                        
                        'receiver_name'=>$query->first()->receiver_name,
                        'receiver_number'=>$query->first()->receiver_number,
                        'reciever_address'=>$query->first()->reciever_address, 
                        'order_id'=>$query->first()->order_id,
                        'images'=>$customer_order_details->first()->images,
                        'rendered_images'=>$this->ImageBoxStructure($customer_order_details->first()->images)
                        ])
                        ->withCookie($id)
                        ->withCookie($first_name)
                        ->withCookie($last_name)
                        ->withCookie($address)
                        ->withCookie($email)
                        ->withCookie($number)
                        ->withCookie($whatsapp_number)
                        ->withCookie($created_at)
                        ->withCookie($number)
                        ->withCookie($whatsapp_number)
                        ->withCookie($created_at)
                            
                        ->withCookie($receiver_name)
                        ->withCookie($receiver_number)
                        ->withCookie($reciever_address)
                        ->withCookie($order_id) 
                        ->withCookie($images);
                }
                else
                {
                    return response()->json([
                    'success'=>1,
                    'id'=>$query->first()->id,
                    'first_name'=>$query->first()->first_name,
                    'last_name'=>$query->first()->last_name,
                    'address'=>$query->first()->address,
                    'email'=>$query->first()->email,
                    'number'=>$query->first()->number,
                    'whatsapp_number'=>$query->first()->whatsapp_number,
                    'created_at'=>$query->first()->created_at
                    ])
                    ->withCookie($id)
                    ->withCookie($first_name)
                    ->withCookie($last_name)
                    ->withCookie($address)
                    ->withCookie($email)
                    ->withCookie($number)
                    ->withCookie($whatsapp_number)
                    ->withCookie($created_at)
                    ->withCookie($number)
                    ->withCookie($whatsapp_number)
                    ->withCookie($created_at);
                }
                
            }
            
        }
        else
        { 
            $query = $this->GetCustomerDetails($number);
            
            if ($query->count() >= 1) 
            {  
                $id = Cookie::make('id', $query->first()->id, $this->coockie_duration);
                $first_name = Cookie::make('first_name', $query->first()->first_name, $this->coockie_duration);
                $last_name = Cookie::make('last_name', $query->first()->last_name, $this->coockie_duration);
                $address = Cookie::make('address', $query->first()->address, $this->coockie_duration);
                $email = Cookie::make('email', $query->first()->email, $this->coockie_duration);
                $number = Cookie::make('number', $query->first()->number, $this->coockie_duration);
                $whatsapp_number = Cookie::make('whatsapp_number', $query->first()->whatsapp_number, $this->coockie_duration);
                $created_at = Cookie::make('created_at', $query->first()->created_at,$this->coockie_duration); 
                
                 
                $customer_order_details = $this->GetCustomerOrderDetails($query->first()->id);
                
                if($customer_order_details->count() >= 1)
                { 
                    $customer_order_details = $customer_order_details->first();
                    // dd($customer_order_details); 
                     
                    $receiver_name = Cookie::make('receiver_name', $customer_order_details->receiver_name, $this->coockie_duration);
                    $receiver_number = Cookie::make('receiver_number', $customer_order_details->receiver_number, $this->coockie_duration);
                    $reciever_address = Cookie::make('reciever_address', $customer_order_details->reciever_address,$this->coockie_duration); 
                    $order_id = Cookie::make('order_id', $customer_order_details->order_id,$this->coockie_duration); 
                    $images = Cookie::make('images', $customer_order_details->images,$this->coockie_duration); 
                    
                    return response()->json([
                        'success'=>1,
                        'id'=>$query->first()->id,
                        'first_name'=>$query->first()->first_name,
                        'last_name'=>$query->first()->last_name,
                        'address'=>$query->first()->address,
                        'email'=>$query->first()->email,
                        'number'=>$query->first()->number,
                        'whatsapp_number'=>$query->first()->whatsapp_number,
                        'created_at'=>$query->first()->created_at,
                        
                        
                        'receiver_name'=>$customer_order_details->receiver_name,
                        'receiver_number'=>$customer_order_details->receiver_number,
                        'reciever_address'=>$customer_order_details->reciever_address, 
                        'order_id'=>$customer_order_details->order_id,
                        'images'=>$customer_order_details->images,
                        'rendered_images'=>$this->ImageBoxStructure($customer_order_details->images)
                        ])
                        ->withCookie($id)
                        ->withCookie($first_name)
                        ->withCookie($last_name)
                        ->withCookie($address)
                        ->withCookie($email)
                        ->withCookie($number)
                        ->withCookie($whatsapp_number)
                        ->withCookie($created_at)
                        ->withCookie($number)
                        ->withCookie($whatsapp_number)
                        ->withCookie($created_at)
                            
                        ->withCookie($receiver_name)
                        ->withCookie($receiver_number)
                        ->withCookie($reciever_address)
                        ->withCookie($order_id)
                        ->withCookie($images);
                }
                else
                {
                    // dd($customer_order_details->count());
                    // dd('working2');
                    return response()->json([
                    'success'=>1,
                    'id'=>$query->first()->id,
                    'first_name'=>$query->first()->first_name,
                    'last_name'=>$query->first()->last_name,
                    'address'=>$query->first()->address,
                    'email'=>$query->first()->email,
                    'number'=>$query->first()->number,
                    'whatsapp_number'=>$query->first()->whatsapp_number,
                    'created_at'=>$query->first()->created_at
                    ])
                    ->withCookie($id)
                    ->withCookie($first_name)
                    ->withCookie($last_name)
                    ->withCookie($address)
                    ->withCookie($email)
                    ->withCookie($number)
                    ->withCookie($whatsapp_number)
                    ->withCookie($created_at)
                    ->withCookie($number)
                    ->withCookie($whatsapp_number)
                    ->withCookie($created_at);
                }
            }
            else
            {
                return response()->json(['success'=>0,'msg'=>'no customer found']); 
            }
              
        }
            
       
    }
    
    public function ImageBoxStructure($images)
    {
        $allimagesboxes = '';
        $i=1;
        $images = explode('|',$images);
        // dd($images);
        // return $images;
        foreach($images as $image)
        {
            // dd($image);
            $allimagesboxes .= '<div class="card card-box-custom col-sm-6" id="imagebox_'.$i.'">';
            $allimagesboxes .= '<div class="card-body" id="edit_selected_img_card_body"  style="padding: 1rem 1rem 0.5rem 1rem;"> ';
            $allimagesboxes .= '<img class="card-img-top" src="'.asset($image).'" id="img_id_'.$i.'" data-image-type="'.$image.'" alt="Card image cap" width="200">';
            $allimagesboxes .= '</div>';
            $allimagesboxes .= '<div class="card-footer" style="padding: 0 1.25rem 2em 1.25em;">';
            $allimagesboxes .= '<a onclick="delete_image('.$i.')" class="btn btn-primary">Delete</a>';
            $allimagesboxes .= '</div></div>';
            $i++;
        } 
        return $allimagesboxes;
    }
    
    public function GetCustomerOrderDetails($customer_id)
    {
        $query = ManualOrders::Select( 
            
            'manual_orders.receiver_name as receiver_name',
            'manual_orders.receiver_number as receiver_number',
            'manual_orders.reciever_address as reciever_address',
            'manual_orders.id as order_id',
            'manual_orders.images as images'
        )
        ->where('customers_id',$customer_id)->where('status','pending')->orderBy('manual_orders.id','desc');
        // dd($query->get()->first());
        return $query->get();
    }
    
    public function GetCustomerDetails($number)
    { 
        
        $query = Customers::Select('id', 'first_name', 'last_name', 'address', 'email', 'number',  'whatsapp_number', 'created_at')->where('number',$number);
        // $search_text = $number;
        // $query = $query->
        //     where(function ($query) use ($search_text) {
        //         $query->where('customers.number','like',$search_text.'%')
        //             ->orWhere('customers.number','like','%'.$search_text.'%')
        //             ->orWhere('customers.number','like','%'.$search_text)
        //             ->orWhere('customers.whatsapp_number','like',$search_text.'%')
        //             ->orWhere('customers.whatsapp_number','like','%'.$search_text.'%')
        //             ->orWhere('customers.whatsapp_number','like','%'.$search_text);
        //     });
        return $query->get();  
            
    }
     
    
    public function DeleteCustomerCoockies(Request $request)
    { 
        
        $id = \Cookie::forget('id');
        $first_name = \Cookie::forget('first_name');
        $last_name = \Cookie::forget('last_name');
        $address = \Cookie::forget('address');
        $email = \Cookie::forget('email');
        $number = \Cookie::forget('number');
        $whatsapp_number = \Cookie::forget('whatsapp_number');
        $created_at = \Cookie::forget('created_at'); 
        
        return response()->json(['success'=>1,'msg'=>'old coockies deleted'])
            ->withCookie($id)
            ->withCookie($first_name)
            ->withCookie($last_name)
            ->withCookie($address)
            ->withCookie($email)
            ->withCookie($number)
            ->withCookie($whatsapp_number)
            ->withCookie($created_at); 
    }
    
    public function UploadProductScreentShot(Request $request)
    {
        $images=array();
        if($files=$request->file('images')){
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                $name = bin2hex(random_bytes(10)).preg_replace('/\s+/', '', $name);
                
                // Storage::disk('upload_images')->putFileAs('',$file,'123.gif');
                
                $file->move($this->images_path,$name);
                $images[]=$this->images_path.$name;
            }
        }
            
        $query = ManualOrders::Select('manual_orders.id as mid')->where('manual_orders.customers_id',Cookie::get('id'))->where('manual_orders.status','pending')->orderBy('manual_orders.id','desc');

        // dd(Cookie::get('id'));
        $manualOrders = $query->first();
        if ($query->count() >= 1) 
        {
            
                // dd($manualOrders->mid);
            $manual_orders = ManualOrders::find($manualOrders->mid);
            
            if($images != null)
            {
                if($manual_orders->images != null)
                {
                    $manual_orders->images = $manual_orders->images.'|'.(implode("|",$images));
                }
                else
                {
                    $manual_orders->images = (implode("|",$images));
                }
            }
            $save_status = $manual_orders->save();
            if($save_status == true)
            { 
                $this->ManualOrdersCoockie($manual_orders->id,$manual_orders->receiver_name,$manual_orders->receiver_number,$manual_orders->reciever_address,$manual_orders->images);
                // dd($manual_orders->images);
                return redirect()->route('customer.create')->with('success', 'Thank you! Screen shot uploaded Successfully');
            }
            else
            {
                echo 'error please upload again or inform brandhub with this error screen shot';
                // dd($save_status);
                // return redirect()->route('customer.create')->with('errors', $success_msg);
            }
            dd($save_status);
        }
        else
        {  
            // dd(Cookie::get('first_name').' '.Cookie::get('last_name'),Cookie::get('number'),Cookie::get('address'));
            $manual_orders = new ManualOrders(); 
            $manual_orders->receiver_name = Cookie::get('first_name').' '.Cookie::get('last_name');
            $manual_orders->receiver_number = Cookie::get('number');
            if($request->city != '')
            {
                $manual_orders->cities_id = $request->city;
            }
            $manual_orders->reciever_address = Cookie::get('address'); 
            $manual_orders->images = implode("|",$images);
            $manual_orders->total_pieces = '';
            $manual_orders->weight = '';
            $manual_orders->price = 0;
            $manual_orders->cod_amount = 0;
            $manual_orders->advance_payment = 0;
            $manual_orders->date_order_paid = '';
            $manual_orders->description = '';
            $manual_orders->reference_number = '';
            $manual_orders->service_type = '';
            $manual_orders->assign_to = 1; 
            $manual_orders->created_by = 1;
            $manual_orders->updated_by = 1;
            $manual_orders->status = 'pending'; 
            $manual_orders->customers_id = Cookie::get('id');
            $save_status = $manual_orders->save();
            
            if($save_status == true)
            {  
                $this->ManualOrdersCoockie($manual_orders->id,$manual_orders->receiver_name,$manual_orders->receiver_number,$manual_orders->reciever_address,$manual_orders->images);
                return redirect()->route('customer.create')->with('success', 'Thank you! Screen shot uploaded Successfully');
            }
            else
            {
                echo 'error please upload again or inform brandhub with this error screen shot';
                dd($save_status);
            }
        }
             
    }
    
    public function ManualOrdersCoockie($order_id,$receiver_name,$receiver_number,$reciever_address,$images)
    {
        
        Cookie::queue('order_id', $order_id, $this->coockie_duration);
        Cookie::queue('receiver_name', $receiver_name, $this->coockie_duration);
        Cookie::queue('receiver_number', $receiver_number, $this->coockie_duration);
        Cookie::queue('reciever_address', $reciever_address, $this->coockie_duration);
        Cookie::queue('images', $images, $this->coockie_duration);
    }
    
    public function DeleteOrderImage(Request $request)
    {   
        // dd($request->delete_path,$request->images);
        $final_images = $request->images;
        if(file_exists($request->delete_path))
        {
            
            if(File::delete($request->delete_path))
            {
                
                $manual_orders = ManualOrders::find($request->order_id); 
                // dd($manual_orders);
                // dd($manual_orders,$request->images);
                $manual_orders->images = $final_images;
                $status =$manual_orders->save();
                // dd($manual_orders->save());
                if($status)
                {
                    // dd($final_images,$request->delete_path);
                    $this->ManualOrdersCoockie($manual_orders->id,$manual_orders->receiver_name,$manual_orders->receiver_number,$manual_orders->reciever_address,$final_images);
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
            // dd('w1');
            $manual_orders = ManualOrders::find($request->order_id); 
            // dd($manual_orders);
            $manual_orders->images = $final_images;
            $status =$manual_orders->save();
            if($status)
            {
                $this->ManualOrdersCoockie($manual_orders->id,$manual_orders->receiver_name,$manual_orders->receiver_number,$manual_orders->reciever_address,$final_images);
                return response()->json(['success'=>'1','messege' => 'Image not deleted but save successfully']); 
            }
            else
            {
                return response()->json(['error'=>'1','messege' => 'Order Image not  deleted']); 
            }
        }   
    }
    
    //
}
