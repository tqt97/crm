<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  mixed $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  mixed $guard
     * @return Response
     */
    public function handle(Request $request, Closure $next, $guard): Response
    {
        /**
         * Check guard in route and ability of token
         */
        foreach (UserType::cases() as $case) {
            if ($case->name === strtoupper($guard) && Auth::user()->tokenCan($guard)) {
                return $next($request);
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'Forbidden',
        ], Response::HTTP_FORBIDDEN);
    }
}
