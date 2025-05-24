<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $transaction;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction->load([
            'booking.customer',
            'booking.photographer', 
            'booking.package',
            'booking.selectedAddons'
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Prepare invoice data
            $invoiceData = $this->prepareInvoiceData();
            
            // Generate PDF
            $pdf = Pdf::loadView('invoices.template', $invoiceData);
            
            // Save PDF to storage
            $filename = "invoice_{$this->transaction->transaction_number}.pdf";
            $path = "invoices/{$this->transaction->booking->customer->id}/{$filename}";
            
            Storage::put($path, $pdf->output());
            
            // Update transaction with invoice path
            $this->transaction->update([
                'invoice_path' => $path,
                'invoice_generated_at' => now(),
            ]);
            
            // Send invoice to customer
            $this->sendInvoiceToCustomer($path);
            
        } catch (\Exception $e) {
            // Log error but don't fail the job completely
            \Log::error("Invoice generation failed for transaction {$this->transaction->id}: " . $e->getMessage());
            
            $this->transaction->update([
                'invoice_error' => $e->getMessage(),
            ]);
        }
    }

    private function prepareInvoiceData()
    {
        $booking = $this->transaction->booking;
        
        return [
            'invoice_number' => $this->transaction->transaction_number,
            'invoice_date' => $this->transaction->created_at,
            'due_date' => $this->transaction->created_at->addDays(30),
            
            // Customer info
            'customer' => [
                'name' => $booking->customer->name,
                'email' => $booking->customer->email,
                'phone' => $booking->customer->phone,
            ],
            
            // Photographer info
            'photographer' => [
                'name' => $booking->photographer->name,
                'email' => $booking->photographer->email,
                'phone' => $booking->photographer->phone,
            ],
            
            // Booking details
            'booking' => [
                'number' => $booking->booking_number,
                'date' => $booking->booking_date,
                'time' => $booking->start_time . ' - ' . $booking->end_time,
                'location' => $booking->location,
                'event_type' => $booking->event_type,
            ],
            
            // Package details
            'package' => [
                'name' => $booking->package->name,
                'description' => $booking->package->description,
                'price' => $booking->package->price,
            ],
            
            // Add-ons
            'addons' => $booking->selectedAddons->map(function($addon) {
                return [
                    'name' => $addon->name,
                    'price' => $addon->price,
                ];
            }),
            
            // Totals
            'subtotal' => $booking->package->price + $booking->selectedAddons->sum('price'),
            'tax' => 0, // Calculate if applicable
            'total' => $this->transaction->amount,
            
            // Transaction info
            'transaction' => [
                'type' => $this->transaction->type,
                'status' => $this->transaction->status,
                'payment_method' => $this->transaction->payment_method,
                'paid_at' => $this->transaction->paid_at,
            ],
        ];
    }

    private function sendInvoiceToCustomer($invoicePath)
    {
        $this->transaction->booking->customer->notify(
            new \App\Notifications\InvoiceGenerated($this->transaction, $invoicePath)
        );
    }
}