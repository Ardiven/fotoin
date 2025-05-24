<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'message_type' => $this->message_type, // text, image, file
            'is_read' => $this->is_read,
            'read_at' => $this->read_at?->format('Y-m-d H:i:s'),
            
            // Sender info
            'sender' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
                'avatar' => $this->sender->avatar ? asset('storage/' . $this->sender->avatar) : null,
                'role' => $this->sender->getRoleNames()->first(),
            ],
            
            // Receiver info
            'receiver' => [
                'id' => $this->receiver->id,
                'name' => $this->receiver->name,
                'avatar' => $this->receiver->avatar ? asset('storage/' . $this->receiver->avatar) : null,
                'role' => $this->receiver->getRoleNames()->first(),
            ],
            
            // Booking context (if chat is related to booking)
            'booking' => $this->when($this->booking_id, function() {
                return [
                    'id' => $this->booking->id,
                    'booking_number' => $this->booking->booking_number,
                    'booking_date' => $this->booking->booking_date->format('Y-m-d'),
                    'status' => $this->booking->status,
                ];
            }),
            
            // Attachments (images, files)
            'attachments' => $this->whenLoaded('attachments', function() {
                return $this->attachments->map(function($attachment) {
                    return [
                        'id' => $attachment->id,
                        'file_name' => $attachment->file_name,
                        'file_type' => $attachment->file_type,
                        'file_size' => $attachment->file_size,
                        'file_url' => asset('storage/' . $attachment->file_path),
                        'thumbnail_url' => $attachment->thumbnail_path ? 
                            asset('storage/' . $attachment->thumbnail_path) : null,
                    ];
                });
            }),
            
            // Timestamps
            'sent_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            // Human readable time
            'sent_at_human' => $this->created_at->diffForHumans(),
        ];
    }
}