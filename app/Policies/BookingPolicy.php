<?php
// app/Policies/BookingPolicy.php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        // Users can view their own bookings, admins can view all
        return true;
    }

    public function view(User $user, Booking $booking)
    {
        // Only customer, photographer, or admin can view booking
        return $user->id === $booking->customer_id 
            || $user->id === $booking->photographer_id 
            || $user->hasRole('admin');
    }

    public function create(User $user)
    {
        // Only customers can create bookings
        return $user->hasRole('customer');
    }

    public function update(User $user, Booking $booking)
    {
        // Rules vary by booking status and user role
        if ($user->hasRole('admin')) {
            return true;
        }

        // Customer can update if booking is pending and they own it
        if ($user->id === $booking->customer_id) {
            return in_array($booking->status, ['pending', 'confirmed']);
        }

        // Photographer can update their own bookings
        if ($user->id === $booking->photographer_id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Booking $booking)
    {
        // Only admin can delete bookings
        return $user->hasRole('admin');
    }

    public function cancel(User $user, Booking $booking)
    {
        // Both customer and photographer can cancel
        // Rules depend on booking date and status
        if ($user->hasRole('admin')) {
            return true;
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return false;
        }

        // Customer can cancel up to 48 hours before
        if ($user->id === $booking->customer_id) {
            return $booking->date->diffInHours(now()) > 48;
        }

        // Photographer can cancel anytime (with different penalty rules)
        if ($user->id === $booking->photographer_id) {
            return true;
        }

        return false;
    }

    public function confirm(User $user, Booking $booking)
    {
        // Only photographer can confirm their bookings
        return $user->id === $booking->photographer_id 
            && $booking->status === 'pending';
    }

    public function complete(User $user, Booking $booking)
    {
        // Only photographer can mark as completed
        return $user->id === $booking->photographer_id 
            && $booking->status === 'confirmed'
            && $booking->date->isPast();
    }

    public function reschedule(User $user, Booking $booking)
    {
        // Both parties can request reschedule
        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return false;
        }

        return $user->id === $booking->customer_id 
            || $user->id === $booking->photographer_id;
    }

    public function refund(User $user, Booking $booking)
    {
        // Only admin can process refunds
        return $user->hasRole('admin') 
            && $booking->payment_status === 'paid';
    }
}