<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'day_name' => $this->date->format('l'), // Monday, Tuesday, etc
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_available' => $this->is_available,
            'availability_type' => $this->availability_type, // full_day, custom, unavailable
            
            // Photographer info
            'photographer' => new UserResource($this->whenLoaded('photographer')),
            
            // Time slots (for detailed availability)
            'time_slots' => $this->when($this->availability_type === 'custom', function() {
                return $this->generateTimeSlots();
            }),
            
            // Booking info (if date is booked)
            'booking' => $this->when($this->booking_id, function() {
                return [
                    'id' => $this->booking->id,
                    'booking_number' => $this->booking->booking_number,
                    'customer_name' => $this->booking->customer->name,
                    'event_type' => $this->booking->event_type,
                    'start_time' => $this->booking->start_time,
                    'end_time' => $this->booking->end_time,
                    'status' => $this->booking->status,
                ];
            }),
            
            // Notes (for special conditions)
            'notes' => $this->notes,
            
            // Pricing (if different from regular)
            'special_price' => $this->when($this->special_price, [
                'amount' => $this->special_price,
                'formatted' => 'Rp ' . number_format($this->special_price, 0, ',', '.'),
                'reason' => $this->price_reason, // holiday, weekend, premium
            ]),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * Generate available time slots for custom availability
     */
    private function generateTimeSlots()
    {
        $slots = [];
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);
        
        while ($start < $end) {
            $slotEnd = $start->copy()->addHours(2); // 2-hour slots
            if ($slotEnd > $end) $slotEnd = $end;
            
            $slots[] = [
                'start_time' => $start->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'is_available' => $this->checkSlotAvailability($start, $slotEnd),
                'label' => $start->format('H:i') . ' - ' . $slotEnd->format('H:i'),
            ];
            
            $start = $slotEnd;
        }
        
        return $slots;
    }
    
    private function checkSlotAvailability($start, $end)
    {
        // Check if this time slot conflicts with existing bookings
        // This would typically query the bookings table
        return true; // Simplified for example
    }
}