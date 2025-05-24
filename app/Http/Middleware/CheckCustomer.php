<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Authentication required.',
                'error' => 'AUTH_REQUIRED'
            ], 401);
        }

        $user = auth()->user();

        if (!$user->hasRole('customer')) {
            return response()->json([
                'message' => 'Access denied. Customer account required.',
                'error' => 'CUSTOMER_ONLY',
                'user_role' => $user->getRoleNames()->first()
            ], 403);
        }

        // Optional: Check if customer has verified email
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Please verify your email address first.',
                'error' => 'EMAIL_NOT_VERIFIED'
            ], 422);
        }

        return $next($request);
    }
}