<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPhotographer
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

        if (!$user->hasRole('photographer')) {
            return response()->json([
                'message' => 'Access denied. Photographer account required.',
                'error' => 'PHOTOGRAPHER_ONLY',
                'user_role' => $user->getRoleNames()->first()
            ], 403);
        }

        // Optional: Check if photographer profile is complete
        if (!$this->isPhotographerProfileComplete($user)) {
            return response()->json([
                'message' => 'Please complete your photographer profile first.',
                'error' => 'INCOMPLETE_PROFILE',
                'required_fields' => $this->getMissingProfileFields($user)
            ], 422);
        }

        return $next($request);
    }

    private function isPhotographerProfileComplete($user)
    {
        return !empty($user->bio) && 
               !empty($user->phone) && 
               !empty($user->location);
    }

    private function getMissingProfileFields($user)
    {
        $missing = [];
        if (empty($user->bio)) $missing[] = 'bio';
        if (empty($user->phone)) $missing[] = 'phone';
        if (empty($user->location)) $missing[] = 'location';
        return $missing;
    }
}