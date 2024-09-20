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
        $from_date= date('Y-m-01', strtotime('-30 days'));
        $to_date = date('Y-m-t');

        // dd(date('Y-m-01', strtotime('-30 days')));         
        if($request->date_from)
        {
            $from_date = $request->date_from;
            $to_date = $request->date_to;  
            
            
        }
        // dd($request->date_from , $request->date_to);
        $users = DB::table('users')->select('id','first_name')->get();
            
            
            
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

        $remaining_invertory = DB::table('remaining_inventories')
             ->select( DB::raw('sum(qty) as total'), DB::raw('sum(cost*qty) as amount'))
             ->get();
             
        $users_totla_orders = DB::table('manual_orders')
        ->select('assign_to as id', DB::raw('count(*) as total_orders'),DB::raw('sum(price) as total_amount'))
        ->leftJoin('users', 'manual_orders.assign_to', '=', 'users.id') 
        ->whereBetween('updated_at', [$from_date, $to_date])
        ->groupBy('assign_to')->get();
            
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
        $inventory = DB::table('inventories')
             ->select('stock_status', DB::raw('sum(qty) as qty'), DB::raw('sum(cost) as cost'), DB::raw('sum(sale) as sale'))
             ->whereBetween('updated_at', [$from_date, $to_date])
             ->groupBy('stock_status')
             ->get(); 
             
        
        
        $cities_name = array();
        $total_city_orders = array();
        
        
        $order_report_by_leopord_cities = ManualOrders::leftJoin('leopord_cities', 'manual_orders.cities_id', '=', 'leopord_cities.id')->
        where(['status'=>'dispatched','shipment_company'=>'leopord'])->
        where('cities_id','!=','0')->
        whereBetween('updated_at', [$from_date, $to_date])->
        select('leopord_cities.name', DB::raw('count(*) as total'))->
        groupBy('leopord_cities.name')->
        OrderBy('total','DESC')->
        limit(20)->
        get();
        
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
        
        $res= DB::table('manual_orders')
        ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')->
        whereBetween('updated_at', [$from_date, $to_date])
        ->groupByRaw('MONTH(created_at)')
        ->whereYear('created_at', date('M'))
        ->get();

        
        
        
        $order_report_by_local_city = ManualOrders::
        select('shipment_company')
        ->whereBetween('updated_at', [$from_date, $to_date])->where('shipment_company','=','local')->get()->count();
        
        // dd($order_report_by_local_city->);
        
        
        // dd($order_report_by_local_city);
        $cities_name[] = 'Local';
        $total_city_orders[] = $order_report_by_local_city;
        
        
        $shipment_cities_data=[]; 
         foreach($order_report_by_leopord_cities as $city)
        {
            $shipment_cities_data[]=  [
			 'y'=> (int)$city->total, 
			 'label'=> $city->name 
		        ];
            $cities_name[] = $city->name;
            $total_city_orders[] = $city->total;
        }
        
    
    // dd($from_date, $to_date);
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
        // dd($shipment_statuses);
        
        $statusfinal=[];
        foreach($shipment_statuses as  $shipment_statuses)
        {
            //$statusfinal[] = $shipment_statuses->shipment_tracking_status;
            // echo $shipment_statuses->shipment_tracking_status;
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
                // array_push($statusfinal, $shipmenttrackings);
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
        // dd(json_encode($shipment_cities_data));
        $shipment_cities_data = json_encode($shipment_cities_data);
        $shipment_cities_data = json_decode($shipment_cities_data);
        // dd(json_encode($shipment_cities_data));
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
            ]);
        // return view('admin.dashboard');
        
        
        
        
        
        
    }
}
