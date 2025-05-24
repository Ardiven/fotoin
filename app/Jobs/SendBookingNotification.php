<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Notifications\BookingCreated;
use App\Notifications\BookingConfirmed;
use App\Notifications\BookingCancelled;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBookingNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;
    protected $notificationType;
    protected $additionalData;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, string $notificationType, array $additionalData = [])
    {
        $this->booking = $booking->load(['customer', 'photographer', 'package']);
        $this->notificationType = $notificationType;
        $this->additionalData = $additionalData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        switch ($this->notificationType) {
            case 'created':
                $this->sendBookingCreatedNotifications();
                break;
            case 'confirmed':
                $this->sendBookingConfirmedNotifications();
                break;
            case 'cancelled':
                $this->sendBookingCancelledNotifications();
                break;
            case 'reminder':
                $this->sendBookingReminderNotifications();
                break;
        }
    }

    private function sendBookingCreatedNotifications()
    {
        // Notify photographer about new booking
        $this->booking->photographer->notify(new BookingCreated($this->booking, 'photographer'));
        
        // Confirm to customer
        $this->booking->customer->notify(new BookingCreated($this->booking, 'customer'));
        
        // Send to admins if configured
        $this->notifyAdmins('booking_created');
    }

    private function sendBookingConfirmedNotifications()
    {
        // Notify customer about confirmation
        $this->booking->customer->notify(new BookingConfirmed($this->booking));
        
        // Create calendar events if integration exists
        $this->createCalendarEvents();
    }

    private function sendBookingCancelledNotifications()
    {
        $cancelledBy = $this->additionalData['cancelled_by'] ?? 'system';
        $reason = $this->additionalData['reason'] ?? '';
        
        // Notify both parties
        $this->booking->customer->notify(new BookingCancelled($this->booking, $cancelledBy, $reason));
        $this->booking->photographer->notify(new BookingCancelled($this->booking, $cancelledBy, $reason));
    }

    private function sendBookingReminderNotifications()
    {
        $reminderType = $this->additionalData['reminder_type'] ?? '24h';
        
        // Send reminder to both customer and photographer
        $this->booking->customer->notify(new BookingReminder($this->booking, $reminderType));
        $this->booking->photographer->notify(new BookingReminder($this->booking, $reminderType));
    }

    private function notifyAdmins($eventType)
    {
        // Get admin users
        $admins = \App\Models\User::role('admin')->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\AdminBookingAlert($this->booking, $eventType));
        }
    }

    private function createCalendarEvents()
    {
        // Integration with Google Calendar, Outlook, etc.
        // This would be implemented based on your calendar service
    }
}