<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'status' => $this->status,
            'status_label' => $this->getStatusLabel(),
            
            // Booking details
            'booking_date' => $this->booking_date->format('Y-m-d'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'event_type' => $this->event_type,
            'guest_count' => $this->guest_count,
            'special_requests' => $this->special_requests,
            
            // Pricing
            'total_amount' => [
                'amount' => $this->total_amount,
                'formatted' => 'Rp ' . number_format($this->total_amount, 0, ',', '.'),
            ],
            'deposit_amount' => [
                'amount' => $this->deposit_amount,
                'formatted' => 'Rp ' . number_format($this->deposit_amount, 0, ',', '.'),
            ],
            
            // Relations
            'customer' => new UserResource($this->whenLoaded('customer')),
            'photographer' => new UserResource($this->whenLoaded('photographer')),
            'package' => new PackageResource($this->whenLoaded('package')),
            
            // Selected add-ons
            'selected_addons' => $this->whenLoaded('selectedAddons', function() {
                return $this->selectedAddons->map(function($addon) {
                    return [
                        'id' => $addon->id,
                        'name' => $addon->name,
                        'price' => [
                            'amount' => $addon->price,
                            'formatted' => 'Rp ' . number_format($addon->price, 0, ',', '.'),
                        ],
                    ];
                });
            }),
            
            // Payment info
            'payment' => $this->whenLoaded('transaction', function() {
                return $this->transaction ? [
                    'status' => $this->transaction->status,
                    'payment_method' => $this->transaction->payment_method,
                    'paid_at' => $this->transaction->paid_at?->format('Y-m-d H:i:s'),
                ] : null;
            }),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    
    private function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }
}