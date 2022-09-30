<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Riders;
use App\Models\Client\Customers;
use App\Models\Inventory;
use App\Models\Products;
use App\Models\Order_details;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function OrderFieldList()
    {
        return array('manual_orders.consignment_id','manual_orders.id','manual_orders.customers_id','manual_orders.description','manual_orders.receiver_number','customers.first_name','manual_orders.reciever_address','customers.last_name','customers.number','customers.address','manual_orders.price','manual_orders.images','manual_orders.total_pieces','manual_orders.date_order_paid','manual_orders.status','manual_orders.created_at','manual_orders.updated_at','manual_orders.status_reason'); 

    }
    
    public function index(Request $request)
    {
        $order_id = $request->search_order_id;
        $search_text = $request->search_text;
        $order_status = $request->order_status;
        //dd($request);
        if($order_id != '')
        {
            $search_test = $request->search_text;
            $order_status = $request->order_status;
            $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->where('manual_orders.id',$order_id)
            ->select($this->OrderFieldList())
            ->paginate(20);
        }
        else if($search_text != '')
        {
        $search_test = $request->search_text;
        
        $order_status = $request->order_status;
        $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')->
        where(function ($query) use ($search_test) {
            $query->where('customers.first_name','like',$search_test.'%')
                    ->orWhere('customers.first_name','like','%'.$search_test.'%')
                    ->orWhere('customers.first_name','like','%'.$search_test)
                    ->orWhere('customers.last_name','like',$search_test.'%')
                    ->orWhere('customers.last_name','like','%'.$search_test.'%')
                    ->orWhere('customers.last_name','like','%'.$search_test)
                    ->orWhere('customers.number','like','%'.$search_test) 
                    ->orWhere('customers.number','like',$search_test.'%')
                    ->orWhere('customers.number','like','%'.$search_test.'%')
                    ->orWhere('manual_orders.id','like','%'.$search_test.'%')
                    ->orWhere('manual_orders.consignment_id','like','%'.$search_test.'%');
            })->where('manual_orders.status','like',$order_status.'%')
            ->orderBy('manual_orders.id', 'ASC')
            ->select($this->OrderFieldList())
            ->paginate(20);
            
        }
        else if($order_status != '')
        {
            $query = Customers::query();
            
            $query = $query->rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id');
            if($order_status != 'all')
            {
                $query = $query->where('manual_orders.status',$order_status);
            } 
            $list = $query->orderBy('manual_orders.id', 'ASC')
            ->select($this->OrderFieldList())
            ->paginate(20); 
        }
        else
        {
            $list = Customers::rightJoin('manual_orders', 'manual_orders.customers_id', '=', 'customers.id')
            ->where('manual_orders.status','pending')
            ->orderBy('manual_orders.id', 'ASC')
            ->select($this->OrderFieldList())
            ->paginate(20);
        }
        //dd($list);
        return view('admin.inventory.manage_inventory')->with('list',$list); 
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
        $products = Products::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'created_by' => '1',
            'updated_by' => '1',
            'status' => 'active' 
            ]); 
        // $product_save = $products->save();
        // $user = User::create($user_inputs);

        $inventory = $products->inventory()->create([
        'sku' => $request->sku,
        'cost' => $request->cost,
        'sale' => $request->sale,
        'units' => $request->units,
        'onhand' => $request->units,
        'unit_type' => $request->unit_type,
        'created_by' => '1',
        'updated_by' => '1',
       'status' => 'active'
    ]);

        // $customers = new Inventory();
        // $customers->sku = $request->sku;
        // $customers->product_id = 1;
        // $customers->cost = $request->cost;
        // $customers->sale = $request->sale;
        // $customers->units = $request->units;
        // $customers->unit_type = $request->unit_type;
        // $customers->created_by = '1';
        // $customers->updated_by = '1';
        // $customers->status = 'active'; 
        // $status = $customers->save();
        if($inventory)
        {
            return response()->json(['messege' => 'successfully Created', 'status' => $inventory]); 
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
}
