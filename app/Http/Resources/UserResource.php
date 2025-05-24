<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'role' => $this->getRoleNames()->first(),
            'is_verified' => $this->email_verified_at ? true : false,
            'location' => $this->location,
            'social_media' => [
                'instagram' => $this->instagram,
                'website' => $this->website,
            ],
            'stats' => $this->when($this->hasRole('photographer'), [
                'total_portfolios' => $this->portfolios_count ?? $this->portfolios()->count(),
                'total_bookings' => $this->photographer_bookings_count ?? $this->photographerBookings()->count(),
                'avg_rating' => $this->avg_rating,
                'total_reviews' => $this->total_reviews,
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}