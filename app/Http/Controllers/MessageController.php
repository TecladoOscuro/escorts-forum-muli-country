<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()
            ->belongsToMany(Conversation::class, 'conversation_participants')
            ->withPivot('last_read_at', 'is_muted')
            ->with(['participants', 'lastMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        abort_unless($conversation->participants->contains(auth()->id()), 403);

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at')
            ->paginate(50);

        // Mark as read
        $conversation->participants()->updateExistingPivot(auth()->id(), [
            'last_read_at' => now(),
        ]);

        $otherUser = $conversation->participants->where('id', '!=', auth()->id())->first();

        return view('messages.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function store(Request $request, User $user)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:1',
        ]);

        // Find existing conversation or create new one
        $conversation = Conversation::whereHas('participants', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'tenant_id' => app('currentTenant')->id,
                'subject' => 'Nachricht an ' . $user->username,
                'last_message_at' => now(),
            ]);

            $conversation->participants()->attach([
                auth()->id() => ['last_read_at' => now()],
                $user->id => [],
            ]);
        }

        Message::create([
            'tenant_id' => app('currentTenant')->id,
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('messages.show', $conversation);
    }

    public function reply(Request $request, Conversation $conversation)
    {
        abort_unless($conversation->participants->contains(auth()->id()), 403);

        $validated = $request->validate([
            'body' => 'required|string|min:1',
        ]);

        Message::create([
            'tenant_id' => app('currentTenant')->id,
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        $conversation->update(['last_message_at' => now()]);
        $conversation->participants()->updateExistingPivot(auth()->id(), [
            'last_read_at' => now(),
        ]);

        return redirect()->route('messages.show', $conversation);
    }
}
