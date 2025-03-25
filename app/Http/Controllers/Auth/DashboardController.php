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
        //========================== date from date to =======================
        //====================================================================
        
        $from_date= date('Y-m-01').' 00:00:00';
        $to_date = date('Y-m-t').' 23:59:59';
        
        if($request->date_from)
        {
            $from_date = $request->date_from.' 00:00:00';
            $to_date = $request->date_to.' 23:59:59';   
        }   
        
        
        //====================================================================
        //===================== Orders Details by Status =====================
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
        
        
        //==========================================================================
        //=========================== Graph Daily Performance ======================
        //==========================================================================
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
            
        $daily_performance_date = array();
        $daily_performance_amount = array();
        // $total_orders=[];
        foreach($result as $results)
        {
            // dd($results['date']);
            $daily_performance_date[] = $results['date'];
            $daily_performance_amount[] = $results['total'];
        }
        
        
        
        //====================================================================
        //======================= CALLING TEAM PERFORMANCE ===================
        //====================================================================  
        $calling_team_performance_data = DB::table('manual_orders')
        ->select('assign_to as id','users.first_name as name', DB::raw('count(*) as total_orders'),DB::raw('sum(price) as total_amount'))
        ->leftJoin('users', 'manual_orders.assign_to', '=', 'users.id') 
        ->whereBetween('updated_at', [$from_date, $to_date])
        ->groupBy('assign_to','name')->orderby('total_amount','ASC')->get(); 
            
        
        // dd($calling_team_performance_data);
        // dd($calling_team_performance_data->pluck('name'));    
        $calling_team_performance_name = array();
        $calling_team_performance_amount = array();
        // $total_orders=[];
        foreach($calling_team_performance_data as $results)
        {
            // dd($results['date']);
            $calling_team_performance_name[] = $results->name;
            $calling_team_performance_amount[] = $results->total_amount;
        }
        
             
        return view('auth.user.dashboard')->with([
            'data'=>$orders_dashboard_query,  
            'date_from'=> $from_date,
            'date_to'=>$to_date,
            'daily_performance_date'=> $daily_performance_date,
            'daily_performance_amount'=> $daily_performance_amount ,
            'calling_team_performance_name' => $calling_team_performance_name,
            'calling_team_performance_amount' => $calling_team_performance_amount,
            'calling_team_performance_data' => $calling_team_performance_data
            ]); 
    }
} 
