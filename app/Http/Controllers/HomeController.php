<?php

namespace App\Http\Controllers;
use CountryState;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $from_date = Carbon::now()->subDays(60)->toDateTimeString();
        $to_date = Carbon::now()->addDays(5)->toDateTimeString();
  
// Add days to date and display it 
//echo date('Y-m-d', strtotime($date. ' + 10 days'));
        //dd('working');
         $list =DB::table('manual_orders')->orderBy('id', 'DESC')->whereBetween('created_at', [$from_date, $to_date])
          ->groupBy('status')
          ->select('status', DB::raw('count(*) as total'), DB::raw('sum(price) as amount'))
          ->get();
          
          
         // dd($list);
        //if ($result->count()) { }
        return view('dashboard')->with('data',$list);
    }
}
