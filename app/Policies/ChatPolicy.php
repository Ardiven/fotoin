<?php
// app/Policies/ChatPolicy.php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Booking $booking)
    {
        // Only booking participants can view chat
        return $user->id === $booking->customer_id 
            || $user->id === $booking->photographer_id 
            || $user->hasRole('admin');
    }

    public function view(User $user, Chat $chat)
    {
        // Only sender, recipient, or admin can view message
        return $user->id === $chat->sender_id 
            || $user->id === $chat->recipient_id 
            || $user->hasRole('admin');
    }

    public function create(User $user, Booking $booking)
    {
        // Only booking participants can send messages
        // Booking must be active (not cancelled/completed long ago)
        if (!in_array($booking->status, ['pending', 'confirmed', 'completed'])) {
            return false;
        }

        // Prevent messaging after 30 days of completion
        if ($booking->status === 'completed' 
            && $booking->updated_at->diffInDays(now()) > 30) {
            return false;
        }

        return $user->id === $booking->customer_id 
            || $user->id === $booking->photographer_id;
    }

    public function update(User $user, Chat $chat)
    {
        // Only sender can edit their message within 15 minutes
        return $user->id === $chat->sender_id 
            && $chat->created_at->diffInMinutes(now()) <= 15;
    }

    public function delete(User $user, Chat $chat)
    {
        // Sender can delete within 1 hour, admin can always delete
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $chat->sender_id 
            && $chat->created_at->diffInHours(now()) <= 1;
    }

    public function addAttachment(User $user, Booking $booking)
    {
        // Same rules as creating messages
        return $this->create($user, $booking);
    }

    public function markAsRead(User $user, Chat $chat)
    {
        // Only recipient can mark message as read
        return $user->id === $chat->recipient_id;
    }
}