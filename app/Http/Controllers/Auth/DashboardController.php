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
        if(Gate::denies('users-pages')) {
            return redirect('login');
            // somehow this seems te get ignored because the unaccessible view gets displayed anyway.
        }
        
        $list = User::all();
        return view('auth.user.dashboard')->with('users',$list);
    }
} 
