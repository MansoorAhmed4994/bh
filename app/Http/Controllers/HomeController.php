<?php

namespace App\Http\Controllers;
use CountryState;
use Illuminate\Http\Request;
use App\Models\Client\Bhvareesha; 
use App\Models\Client\Customers; 
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
         
        $list = DB::table('manual_orders')
                 ->select('status', DB::raw('count(*) as total'), DB::raw('sum(price) as amount'))
                 ->whereBetween('updated_at', [$from_date, $to_date])
                 ->groupBy('status')
                 ->get();
        
        
        //  $list =DB::table('manual_orders')->orderBy('id', 'DESC')->whereBetween('created_at', [$from_date, $to_date])
        //   ->groupBy('status')
        //   ->select('status', DB::raw('count(*) as total'), DB::raw('sum(price) as amount'))
        //   ->get();
          
          
         // dd($list);
        //if ($result->count()) { }
        return view('dashboard')->with('data',$list);
    }
    
    public function contact()
    {
        // dd();
        $customers = Customers::all();
        $bhvareeshas = Bhvareesha::all();
        $not=[]; 
        // dd($bhvareeshas->first());
        foreach($customers as $customer)
        {
            $customer_numnber = $customer->number;
            $number_length = strlen($customer_numnber);
            if($number_length == 11)
            {
                $not['details']['number'][]= $customer_numnber;
                $not['details']['length'][]= $number_length;
                $not['details']['id'][]= $customer->id;
            }
            else
            {
                // $not['details']['number'][]= $customer_numnber;
                // $not['details']['length'][]= $number_length;
                // $not['details']['id'][]= $customer->id;
            }
            
        }
        
        $notavailables = Bhvareesha::select('*')->whereNotIn('phone',$not['details']['number'])->get();
        // dd($notavailables);
        foreach($notavailables as $notavailable)
        {
            $customers_insert = Customers::create([
                'first_name' => $notavailable->name,
                'last_name' => $notavailable->name,
                'number' => $notavailable->phone, 
                'whatsapp_number' => $notavailable->phone,
                'address' => '',
                'created_by' => '1',
                'updated_by' => '1',
                'status' => 'active', 
            ]); 
            echo '<pre>';print_r($customers_insert);
        }
        
        dd($customers);
    }
}
