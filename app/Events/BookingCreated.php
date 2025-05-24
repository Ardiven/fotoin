<?php
// app/Events/BookingCreated.php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Booking $booking
    ) {}

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->booking->photographer_id),
            new PrivateChannel('user.' . $this->booking->customer_id)
        ];
    }

    public function broadcastAs()
    {
        return 'booking.created';
    }

    public function broadcastWith()
    {
        return [
            'booking_id' => $this->booking->id,
            'customer_name' => $this->booking->customer->name,
            'photographer_name' => $this->booking->photographer->name,
            'package_name' => $this->booking->package->name,
            'date' => $this->booking->date->format('Y-m-d'),
            'status' => $this->booking->status,
            'total_amount' => $this->booking->total_amount
        ];
    }
}