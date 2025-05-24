<?php
// app/Notifications/NewMessage.php

namespace App\Notifications;

use App\Models\Chat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Chat $chat
    ) {}

    public function via($notifiable)
    {
        // Only send database notification for real-time updates
        // Email only if user has email notifications enabled
        $channels = ['database'];
        
        if ($notifiable->email_notifications ?? true) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        $sender = $this->chat->sender;
        
        return (new MailMessage)
            ->subject('New Message from ' . $sender->name)
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have received a new message from ' . $sender->name . '.')
            ->line('Message: "' . \Str::limit($this->chat->message, 100) . '"')
            ->action('Reply Now', url('/chats/' . $this->chat->booking_id))
            ->line('Stay connected with your photographer/client!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'new_message',
            'chat_id' => $this->chat->id,
            'sender_id' => $this->chat->sender_id,
            'sender_name' => $this->chat->sender->name,
            'booking_id' => $this->chat->booking_id,
            'message_preview' => \Str::limit($this->chat->message, 50),
            'has_attachment' => $this->chat->attachments->count() > 0,
            'message' => 'New message from ' . $this->chat->sender->name
        ];
    }
}