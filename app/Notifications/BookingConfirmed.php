<?php
// app/Notifications/BookingConfirmed.php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification implements ShouldQueue
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
            ->subject('Booking Confirmed!')
            ->greeting('Great news, ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed.')
            ->line('Photographer: ' . $this->booking->photographer->name)
            ->line('Package: ' . $this->booking->package->name)
            ->line('Date: ' . $this->booking->date->format('M d, Y'))
            ->line('Time: ' . $this->booking->time)
            ->line('Location: ' . $this->booking->location)
            ->action('View Details', url('/bookings/' . $this->booking->id))
            ->line('We look forward to capturing your special moments!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'booking_confirmed',
            'booking_id' => $this->booking->id,
            'photographer_name' => $this->booking->photographer->name,
            'package_name' => $this->booking->package->name,
            'date' => $this->booking->date->format('Y-m-d'),
            'message' => 'Your booking has been confirmed!'
        ];
    }
}