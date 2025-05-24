<?php
// app/Events/MessageSent.php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Chat $chat
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat->booking_id);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->chat->id,
            'booking_id' => $this->chat->booking_id,
            'sender_id' => $this->chat->sender_id,
            'recipient_id' => $this->chat->recipient_id,
            'message' => $this->chat->message,
            'sender' => [
                'id' => $this->chat->sender->id,
                'name' => $this->chat->sender->name,
                'avatar' => $this->chat->sender->avatar_url
            ],
            'attachments' => $this->chat->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'filename' => $attachment->filename,
                    'file_type' => $attachment->file_type,
                    'file_url' => $attachment->file_url
                ];
            }),
            'created_at' => $this->chat->created_at->toISOString()
        ];
    }
}