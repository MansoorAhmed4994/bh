<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Auth;
class riderroles
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
        dd(Auth::user());
        foreach (Auth::user()->connect as $role) 
        {
            if ($role->name == 'rider') 
            {
                return $next($request);        
            }
            else
            {
                return redirect()->route('riders.login')->with('flash_message_error','Please LogIn With User To Access');
            }
        
        }
        
    }
}
