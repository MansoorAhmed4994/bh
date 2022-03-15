<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
class Users
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
        foreach (Auth::user()->connect as $role) {
            if ($role->name == 'user') {
                return $next($request);
            
        }

        else if ($role->name == 'rider') {
            return redirect()->route('riders.dashboard');
        
        }
        
        else
            {
                return redirect()->route('login')->with('flash_message_error','Please LogIn To Access');
            }
        
            }
    }
}
