<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//Models
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;
use App\Models\Support;

//Traits
use App\Traits\CustomerTraits; 

class SupportController extends Controller
{
    use CustomerTraits;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function index()
    {
            $support =  Support::select('*')->paginate(20); 
         return view('support.create')->with(['list'=>$support]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('frontend.support.create');
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
        
        if($request->customers_id == '')
        { 
            $data = array([
                'first_name' => $request['first_name'], 
                'last_name' => $request['last_name'], 
                'address' => $request['address'], 
                'number' => $request['number'], 
                'whatsapp_number' => $request['whatsapp_number'], 
                'created_by' => 37, 
                'updated_by' => 37, 
                'loyality_count' => 0, 
                'status' => 'active', 
                ]);
            $status = $this->CreateCustomer($data);
            // dd($status);
            $customers_id = '';
            if($status['error'] == 0)
            {
                $customers_id = $status['data']->id;
            }
            else
            {
                $customers_id = $status['data'];
            }
            
            $data = array([
                'customers_id' => $customers_id,
                'order_id' => $request->order_id,
                'ticket_type' => $request->ticket_type,
                'msg' => $request->msg,
                'images' => $request->images,
                'status' => 'active'
                ]);
            $status = $this->CreateSupportTicket($data,$request);
            
            if($status)
            { 
                toastr()->success('Ticket has been saved successfully!');
                return back(); 
            }
            else
            { 
                toastr()->error('error! send this screenshot to brandhub whatsapp 03330139993');
                return back(); 
            }
            
        }
        else
        {
            $data = array([
                'customers_id' => $customers_id,
                'order_id' => $request->order_id,
                'ticket_type' => $request->ticket_type,
                'msg' => $request->msg,
                'images' => $request->images,
                'status' => 'active'
                ]);
                
            $status = $this->CreateSupportTicket($data,$request);
                
            if($status)
            { 
                toastr()->success('Ticket has been saved successfully!');
                return back(); 
            }
            else
            { 
                toastr()->error('error! send this screenshot to brandhub whatsapp 03330139993');
                return back(); 
            }
        }
        
        
        //
    }
    
    public function CreateSupportTicket($data,$request)
    {
        // dd($data[0]);
        $images=array();
            if($files=$request->file('images')){
                foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    
                    $file->move($this->images_path,$name);
                    $images[]=$this->images_path.$name;
                }
            }
            
        $support = new Support();
        $support->customers_id = $data[0]['customers_id'];
        $support->order_id = $data[0]['order_id'];
        $support->ticket_type = $data[0]['ticket_type'];
        $support->msg = $data[0]['msg'];
        $support->images = implode("|",$images);
        $support->status = $data[0]['status'];
        $support->created_by = 37;
        $support->updated_by = 37;
        $status = $support->save();
        
        return $status; 
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
    
    public function CustomerDetails(Request $request)
    { 
        $query = Customers::query();
        $number = $request->number;
        $query = $query->where('customers.number','like','%'.$number); 
        
        if($query->count() >= 1)
        {
            return response()->json([ 
                'field_values'=> $query->first(),  
                ]);
            
        }
        else
        { 
            return response()->json([ 
                'error'=> 1,  
                ]);
        }
        
    }
}
