<?php
// app/Notifications/BookingCreated.php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Booking Request')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new booking request.')
            ->line('Customer: ' . $this->booking->customer->name)
            ->line('Package: ' . $this->booking->package->name)
            ->line('Date: ' . $this->booking->date->format('M d, Y'))
            ->line('Amount: $' . number_format($this->booking->total_amount, 2))
            ->action('View Booking', url('/bookings/' . $this->booking->id))
            ->line('Please review and confirm the booking.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'booking_created',
            'booking_id' => $this->booking->id,
            'customer_name' => $this->booking->customer->name,
            'package_name' => $this->booking->package->name,
            'date' => $this->booking->date->format('Y-m-d'),
            'amount' => $this->booking->total_amount,
            'message' => 'New booking request from ' . $this->booking->customer->name
        ];
    }
}