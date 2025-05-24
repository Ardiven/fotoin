<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'location' => $this->location,
            'is_featured' => $this->is_featured,
            'view_count' => $this->view_count,
            'like_count' => $this->like_count,
            'tags' => $this->tags ? explode(',', $this->tags) : [],
            
            // Photographer info
            'photographer' => new UserResource($this->whenLoaded('user')),
            
            // Images
            'images' => $this->whenLoaded('images', function() {
                return $this->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'url' => asset('storage/' . $image->image_path),
                        'thumbnail' => asset('storage/' . $image->thumbnail_path),
                        'alt_text' => $image->alt_text,
                        'is_cover' => $image->is_cover,
                        'order' => $image->order,
                    ];
                });
            }),
            
            // Cover image (first image or featured image)
            'cover_image' => $this->when($this->images->isNotEmpty(), 
                asset('storage/' . $this->images->where('is_cover', true)->first()?->image_path ?? $this->images->first()->image_path)
            ),
            
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}