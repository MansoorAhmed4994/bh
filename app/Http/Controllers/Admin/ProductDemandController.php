<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

use App\Models\ProductDemand;

class ProductDemandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = ProductDemand::select('*')->paginate(50);
        return view('admin.product_demand.list')->with(['list'=>$list]);
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
        $ProductDemand=ProductDemand::find($id);
        $status = $ProductDemand->delete(); 
        if($status)
        {
            return response()->json(['success'=>'1','messege' => 'successfully deleted']); 
        }
        else
        {
            return response()->json(['error'=>'1','messege' => 'successfully deleted']); 
        }
        
        // dd($id);
        //
    }
    
    public function create_product_demand(Request $request)
    {
        // dd($request->img_src);
        $product_demand = new ProductDemand;
        $product_demand->name = $request->category;
        $product_demand->image = $request->img_src;
        $product_demand->ref_id = $request->order_id;
        $product_demand->table_name = 'manual_orders';
        $product_demand->created_by = Auth::id();
        $product_demand->updated_by = Auth::id();
        $product_demand->status = 'pending';
        
        $status = $product_demand->save(); 
        
        
        if($status)
        {
            return response()->json(['success'=>'1','messege' => 'Demand successfully Created']); 
        }
        else
        {
            return response()->json(['error'=>'1','messege' => 'Demand not created']); 
        }
        //
    }
}
