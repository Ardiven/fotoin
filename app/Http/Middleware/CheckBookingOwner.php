<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Booking;

class CheckBookingOwner
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

        // Get booking ID from route parameter
        $bookingId = $request->route('booking') ?? $request->route('id');
        
        if (!$bookingId) {
            return response()->json([
                'message' => 'Booking ID is required.',
                'error' => 'BOOKING_ID_MISSING'
            ], 400);
        }

        $booking = Booking::find($bookingId);
        
        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found.',
                'error' => 'BOOKING_NOT_FOUND'
            ], 404);
        }

        $user = auth()->user();
        
        // Check if user is either the customer or photographer of this booking
        if ($booking->customer_id !== $user->id && $booking->photographer_id !== $user->id) {
            return response()->json([
                'message' => 'Access denied. You can only access your own bookings.',
                'error' => 'BOOKING_ACCESS_DENIED'
            ], 403);
        }

        // Add booking to request for controller use
        $request->merge(['booking_instance' => $booking]);

        return $next($request);
    }
}