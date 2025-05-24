<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
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
            ->paginate(20);

        $activeConversation = null;
        $messages = collect();

        if ($request->with && $request->with !== auth()->id()) {
            $activeConversation = User::findOrFail($request->with);
            $messages = Chat::with(['sender', 'receiver', 'attachments'])
                ->where(function ($query) use ($request) {
                    $query->where('sender_id', auth()->id())->where('receiver_id', $request->with)
                          ->orWhere('sender_id', $request->with)->where('receiver_id', auth()->id());
                })
                ->orderBy('created_at', 'asc')
                ->paginate(50);

            // Mark messages as read
            Chat::where('sender_id', $request->with)
                ->where('receiver_id', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return view('chat.index', compact('conversations', 'activeConversation', 'messages'));
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

        // Dispatch event
        event(new MessageSent($chat));

        return back()->with('success', 'Message sent successfully');
    }

    public function show(User $user)
    {
        return redirect()->route('chat.index', ['with' => $user->id]);
    }
}