<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            
            $role = Auth::user()->type; 

        
            // Check user role
            switch ($role) {
                case '2':
                        return route('users');
                    break;
                case '1':
                        return route('sellers');
                    break; 
                default:
                        return route('profile_view');
                    break;
            }
            
        }

        return $next($request);
    }
}
