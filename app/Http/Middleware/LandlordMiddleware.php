<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LandlordMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(! \Spatie\Multitenancy\Models\Tenant::checkCurrent())
        {
            return $next($request);
        }else{
            return redirect('/');
        }
    }
}
