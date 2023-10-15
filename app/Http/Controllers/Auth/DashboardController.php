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
    public function index()
    { 
        // $list = ManualOrders::where('status', 'pending');
        if(Gate::denies('users-pages')) 
        {
            return redirect('login');
            // somehow this seems te get ignored because the unaccessible view gets displayed anyway.
        }
        $from_date= date('Y-m-01');
        $to_date = date('Y-m-t');
        // dd( $from_date.' '.$to_date);
        if(isset($request->date_from))
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
        // $query = DB::table('remaining_inventories')::query();
        // $query = $query->select( DB::raw('sum(qty) as total'), DB::raw('sum(cost*qty) as amount'));
        // $query = $query->whereBetween('updated_at', [$from_date, $to_date])
        //          ->groupBy('stock_status')
        //          ->get();
        //          $remaining_invertory = $query;
                 
        $remaining_invertory = DB::table('remaining_inventories')
                 ->select( DB::raw('sum(qty) as total'), DB::raw('sum(cost*qty) as amount'))
                //  ->where('assign_to',Auth::id())
                //  ->whereBetween('created_at', [$from_date, $to_date])
                 ->get();   
                 
        // $user_id = User::find(auth()->user()->id); 
        // $user_roles = $user_id->roles()->get()->pluck('name')->toArray(); 
        
        
        // if(in_array('author', $user_roles) || in_array('admin', $user_roles))
        // {  
            
        // } 
        // elseif(in_array('user', $user_roles))
        // {  
        //     $query = $query->where("manual_orders.updated_by" ,$user_id->id);
        // }
        //          $query = $query->whereBetween('updated_by', )
        //          ->get(); 
        
        
            $inventory = DB::table('inventories')
                 ->select('stock_status', DB::raw('sum(qty) as qty'), DB::raw('sum(cost) as cost'), DB::raw('sum(sale) as sale'))
                 ->whereBetween('updated_at', [$from_date, $to_date])
                 ->groupBy('stock_status')
                 ->get(); 
                 
                
            // Orders Dashboard Tab
            $orders_dashboard_query = ManualOrders::query();
            $orders_dashboard_query = $orders_dashboard_query->select('status', DB::raw('count(*) as total_orders'), DB::raw('sum(price) as total_amount'))->whereBetween('updated_at', [$from_date, $to_date]);
            $user_id = User::find(auth()->user()->id);
            $user_roles = $user_id->roles()->get()->pluck('name')->toArray();
            if(in_array('author', $user_roles) || in_array('admin', $user_roles))
            { 
                // dd('');
            } 
            elseif(in_array('user', $user_roles))
            {
                $orders_dashboard_query = $orders_dashboard_query->where('assign_to',Auth::id());
            }
            
                 
             $orders_dashboard_query = $orders_dashboard_query->groupBy('status')->get(); 
                 
            $order_report_by_cities = ManualOrders::leftJoin('cities', 'manual_orders.cities_id', '=', 'cities.id')->
            select('cities.name', DB::raw('count(*) as total'))
            ->whereBetween('updated_at', [$from_date, $to_date])
            ->groupBy('cities.name')->havingRaw('COUNT(*) > 10')->get();
            
            $cities_name = array();
            $total_city_orders = array();
            // $total_orders=[];
            foreach($order_report_by_cities as $city)
            {
                $cities_name[] = $city->name;
                $total_city_orders[] = $city->total;
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
        return view('auth.user.dashboard')->with([
            'data'=>$orders_dashboard_query,
            'shipment'=>$shipment,
            'shipmenttracking'=>$statusfinal,
            'date_from'=> $from_date,
            'date_to'=>$to_date,
            'remaining_invertory'=>$remaining_invertory,
            'inventories'=>$inventory,
            'cities_name'=>$cities_name,
            'total_city_orders'=>$total_city_orders,
            ]);
        // return view('admin.dashboard');
        // $users = User::all();
        // return view('auth.user.dashboard')->with('users',$users);
    }
} 
