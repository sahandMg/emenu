<?php

namespace App\Http\Middleware;

use App\Restaurant;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if (Auth::guard('cashier')->check()) {
            return redirect()->route('orders');
        }else if(Auth::guard('manager')->check()){

            return redirect()->route('settings');
        }
        return $next($request);
    }
}
