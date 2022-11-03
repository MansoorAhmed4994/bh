<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;use PragmaRX\Countries\Package\Countries;
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
        // dd($request->date_from);
        $from_date= date('Y-m-01');
        $to_date = date('Y-m-t');
        // dd( $from_date.' '.$to_date);
        if($request->date_from)
        {
            // dd($request->date_from.'  '.$request->date_to);
            // $from_date = ;
            $from_date = $request->date_from;
            $to_date = $request->date_to;  
            // dd($from_date.'  '.$to_date);
        }
        // else
        // { 
        //     $from_date = Carbon::now()->subDays(30)->toDateTimeString();
        //     $to_date = Carbon::now()->addDays(5)->toDateTimeString(); 
        // }
         
            $list = DB::table('manual_orders')
                 ->select('status', DB::raw('count(*) as total'), DB::raw('sum(price) as amount'))
                 ->whereBetween('created_at', [$from_date, $to_date])
                 ->groupBy('status')
                 ->get(); 
                 
            
        
        
            $shipment = DB::table('manual_orders')
            ->select('manual_orders.payment_status', DB::raw('count(*) as total' ), DB::raw('sum(price-fare) as amount'), DB::raw('(sum(orderpayments.amount) ) as t_amount'), DB::raw('sum(fare) as fare'))
            ->leftJoin('orderpayments', 'orderpayments.order_id', '=', 'manual_orders.id')
            ->leftJoin('customers', 'customers.id', '=', 'manual_orders.customers_id')
            ->whereBetween('manual_orders.created_at', [$from_date, $to_date]) 
            ->where('manual_orders.consignment_id' ,'>','0') 
            ->groupBy('manual_orders.payment_status')
            ->get();
            
            $shipment_statuses = DB::table('manual_orders')
            ->select('shipment_tracking_status')
            ->groupBy('shipment_tracking_status')
                ->whereBetween('manual_orders.created_at', [$from_date, $to_date])
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
                ->whereBetween('manual_orders.created_at', [$from_date, $to_date])
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
        return view('admin.dashboard')->with(['data'=>$list,'shipment'=>$shipment,'shipmenttracking'=>$statusfinal,'date_from'=> $from_date,'date_to'=>$to_date]);
        // return view('admin.dashboard');
    }
}
