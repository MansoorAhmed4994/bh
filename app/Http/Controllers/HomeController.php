<?php

namespace App\Http\Controllers;
use CountryState;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use DB;

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
        //dd('working');
         $list =DB::table('manual_orders')
          ->groupBy('status')
          ->select('status', DB::raw('count(*) as total'), DB::raw('sum(price) as amount'))
          ->get();
          
          
         // dd($list);
        //if ($result->count()) { }
        return view('dashboard')->with('data',$list);
    }
}
