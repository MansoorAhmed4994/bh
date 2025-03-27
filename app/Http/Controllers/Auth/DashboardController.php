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
// use Carbon\Carbon;
// use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use DB;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;


class DashboardController extends Controller
{ 
    public function __construct()
    {
        $this->middleware('auth:user');

       
    }

    private  $backgroundColor =  [
                'rgba(255, 99, 132, 0.5)',   // Red
                'rgba(255, 159, 64, 0.5)',   // Orange
                'rgba(255, 205, 86, 0.5)',   // Yellow
                'rgba(75, 192, 192, 0.5)',   // Teal
                'rgba(54, 162, 235, 0.5)',   // Blue
                'rgba(153, 102, 255, 0.5)',  // Purple
                'rgba(201, 203, 207, 0.5)',  // Gray
            
                // Added more attractive colors
                'rgba(0, 255, 127, 0.5)',    // Spring Green
                'rgba(255, 140, 0, 0.5)',    // Dark Orange
                'rgba(220, 20, 60, 0.5)',    // Crimson
                'rgba(0, 191, 255, 0.5)',    // Deep Sky Blue
                'rgba(186, 85, 211, 0.5)',   // Medium Orchid
                'rgba(72, 61, 139, 0.5)',    // Dark Slate Blue
                'rgba(173, 255, 47, 0.5)',   // Green Yellow
                'rgba(250, 128, 114, 0.5)'   // Salmon
            ];
    private  $borderColor = [
                'rgb(255, 0, 0)',      // Strong Red for contrast
                'rgb(204, 85, 0)',     // Deep Orange
                'rgb(204, 153, 0)',    // Dark Yellow
                'rgb(0, 128, 128)',    // Dark Teal
                'rgb(0, 77, 153)',     // Dark Blue
                'rgb(102, 0, 204)',    // Deep Purple
                'rgb(128, 128, 128)',  // Dark Gray
                
                // Contrasting borders for additional colors
                'rgb(0, 128, 64)',     // Dark Green
                'rgb(153, 76, 0)',     // Deep Orange-Brown
                'rgb(139, 0, 0)',      // Dark Red
                'rgb(0, 0, 153)',      // Deep Navy Blue
                'rgb(102, 0, 153)',    // Deep Violet
                'rgb(47, 0, 77)',      // Dark Purple
                'rgb(102, 153, 0)',    // Olive Green
                'rgb(153, 51, 51)'     // Dark Salmon
            ];
   
    //
    public function index(Request $request)
    {  
        $calling_team_list = '';
        if(Gate::denies('users-pages')) 
        {
            return redirect('login'); 
        }
        $permornace_type = 'Team';
        
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
        //======================= CALLING TEAM achieved ===================
        //====================================================================  
        $calling_team_achieved_data = DB::table('manual_orders')
        ->select('assign_to as id','users.first_name as name', DB::raw('count(*) as total_orders'),DB::raw('sum(price) as total_amount'))
        ->leftJoin('users', 'manual_orders.assign_to', '=', 'users.id') 
        ->whereBetween('updated_at', [$from_date, $to_date])
        ->whereNotNull('assign_to')
        ->groupBy('assign_to','name')->orderby('total_amount','ASC')->get(); 
        
        
        // dd($calling_team_achieved_data->pluck('name'));    
        $calling_team_achieved_name = array();
        $calling_team_achieved_amount = array();
        // $total_orders=[];
        foreach($calling_team_achieved_data as $results)
        {
            // dd($results['date']);
            $calling_team_achieved_name[] = $results->name;
            $calling_team_achieved_amount[] = $results->total_amount;
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
            $calling_team_list = $calling_team_achieved_data; 
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
            $permornace_type = Auth::user()->first_name;
            
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
        //=========================== Graph Daily achieved ======================
        //========================================================================== 
        $team_performance = array(); 
        $TeamDailyPerformance=[];
        $query = ManualOrders::query();
        if( in_array('admin', $user_roles))
        {   
            // $assign_to_team = DB::table('manual_orders')
            //     ->select('assign_to as id','users.first_name as name', DB::raw('count(*) as total_orders'),DB::raw('sum(price) as total_amount')) 
            //     ->leftJoin('users', 'manual_orders.assign_to', '=', 'users.id')
            //     ->whereBetween(DB::raw('DATE(updated_at)'), [$from_date, $to_date])
            //     ->where('status', 'dispatched') 
            //     ->groupBy('assign_to','name')->get(); 
                
            // // $result_dates = ManualOrders::query();
            // // $result_dates = $result_dates->selectRaw('sum(price) as amount, DATE(updated_at) as update_at') 
            // //     ->whereBetween(DB::raw('DATE(updated_at)'), [$from_date, $to_date])
            // //     ->where('status', 'dispatched') 
            // //     ->groupBy('update_at')->orderby('update_at','DESC')->get(); 
            
            $start = Carbon::parse($from_date);
            $end = Carbon::parse($to_date); 
            $dates = new Collection();
            for ($date = $start; $date->lte($end); $date->addDay()) {
                $dates->push($date->toDateString());
            } 
            
            $TeamDailyPerformance = 
            [
                'labels'=>$dates,
                'backgroundColor'=> '#9BD0F5',
                'datasets' => [],
            ];  
            
            // foreach($dates as $date)
            // {      
            //     foreach($assign_to_team as $team_result_key)
            //     {   
            //         $team_result = DB::table('manual_orders')
            //         ->select(DB::raw('sum(price) as total_amount'))
            //         ->whereBetween('updated_at', [$date.' 00:00:00', $date.' 23:59:59'])
            //         ->where('status', 'dispatched') 
            //         ->where('assign_to', $team_result_key->id)->get(); 
            //         if(is_null($team_result->first()->total_amount))
            //         {
            //             $team_performance['amount'][$team_result_key->name][] =  0;     
            //         }
            //         else
            //         {
            //             $team_performance['amount'][$team_result_key->name][] =  $team_result->first()->total_amount; 
            //         } 
            //     } 
                
            // } 
            
            // $BgColorLoop = 0;
            // foreach($team_performance['amount'] as $team_performances => $key)
            // { 
            //     $TeamDailyPerformance['datasets'][] = [
            //             'backgroundColor' => $this->backgroundColor[$BgColorLoop],
            //             'label' => $team_performances, 
            //             'data' => $key, 
            //             'fill' => true,
            //             'borderColor' => $this->borderColor[$BgColorLoop],
            //             'tension' => 0.1, 
            //         ]; 
            //     $BgColorLoop++;
            // } 
        }  
        elseif(in_array('calling', $user_roles))
        {
            
            $team_result = DB::table('manual_orders')
            ->selectRaw('sum(price) as amount, DATE(updated_at) as update_at') 
            ->whereBetween('updated_at', [$from_date, $to_date])
            ->where('status', 'dispatched') 
            ->where('assign_to', Auth::id())
            ->groupBy('update_at')
            ->orderby('update_at','DESC')
            ->get(); 
            
            $TeamDailyPerformance = 
            [
                'labels'=>$team_result->pluck('update_at'),
                // 'backgroundColor'=> '#9BD0F5',
                'datasets' => [],
            ];   
            
            $TeamDailyPerformance['datasets'][] = [
                'label'=>Auth::user()->first_name,  
                'data' => $team_result->pluck('amount')->toArray(),
                'fill' => true,
                'borderColor' => '#f75f5f',
                'tension' => 0.1, 
                'backgroundColor' => 'rgba(75, 192, 1,0.5)', 
            ];  
        }
        elseif(in_array('user', $user_roles) || in_array('author', $user_roles))
        {
            
            $team_result = DB::table('manual_orders')
            ->selectRaw('sum(price) as amount, DATE(updated_at) as update_at') 
            ->whereBetween('updated_at', [$from_date, $to_date])
            ->where('status', 'dispatched') 
            ->where(function($query) {
                $query->where('created_by', Auth::id())
                ->orWhere('updated_by', Auth::id());
                })
            ->groupBy('update_at')
            ->orderby('update_at','DESC')
            ->get(); 
            
            $TeamDailyPerformance = 
            [
                'labels'=>$team_result->pluck('update_at'),
                'backgroundColor'=> '#9BD0F5',
                'datasets' => [],
            ];   
            
            $TeamDailyPerformance['datasets'][] = [
                'backgroundColor' => $this->backgroundColor[0], 
                'data' => $team_result->pluck('amount')->toArray(),
                'label'=>Auth::user()->first_name, 
                'fill' => true,
                'borderColor' => $this->borderColor[0],
                'borderWidth'=> 1,
                'tension' => 0.1, 
                'onClick'=> '(e, activeEls) => {getdatadb()}',
            ]; 
             
        }  
        
 
        return view('auth.user.dashboard')->with([
            'data'=>$orders_dashboard_query,  
            'date_from'=> $from_date,
            'date_to'=>$to_date,
            'TeamDailyPerformance'=> $TeamDailyPerformance, 
            'calling_team_achieved_name' => $calling_team_achieved_name,
            'calling_team_achieved_amount' => $calling_team_achieved_amount,
            'calling_team_achieved_data' => $calling_team_achieved_data,
            'calling_team_list' => $calling_team_list,
            'permornace_type'=>$permornace_type,
            'from_date' => $from_date,
            'to_date' => $to_date
            ]); 
    }
    
    public function GetDailyCallingTeamDispatchData(Request $request)
    {
        $start = Carbon::parse($request->from_date);
        $end = Carbon::parse($request->to_date); 
        $dates = new Collection();
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $dates->push($date->toDateString());
        } 
        $team_result;
        foreach($dates as $date)
        {    
            $team_result = DB::table('manual_orders')
            ->select(DB::raw('sum(price) as amount'))
            ->whereBetween('updated_at', [$date.' 00:00:00', $date.' 23:59:59'])
            ->where('status', 'dispatched') 
            ->where('assign_to', $request->id)->get(); 
            if(is_null($team_result->first()->amount))
            {
                $team_performance[] =  0;     
            }
            else
            {
                $team_performance[] =  $team_result->first()->amount; 
            } 
            
        }   
        
        
        // $team_result = DB::table('manual_orders')
        //     ->selectRaw('sum(price) as amount, DATE(updated_at) as update_at') 
        //     ->whereBetween('updated_at', [$request->from_date, $request->to_date])
        //     ->where('status', 'dispatched') 
        //     ->where('assign_to', $request->id)
        //     ->groupBy('update_at')
        //     ->orderby('update_at','DESC')
        //     ->get(); 
            
                
        //   dd($team_performance); 
            $TeamDailyPerformance = [
                'label'=>$request->emp_name,  
                'data' => $team_performance,
                'fill' => true,
                'borderColor' => $this->borderColor[$request->color_index],
                'borderWidth'=> 1, 
                'tension' => 0.1,
                'backgroundColor' => $this->backgroundColor[$request->color_index], 
            ];
            // dd($TeamDailyPerformance);
        return response()->json(['data'=>$TeamDailyPerformance]);  
    }
    
} 
