<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;use PragmaRX\Countries\Package\Countries;
use App\Models\Client\ManualOrders;
use App\Models\User;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function __construct()
    {
        //dd();
        $this->middleware('auth:admin');
    }

    
    public function index(Request $request)
    { 
        //====================================================================
        //=================================================date from date to 
        //====================================================================
        
        $from_date= date('Y-m-01').' 00:00:00';
        $to_date = date('Y-m-t').' 23:59:59';
        
        if($request->date_from)
        {
            $from_date = $request->date_from.' 00:00:00';
            $to_date = $request->date_to.' 23:59:59';   
        }
        // dd($request->date_from , $request->date_to);
        // $users = DB::table('users')->select('id','first_name')->get(); 
        
        
        
        
        //====================================================================
        //================================================= Orders Details boxes
        //====================================================================
        $group_by_status = DB::table('manual_orders')
             ->select('status', DB::raw('count(*) as total_orders'), DB::raw('sum(price) as total_amount'))
             ->whereBetween('updated_at', [$from_date, $to_date])
             ->groupBy('status')
             ->get()->toArray(); 
         
        for($i=0; $i<sizeof($group_by_status); $i++)
        {
            $users_order_status = DB::table('manual_orders')
            ->select('assign_to as id', DB::raw('count(*) as total_orders'),DB::raw('sum(price) as total_amount'))
            ->leftJoin('users', 'manual_orders.assign_to', '=', 'users.id') 
            ->whereBetween('updated_at', [$from_date, $to_date])->where('status',$group_by_status[$i]->status)
            ->groupBy('assign_to')
            ->get()->toArray();  
            $group_by_status[$i]->users = $users_order_status; 
        } 
        
        
        
        
        //====================================================================
        //================================================= Orders Details boxes
        //====================================================================
             
        $users_totla_orders = DB::table('manual_orders')
        ->select('assign_to as id', DB::raw('count(*) as total_orders'),DB::raw('sum(price) as total_amount'))
        ->leftJoin('users', 'manual_orders.assign_to', '=', 'users.id') 
        ->whereBetween('updated_at', [$from_date, $to_date])
        ->groupBy('assign_to')->get();
        
        
        
        
        //====================================================================
        //================================================= Orders by shipment graph
        //====================================================================
            
        $orders_by_shipment_company = DB::table('manual_orders')
        ->select('shipment_company as id', DB::raw('count(*) as total_orders'),DB::raw('sum(price) as total_amount'))
        ->leftJoin('users', 'manual_orders.assign_to', '=', 'users.id') 
        ->whereBetween('updated_at', [$from_date, $to_date])->where('status','dispatched')
        ->groupBy('shipment_company')->get();
        // dd($orders_by_shipment_company);
        $shipment_companies = array();
        $total_shipment_orders = array();
        $shipment_cities_summary = array();
        foreach($orders_by_shipment_company as $city)
        {
            $shipment_cities_summary['shipment_cities_name'][] = $city->id;
            $shipment_cities_summary['shipment_cities_orders'][] = $city->total_orders;
        } 
        
        
        
        
        //====================================================================
        //================================================= Innventory
        //====================================================================
        $inventory = DB::table('inventories')
             ->select('stock_status', DB::raw('sum(qty) as qty'), DB::raw('sum(cost) as cost'), DB::raw('sum(sale) as sale'))
             ->whereBetween('updated_at', [$from_date, $to_date])
             ->groupBy('stock_status')
             ->get(); 

        $remaining_invertory = DB::table('remaining_inventories')
             ->select( DB::raw('sum(qty) as total'), DB::raw('sum(cost*qty) as amount'))
             ->get();
        
        
        
        
        //====================================================================
        //================================================= shipment tracking status
        //====================================================================  
        $total_city_orders = array();
        
        $order_report_by_leopord_cities_dispatched = ManualOrders::leftJoin('leopord_cities', 'manual_orders.cities_id', '=', 'leopord_cities.id')->
        where(['status'=>'dispatched','shipment_company'=>'leopord'])->
        where('cities_id','!=','0')->
        whereBetween('updated_at', [$from_date, $to_date])->
        select('leopord_cities.name', DB::raw('count(*) as total'))->
        groupBy('leopord_cities.name')->
        OrderBy('total','DESC')->
        limit(10)->
        get();
        
        $order_report_by_leopord_cities_return = ManualOrders::leftJoin('leopord_cities', 'manual_orders.cities_id', '=', 'leopord_cities.id')->
        where(['status'=>'return','shipment_company'=>'leopord'])->
        where('cities_id','!=','0')->
        whereBetween('updated_at', [$from_date, $to_date])->
        select('leopord_cities.name', DB::raw('count(*) as total'))->
        groupBy('leopord_cities.name')->
        OrderBy('total','DESC')->
        limit(10)->
        get();
        
        $order_report_by_leopord_cities_pending = ManualOrders::leftJoin('leopord_cities', 'manual_orders.cities_id', '=', 'leopord_cities.id')->
        where(['status'=>'pending','shipment_company'=>'leopord'])->
        where('cities_id','!=','0')->
        whereBetween('updated_at', [$from_date, $to_date])->
        select('leopord_cities.name', DB::raw('count(*) as total'))->
        groupBy('leopord_cities.name')->
        OrderBy('total','DESC')->
        limit(10)->
        get();
        
        
        
        
        //====================================================================
        //================================================= Local VS Other Cities
        //====================================================================   
        
        //Out Cities
        $order_report_by_leopord_cities_top_ten = ManualOrders::leftJoin('leopord_cities', 'manual_orders.cities_id', '=', 'leopord_cities.id')->
        where(['status'=>'dispatched','shipment_company'=>'leopord'])->
        where('cities_id','!=','0')-> 
        whereBetween('updated_at', [$from_date, $to_date])->
        select('leopord_cities.name', DB::raw('count(*) as total'))->
        groupBy('leopord_cities.name')->
        OrderBy('total','ASC')->
        limit(50)->
        get(); 
        
        //Merge  all cities shipment
        $shipment_cities_data=[]; 
        foreach($order_report_by_leopord_cities_top_ten as $city)
        {
            $shipment_cities_data[] = [
                'y'=> (int)$city->total, 
                'label'=> $city->name
            ];
             
        }  
        
        $shipment_cities_data = json_encode($shipment_cities_data);
        $shipment_cities_data = json_decode($shipment_cities_data);
        
        
        
        
        //====================================================================
        //================================================= Top Ten Cities Graph
        //====================================================================  
        
        //Out Cities
        $order_report_by_leopord_cities = ManualOrders::leftJoin('leopord_cities', 'manual_orders.cities_id', '=', 'leopord_cities.id')->
        where(['status'=>'dispatched','shipment_company'=>'leopord'])->
        where('cities_id','!=','0')-> 
        whereBetween('updated_at', [$from_date, $to_date])->
        select('leopord_cities.name', DB::raw('count(*) as total'))->
        groupBy('leopord_cities.name')->
        OrderBy('total','DESC')->
        limit(10)->
        get();
        
        //Out Local
        $cities_name = array();  
        $order_report_by_local_city = ManualOrders::select('shipment_company')->whereBetween('updated_at', [$from_date, $to_date])->where(['shipment_company' => 'local','status'=>'dispatched'])->get()->count(); 
        $cities_name[] = 'Local';
        $total_city_orders[] = $order_report_by_local_city; 
        
        //Merge  all cities shipment 
        foreach($order_report_by_leopord_cities as $city)
        {  
            $cities_name[] = $city->name;
            $total_city_orders[] = $city->total;
        } 
        
        
        
        
        //====================================================================
        //================================================= shipment tracking by status
        //==================================================================== 
        $shipment = DB::table('manual_orders')
        ->select('manual_orders.payment_status', DB::raw('count(*) as total' ), DB::raw('sum(price-fare) as amount'), DB::raw('(sum(orderpayments.amount) ) as t_amount'), DB::raw('sum(fare) as fare'))
        ->leftJoin('orderpayments', 'orderpayments.order_id', '=', 'manual_orders.id')
        ->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')
        ->whereBetween('manual_orders.updated_at', [$from_date, $to_date]) 
        ->where('manual_orders.consignment_id' ,'>','0') 
        ->groupBy('manual_orders.payment_status')
        ->get();
        
        
        $shipment_statuses = DB::table('manual_orders')
        ->select('shipment_tracking_status')
        ->groupBy('shipment_tracking_status')
            ->whereBetween('manual_orders.updated_at', [$from_date, $to_date])
            ->where('manual_orders.consignment_id' ,'>','0') 
        ->get();
        
        
        $statusfinal=[];
        foreach($shipment_statuses as  $shipment_statuses)
        { 
            $shipmenttrackings = DB::table('manual_orders')
            ->select('manual_orders.payment_status', DB::raw('count(*) as total' ), DB::raw('sum(price-fare) as amount'))
            ->leftJoin('orderpayments', 'orderpayments.order_id', '=', 'manual_orders.id')
            ->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')
            ->whereBetween('manual_orders.updated_at', [$from_date, $to_date])
            ->where('manual_orders.consignment_id' ,'>','0') 
            ->where('manual_orders.shipment_tracking_status' ,'=',$shipment_statuses->shipment_tracking_status) 
            ->groupBy('manual_orders.payment_status')
            ->get();
            
            //dd($shipmenttrackings);
            foreach($shipmenttrackings as  $shipmenttracking)
            { 
                $statusfinal[$shipment_statuses->shipment_tracking_status][] = $shipmenttracking; 
            } 
             
        }
        // dd($statusfinal);
        // $shipmenttracking = DB::table('manual_orders')
        // ->select('manual_orders.shipment_tracking_status','manual_orders.payment_status', DB::raw('count(*) as total' ), DB::raw('sum(price-fare) as amount'), DB::raw('sum(orderpayments.amount) as t_amount'))
        // ->leftJoin('orderpayments', 'orderpayments.order_id', '=', 'manual_orders.id')
        // ->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')
        // ->whereBetween('manual_orders.created_at', [$from_date, $to_date])
        // ->where('manual_orders.consignment_id' ,'>','0')
        // ->groupBy('manual_orders.shipment_tracking_status')
        // ->groupBy('manual_orders.payment_status')
        // ->get();
        // dd($shipmenttracking);
        //     $shipment = DB::table('manual_orders')
        //     ->select('manual_orders.payment_status',DB::raw('sum(orderpayments.amount) as amount'))
        //     ->leftJoin('orderpayments', 'orderpayments.order_id', '=', 'manual_orders.id')
        //     ->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')
        //     ->whereBetween('manual_orders.created_at', [$from_date, $to_date])
        //     ->where('manual_orders.payment_status' ,'!=','')
        //     ->groupBy('manual_orders.payment_status')
        //     ->get();
          
        //   dd($shipment);
        //if ($result->count()) { }
        // dd($total_shipment_orders); 
         
        return view('admin.dashboard')->with([
            'data'=>$group_by_status,
            'shipment'=>$shipment,
            'shipmenttracking'=>$statusfinal,
            'date_from'=> $from_date,
            'date_to'=>$to_date,
            'remaining_invertory'=>$remaining_invertory,
            'inventories'=>$inventory,
            'cities_name'=>$cities_name,
            'total_city_orders'=>$total_city_orders,
            'users_totla_orders'=>$users_totla_orders,
            'orders_by_shipment_company'=>$orders_by_shipment_company,
            'shipment_cities_summary'=>$shipment_cities_summary, 
            'shipment_cities_data'=>$shipment_cities_data,
            'from_date'=>$from_date,
            'to_date'=>$to_date,
            ]);
        // return view('admin.dashboard');
        
        
        
        
        
        
    }
}
