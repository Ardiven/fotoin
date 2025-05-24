<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('customer');
    }

    public function rules()
    {
        return [
            'photographer_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'booking_date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:500',
            'event_type' => 'required|string|max:255',
            'guest_count' => 'nullable|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
            'selected_addons' => 'nullable|array',
            'selected_addons.*' => 'exists:package_addons,id'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if photographer is available on selected date
            $bookingDate = $this->booking_date;
            $startTime = $this->start_time;
            
            // Add custom validation logic here
            // Example: Check photographer availability
        });
    }

    public function messages()
    {
        return [
            'booking_date.after' => 'Tanggal booking harus setelah hari ini.',
            'photographer_id.exists' => 'Photographer tidak ditemukan.',
            'package_id.exists' => 'Package tidak ditemukan.'
        ];
    }
}