<?php
// app/Notifications/PaymentReceived.php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Transaction $transaction
    ) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Received')
            ->greeting('Hello ' . $notifiable->name)
            ->line('We have received your payment.')
            ->line('Amount: $' . number_format($this->transaction->amount, 2))
            ->line('Payment Method: ' . ucfirst($this->transaction->payment_method))
            ->line('Transaction ID: ' . $this->transaction->transaction_id)
            ->line('Date: ' . $this->transaction->created_at->format('M d, Y H:i:s'))
            ->action('Download Receipt', url('/transactions/' . $this->transaction->id . '/receipt'))
            ->line('Thank you for your payment!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'payment_received',
            'transaction_id' => $this->transaction->id,
            'amount' => $this->transaction->amount,
            'payment_method' => $this->transaction->payment_method,
            'booking_id' => $this->transaction->booking_id,
            'message' => 'Payment of $' . number_format($this->transaction->amount, 2) . ' received'
        ];
    }
}