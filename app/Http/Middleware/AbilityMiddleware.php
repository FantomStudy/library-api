<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AbilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $ability): Response
    {
        $user = Auth::user();

        if (!$user->tokenCan($ability)) {
            return response()->json([
                'success' => false,
                'message' => "You don't have permission to access this resource.",
            ], 403);
        }

        return $next($request);
    }
}
