<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => [
                'amount' => $this->price,
                'formatted' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            ],
            'duration_hours' => $this->duration_hours,
            'max_photos' => $this->max_photos,
            'max_edited_photos' => $this->max_edited_photos,
            'includes_raw_files' => $this->includes_raw_files,
            'category' => $this->category,
            'is_active' => $this->is_active,
            
            // Photographer info
            'photographer' => new UserResource($this->whenLoaded('user')),
            
            // Add-ons
            'addons' => $this->whenLoaded('addons', function() {
                return $this->addons->map(function($addon) {
                    return [
                        'id' => $addon->id,
                        'name' => $addon->name,
                        'description' => $addon->description,
                        'price' => [
                            'amount' => $addon->price,
                            'formatted' => 'Rp ' . number_format($addon->price, 0, ',', '.'),
                        ],
                    ];
                });
            }),
            
            // Package stats
            'stats' => [
                'total_bookings' => $this->bookings_count ?? $this->bookings()->count(),
                'avg_rating' => $this->avg_rating,
            ],
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}