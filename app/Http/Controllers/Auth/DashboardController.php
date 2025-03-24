<?php

namespace App\Http\Controllers\Auth; 
use App\Http\Controllers\Controller;  
use App\Traits\ManualOrderTraits;
use Illuminate\Http\Request;
use App\Models\Client\ManualOrders;
use App\Models\Client\Customers;
use App\Models\User;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use DB;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;


class DashboardController extends Controller
{ 
    public function __construct()
    {
        $this->middleware('auth:user');

       
    }

   
    //
    public function index(Request $request)
    {  
        if(Gate::denies('users-pages')) 
        {
            return redirect('login'); 
        }
        
        
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
        
        
        //====================================================================
        //================================================= Orders Details
        //====================================================================  
        $orders_dashboard_query = ManualOrders::query();
        $orders_dashboard_query = $orders_dashboard_query->select('status', DB::raw('count(*) as total_orders'), DB::raw('sum(price) as total_amount'))->whereBetween('updated_at', [$from_date, $to_date]);
        $user_id = User::find(auth()->user()->id);
        $user_roles = $user_id->roles()->get()->pluck('name')->toArray();
        
        // dd($user_roles);
        if( in_array('admin', $user_roles))
        { 
            // dd('working');
        }  
        elseif(in_array('author', $user_roles))
        {
            $orders_dashboard_query = $orders_dashboard_query->where('status', '!=', 'dispatched'); 
        }
        elseif(in_array('calling', $user_roles))
        {
            $orders_dashboard_query = $orders_dashboard_query->where('status', '!=', 'pending');
            $orders_dashboard_query = $orders_dashboard_query->where('assign_to',Auth::id());
        }
        elseif(in_array('user', $user_roles))
        {
            $orders_dashboard_query = $orders_dashboard_query-> where(function($query) {
                $query->where('created_by', Auth::id())
                ->orWhere('updated_by', Auth::id())
                ->orWhere('status', Auth::id());
                });
        }    
        $orders_dashboard_query = $orders_dashboard_query->groupBy('status')->get();
        
        
        //====================================================================
        //================================================= Graph Daily Performance
        //==================================================================== 
        $query = ManualOrders::query();
        $result = $query->selectRaw('sum(price) as amount, DATE(updated_at) as date')
            ->whereBetween('updated_at', [$from_date, $to_date])
            ->where('status', 'dispatched');
        
        if( in_array('admin', $user_roles))
        {  
            $query = $query->where('status', 'dispatched');
        }  
        elseif(in_array('author', $user_roles))
        {
            $query = $query->where('status', 'dispatched'); 
        }
        elseif(in_array('calling', $user_roles))
        {
            $query = $query->where('status', 'dispatched');
            $query = $query->where('assign_to',Auth::id());
        }
        elseif(in_array('user', $user_roles))
        {
            $query = $query-> where(function($query) {
                $query->where('created_by', Auth::id())
                ->orWhere('updated_by', Auth::id())
                ->orWhere('status', Auth::id());
                });
        } 
            
            
        $result = $query->groupBy('date')->orderby('date','DESC')->get()
            ->map(function ($item) {
                return [
                    'date'  => date('d M', strtotime($item->date)),
                    'total' => $item->amount,
                ];
            });
            
            
            // dd($result);
        $daily_performance_date = array();
        $daily_performance_amount = array();
        // $total_orders=[];
        foreach($result as $results)
        {
            // dd($results['date']);
            $daily_performance_date[] = $results['date'];
            $daily_performance_amount[] = $results['total'];
        }
        
    
    
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
             
        return view('auth.user.dashboard')->with([
            'data'=>$orders_dashboard_query,
            'shipment'=>$shipment,
            'shipmenttracking'=>$statusfinal,
            'date_from'=> $from_date,
            'date_to'=>$to_date,
            'daily_performance_date'=> $daily_performance_date,
            'daily_performance_amount'=> $daily_performance_amount
            // 'remaining_invertory'=>$remaining_invertory,
            // 'inventories'=>$inventory, 
            ]); 
    }
} 
