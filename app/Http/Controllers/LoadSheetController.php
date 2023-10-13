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
        // dd($request->order_ids);
        $error_messege = array(); 
        foreach($request->order_ids as $order_id)
        {
            if(check_customer_advance_payment($order_id) > 0)
            {
                
                array_push($error_messege, 'Payment Not Approved for id #: '.$order_id); 
            }
            else
            {
                // echo $order_id;
            }
        }
        // dd(sizeof($error_messege));
        if(sizeof($error_messege) > 0)
        { 
            return response()->json(['error' => 1, 'messege' => $error_messege]); 
        }
        $action_status = ManualOrders::whereIn('id',$request->order_ids)->update(['status' => 'dispatched', 'riders_id'=> $request->riders]);
        if($action_status)
        {
            return response()->json(['success' => '1', 'messege' => $action_status.' Parcels Status successfully updated']); 
        }
        else
        {
            
            return response()->json(['error' => '1', 'messege' => 'Dispatche status not updated and rider not added','success'=>'0','details'=>$action_status]); 
        }
        // dd($action_status);
        // dd($request->order_ids[0]);
        
        
    }
}
