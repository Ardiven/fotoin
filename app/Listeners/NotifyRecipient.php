<?php
// app/Listeners/NotifyRecipient.php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Notifications\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyRecipient implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(MessageSent $event)
    {
        $chat = $event->chat;
        $recipient = $chat->recipient;
        
        // Don't notify if recipient is currently online and viewing the chat
        if ($this->isRecipientActiveInChat($recipient, $chat->booking_id)) {
            return;
        }
        
        // Send notification to recipient
        $recipient->notify(new NewMessage($chat));
        
        // Update unread message count
        $this->updateUnreadCount($chat);
        
        // Log message activity
        activity()
            ->causedBy($chat->sender)
            ->performedOn($chat)
            ->withProperties([
                'booking_id' => $chat->booking_id,
                'recipient_id' => $chat->recipient_id,
                'has_attachments' => $chat->attachments->count() > 0
            ])
            ->log('Message sent');
    }

    private function isRecipientActiveInChat($recipient, $bookingId)
    {
        // Check if user is currently active in the chat
        $cacheKey = "user_active_chat_{$recipient->id}_{$bookingId}";
        return cache()->has($cacheKey);
    }

    private function updateUnreadCount($chat)
    {
        $recipient = $chat->recipient;
        $unreadCount = $recipient->receivedChats()
            ->where('read_at', null)
            ->count();
            
        // Update user's unread message count
        $recipient->update(['unread_messages_count' => $unreadCount]);
    }
}