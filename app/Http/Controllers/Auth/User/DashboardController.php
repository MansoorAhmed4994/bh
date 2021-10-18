<?php

namespace App\Http\Controllers\Auth\User; 
use App\Http\Controllers\Controller;  
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }
   
    //
    public function index()
    {
        
        return view('auth.user.dashboard');
    }
}
