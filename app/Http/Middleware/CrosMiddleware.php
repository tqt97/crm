<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CrosMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('api/*')) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', '*')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Allow-Headers', 'X-CSRF-Token');
        }
        return $next($request);
    }
}
