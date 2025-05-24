<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $chats = Chat::query()
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                      ->orWhere('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver', 'attachments'])
            ->when($request->with_user, function ($query, $userId) {
                return $query->where(function ($q) use ($userId) {
                    $q->where('sender_id', $userId)->where('receiver_id', auth()->id())
                      ->orWhere('sender_id', auth()->id())->where('receiver_id', $userId);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return ChatResource::collection($chats);
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required_without:attachments|string',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);

        $chat = Chat::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('chat-attachments', 'public');
                
                $chat->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Mark message as read by sender
        $chat->update(['read_at' => now()]);

        // Dispatch event
        event(new MessageSent($chat));

        return response()->json([
            'status' => 'success',
            'message' => 'Message sent successfully',
            'data' => new ChatResource($chat->load(['attachments']))
        ], 201);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'chat_ids' => 'required|array',
            'chat_ids.*' => 'exists:chats,id'
        ]);

        Chat::whereIn('id', $request->chat_ids)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Messages marked as read'
        ]);
    }

    public function getConversations()
    {
        $conversations = Chat::query()
            ->where(function ($query) {
                $query->where('sender_id', auth()->id())
                      ->orWhere('receiver_id', auth()->id());
            })
            ->with(['sender', 'receiver'])
            ->selectRaw('
                CASE 
                    WHEN sender_id = ? THEN receiver_id 
                    ELSE sender_id 
                END as other_user_id,
                MAX(created_at) as last_message_at,
                COUNT(CASE WHEN receiver_id = ? AND read_at IS NULL THEN 1 END) as unread_count
            ', [auth()->id(), auth()->id()])
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $conversations
        ]);
    }
}