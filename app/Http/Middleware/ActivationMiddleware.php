<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivationMiddleware
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
        if (!is_null(DB::table('managers')->first())) {
            if (DB::table('managers')->first()->expired == 0) {

                if (Auth::guard('manager')->check() || Auth::guard('cashier')->check()) {

                    return $next($request);

                } else {
                    return redirect()->route('login');
                }
            } else {
//                Auth::guard('cashier')->logout();
                return redirect()->route('activationReset');
            }

        }else{
            return redirect()->route('activation');
        }
    }
}
