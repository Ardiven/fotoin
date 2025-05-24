<?php
// app/Listeners/UpdateBookingStatus.php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Notifications\PaymentReceived;
use App\Notifications\BookingConfirmed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateBookingStatus implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PaymentProcessed $event)
    {
        $transaction = $event->transaction;
        $booking = $transaction->booking;
        
        if ($transaction->status === 'completed') {
            // Update booking status to confirmed
            $booking->update([
                'status' => 'confirmed',
                'payment_status' => 'paid'
            ]);
            
            // Send payment confirmation to both parties
            $booking->customer->notify(new PaymentReceived($transaction));
            $booking->photographer->notify(new PaymentReceived($transaction));
            
            // Send booking confirmation
            $booking->customer->notify(new BookingConfirmed($booking));
            
            // Schedule booking reminders
            $this->scheduleBookingReminders($booking);
            
        } elseif ($transaction->status === 'failed') {
            // Handle failed payment
            $booking->update(['payment_status' => 'failed']);
            
            event(new \App\Events\PaymentFailed($transaction));
        }
        
        // Log transaction
        activity()
            ->causedBy($booking->customer)
            ->performedOn($transaction)
            ->withProperties([
                'transaction_id' => $transaction->id,
                'booking_id' => $booking->id,
                'amount' => $transaction->amount,
                'status' => $transaction->status
            ])
            ->log('Payment processed');
    }

    private function scheduleBookingReminders($booking)
    {
        $bookingDateTime = $booking->date->setTimeFromTimeString($booking->time ?? '10:00');
        
        // Schedule 24 hour reminder
        \App\Jobs\SendBookingReminder::dispatch($booking, '24_hours')
            ->delay($bookingDateTime->subDay());
            
        // Schedule 2 hour reminder
        \App\Jobs\SendBookingReminder::dispatch($booking, '2_hours')
            ->delay($bookingDateTime->subHours(2));
    }
}