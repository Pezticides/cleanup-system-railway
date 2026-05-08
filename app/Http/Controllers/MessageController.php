<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()->conversations()->with(['users', 'messages' => fn($q) => $q->latest()->limit(1)])->latest('updated_at')->get();
        return view('messages.index', compact('conversations'));
    }

    public function create()
    {
        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();
        return view('messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'body' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|max:4096',
        ]);

        $memberIds = collect($request->user_ids)->push(auth()->id())->unique()->values();

        $conversation = Conversation::create([
            'name' => $request->name,
            'is_group' => $memberIds->count() > 2,
            'created_by' => auth()->id(),
        ]);
        $conversation->users()->sync($memberIds);

        if ($request->filled('body') || $request->hasFile('photo')) {
            $this->saveMessage($conversation, $request);
        }

        return redirect()->route('messages.show', $conversation)->with('success', 'Conversation created.');
    }

    public function show(Conversation $conversation)
    {
        abort_unless($conversation->users()->where('users.id', auth()->id())->exists(), 403);
        $conversation->load(['users', 'messages.user']);
        return view('messages.show', compact('conversation'));
    }

    public function send(Request $request, Conversation $conversation)
    {
        abort_unless($conversation->users()->where('users.id', auth()->id())->exists(), 403);
        $request->validate([
            'body' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|max:4096',
        ]);
        if (!$request->filled('body') && !$request->hasFile('photo')) {
            return back()->withErrors(['body' => 'Write a message or upload a photo.']);
        }
        $this->saveMessage($conversation, $request);
        return back();
    }

    private function saveMessage(Conversation $conversation, Request $request): void
    {
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('message_photos', 'public');
        }
        $conversation->messages()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
            'photo' => $photoPath,
        ]);
        $conversation->touch();
    }
}
