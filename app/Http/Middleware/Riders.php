<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth; 
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class Riders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        // dd($request);
        if(Auth::check()){
            return redirect('riders/dashboard');
        }
        else{
            return redirect('riders/login')->with('flash_message_error','Please LogIn With rider To Access');
        }
        

        
    }
}
