<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;

class LoadSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        //
    }
    
    public function generate_load_sheet(Request $request)
    {
        //dd($request->all());
        $action_status = ManualOrders::whereIn('id',$request->order_ids)->update(['status' => 'dispatched', 'riders_id'=> $request->riders]);
        if($action_status)
        {
            return response()->json(['status' => '1', 'messege' => $action_status.' Parcels Status successfully updated']); 
        }
        else
        {
            return response()->json(['status' => '0', 'messege' => 'some thing went wrong']); 
        }
        // dd($action_status);
        // dd($request->order_ids[0]);
        
        
    }
}
