<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InitializeRole
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
        // Clear role if it is not set
        if (!$request->session()->has('role')) {
            $request->session()->forget('role');
        }
        
        return $next($request);
    }
}
