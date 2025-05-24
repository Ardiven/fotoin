<?php
// app/Events/PaymentProcessed.php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Transaction $transaction
    ) {}

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->transaction->booking->customer_id),
            new PrivateChannel('user.' . $this->transaction->booking->photographer_id)
        ];
    }

    public function broadcastAs()
    {
        return 'payment.processed';
    }

    public function broadcastWith()
    {
        return [
            'transaction_id' => $this->transaction->id,
            'booking_id' => $this->transaction->booking_id,
            'amount' => $this->transaction->amount,
            'status' => $this->transaction->status,
            'payment_method' => $this->transaction->payment_method
        ];
    }
}