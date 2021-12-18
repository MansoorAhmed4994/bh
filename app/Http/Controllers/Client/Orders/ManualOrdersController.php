<?php

namespace App\Http\Controllers\Client\orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\client\orders\ManualOders;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class ManualOrdersController extends Controller
{ 

    private $images_path =  'storage/images/orders/manual-orders/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        
        //$this->middleware('auth');
    }

    public function index()
    {
        $list = ManualOders::where('status','pending')->orderBy('created_at', 'DESC')->paginate(5);
        // $list = $list->all();
        // dd($list->all());
        return view('client.orders.manual-orders.list')->with('list',$list);
        
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $validated = $request->validate([

            'images' => 'required',
            'first_name' => 'required',
            'number' => 'required',
            'address1' => 'required',

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

       // dd();
        $manual_orders = new ManualOders();
        $manual_orders->first_name = $request->first_name;
        $manual_orders->last_name = $request->first_name;
        $manual_orders->receiver_name = $request->number;
        $manual_orders->receiver_number = $request->number;
        $manual_orders->number = $request->number;
        $manual_orders->whatsapp_number = $request->number;
        $manual_orders->address1 = $request->address1;
        $manual_orders->status = 'pending';
        $manual_orders->images = implode("|",$images);
        $manual_orders->created_by = Auth::id();
        $manual_orders->updated_by = Auth::id();
        $manual_orders->save();
        /*Insert your data*/

        // Detail::insert( [
        //     'images'=>  implode("|",$images),
        //     'description' =>$input['description'],
        //     //you can put other insertion here
        // ]);


        return redirect()->route('ManualOrders.create')->with('success', 'Order Successfully placed');
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
    public function edit(ManualOders $ManualOrder)
    {
        
        // dd($ManualOrder); 
        if($ManualOrder) 
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
    public function update(Request $request, ManualOders $ManualOrder)
    {
        
        $validated = $request->validate([
 
            'first_name' => 'required',
            'number' => 'required',
            'address1' => 'required',

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
        
        
        // $manual_orders = new ManualOders();
        $ManualOrder->first_name = $request->first_name;
        $ManualOrder->last_name = $request->last_name;
        $ManualOrder->receiver_name = $request->receiver_name;
        $ManualOrder->receiver_number = $request->number;
        $ManualOrder->number = $request->number;
        $ManualOrder->whatsapp_number = $request->number;
        $ManualOrder->address1 = $request->address1;
        $ManualOrder->status = 'pending';
        if($images != null)
        {
            $ManualOrder->images = $ManualOrder->images.'|'.(implode("|",$images));
            
        }      
        $ManualOrder->created_by = Auth::id();
        $ManualOrder->updated_by = Auth::id(); 
        $ManualOrder->save();

        return redirect()->route('ManualOrders.index')->with('success', 'Order Successfully placed');
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

    public function delete_order_image(Request $request)
    { 
        if(File::delete($request->delete_path))
        {
            $manual_orders = ManualOders::find($request->order_id); 
            $manual_orders->images = $request->images;
            $manual_orders->save(); 
            //dd($satus);
            return response()->json(['messege' => 'successfully deleted']); 
        }  
        else
        {
            return 'Some thing went wrong';
        }   
    }
 
}
