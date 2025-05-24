<?php
// app/Listeners/SendBookingNotification.php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Notifications\BookingCreated as BookingCreatedNotification;
use App\Jobs\SendBookingNotification as SendBookingNotificationJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(BookingCreated $event)
    {
        $booking = $event->booking;
        
        // Notify photographer about new booking request
        $booking->photographer->notify(new BookingCreatedNotification($booking));
        
        // Send confirmation email to customer
        dispatch(new SendBookingNotificationJob($booking, 'customer_confirmation'));
        
        // Log admin activity
        activity()
            ->causedBy($booking->customer)
            ->performedOn($booking)
            ->withProperties([
                'booking_id' => $booking->id,
                'photographer_id' => $booking->photographer_id,
                'package_id' => $booking->package_id,
                'total_amount' => $booking->total_amount
            ])
            ->log('Booking created');
    }

    public function failed(BookingCreated $event, $exception)
    {
        \Log::error('Failed to send booking notification', [
            'booking_id' => $event->booking->id,
            'error' => $exception->getMessage()
        ]);
    }
}