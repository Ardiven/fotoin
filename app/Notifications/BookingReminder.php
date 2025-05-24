<?php
// app/Notifications/BookingReminder.php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReminder extends Notification implements ShouldQueue
{
    use Queueable;

    private $reminderType;

    public function __construct(
        public Booking $booking,
        string $reminderType = '24_hours'
    ) {
        $this->reminderType = $reminderType;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $timeUntil = $this->getTimeUntilBooking();
        
        return (new MailMessage)
            ->subject('Booking Reminder - ' . $timeUntil)
            ->greeting('Hello ' . $notifiable->name)
            ->line('This is a friendly reminder about your upcoming photoshoot.')
            ->line('Time until session: ' . $timeUntil)
            ->line('Date: ' . $this->booking->date->format('M d, Y'))
            ->line('Time: ' . $this->booking->time)
            ->line('Location: ' . $this->booking->location)
            ->line('Photographer: ' . $this->booking->photographer->name)
            ->action('View Booking Details', url('/bookings/' . $this->booking->id))
            ->line('We look forward to seeing you soon!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'booking_reminder',
            'booking_id' => $this->booking->id,
            'reminder_type' => $this->reminderType,
            'time_until' => $this->getTimeUntilBooking(),
            'date' => $this->booking->date->format('Y-m-d'),
            'time' => $this->booking->time,
            'message' => 'Upcoming booking reminder - ' . $this->getTimeUntilBooking()
        ];
    }

    private function getTimeUntilBooking(): string
    {
        return match($this->reminderType) {
            '24_hours' => '24 hours',
            '2_hours' => '2 hours',
            '30_minutes' => '30 minutes',
            default => 'Soon'
        };
    }
}