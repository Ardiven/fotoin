<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Transaction;
use App\Services\PaymentGatewayService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;
    protected $paymentData;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $transaction, array $paymentData)
    {
        $this->transaction = $transaction->load(['booking.customer', 'booking.photographer']);
        $this->paymentData = $paymentData;
    }

    /**
     * Execute the job.
     */
    public function handle(PaymentGatewayService $paymentService)
    {
        try {
            // Process payment through gateway
            $result = $paymentService->processPayment([
                'amount' => $this->transaction->amount,
                'currency' => 'IDR',
                'payment_method' => $this->paymentData['payment_method'],
                'customer_info' => [
                    'name' => $this->transaction->booking->customer->name,
                    'email' => $this->transaction->booking->customer->email,
                    'phone' => $this->transaction->booking->customer->phone,
                ],
                'order_info' => [
                    'order_id' => $this->transaction->transaction_number,
                    'description' => "Booking #{$this->transaction->booking->booking_number}",
                ],
                'callback_url' => route('payment.callback'),
                'return_url' => route('payment.success'),
            ]);

            if ($result['success']) {
                // Update transaction status
                $this->transaction->update([
                    'status' => 'processing',
                    'gateway_transaction_id' => $result['transaction_id'],
                    'gateway_response' => $result,
                    'processed_at' => now(),
                ]);

                // Update booking status if it's full payment
                if ($this->transaction->type === 'full_payment') {
                    $this->transaction->booking->update(['status' => 'confirmed']);
                    
                    // Send confirmation notifications
                    SendBookingNotification::dispatch($this->transaction->booking, 'confirmed');
                }

                // Generate invoice
                GenerateInvoice::dispatch($this->transaction);

            } else {
                // Payment failed
                $this->transaction->update([
                    'status' => 'failed',
                    'gateway_response' => $result,
                    'error_message' => $result['error_message'] ?? 'Payment processing failed',
                ]);

                // Notify customer of failure
                $this->notifyPaymentFailure($result['error_message'] ?? 'Payment failed');
            }

        } catch (\Exception $e) {
            $this->transaction->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function notifyPaymentFailure($errorMessage)
    {
        $this->transaction->booking->customer->notify(
            new \App\Notifications\PaymentFailed($this->transaction, $errorMessage)
        );
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        $this->transaction->update([
            'status' => 'failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}