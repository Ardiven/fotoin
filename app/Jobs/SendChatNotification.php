<?php

namespace App\Jobs;

use App\Models\Chat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendChatNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chat;

    /**
     * Create a new job instance.
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat->load(['sender', 'receiver']);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Send push notification to receiver
        $this->sendPushNotification();
        
        // Send email notification if user has email notifications enabled
        $this->sendEmailNotification();
        
        // Update unread count
        $this->updateUnreadCount();
    }

    private function sendPushNotification()
    {
        // Implementation depends on your push notification service
        // Example with Firebase FCM or Pusher
        
        $receiver = $this->chat->receiver;
        $sender = $this->chat->sender;
        
        if ($receiver->fcm_token) {
            $pushData = [
                'title' => "New message from {$sender->name}",
                'body' => $this->getMessagePreview(),
                'data' => [
                    'chat_id' => $this->chat->id,
                    'sender_id' => $sender->id,
                    'type' => 'chat_message',
                ],
            ];
            
            // Send push notification
            // PushNotificationService::send($receiver->fcm_token, $pushData);
        }
    }

    private function sendEmailNotification()
    {
        $receiver = $this->chat->receiver;
        
        // Only send email if user has email notifications enabled
        if ($receiver->email_notifications_enabled) {
            $receiver->notify(new \App\Notifications\NewChatMessage($this->chat));
        }
    }

    private function updateUnreadCount()
    {
        // Update unread count for receiver
        $unreadCount = Chat::where('receiver_id', $this->chat->receiver_id)
            ->where('is_read', false)
            ->count();
            
        // You might want to broadcast this to frontend via WebSocket
        // broadcast(new UnreadCountUpdated($this->chat->receiver_id, $unreadCount));
    }

    private function getMessagePreview()
    {
        $message = $this->chat->message;
        
        if ($this->chat->message_type === 'text') {
            return strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message;
        } else {
            return "Sent " . $this->chat->message_type;
        }
    }
}