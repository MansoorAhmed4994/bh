<?php

namespace App\Http\Middleware;


use Auth;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;


class Rider
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
        foreach (Auth::user()->connect as $role) 
        {
            if ($role->name == 'rider') 
            {
                return $next($request);        
            }
            else
            {
                return redirect()->route('riders.login')->with('flash_message_error','Please LogIn With Admin To Access');
            }
        
        }
    }
}
