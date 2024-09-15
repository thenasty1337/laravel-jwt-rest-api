<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index']);
    }

    public function index()
    {
        $messages = ChatMessage::with('user')->latest()->take(50)->get()->reverse();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'room' => 'required|string',
        ]);

        $message = Auth::user()->chatMessages()->create([
            'content' => $request->input('content'),
            'room' => $request->input('room'),
        ]);

        broadcast(new \App\Events\NewChatMessage($message))->toOthers();

        return response()->json(['success' => true, 'message' => $message->load('user')]);
    }

    public function getMessages(Request $request)
    {
        $request->validate([
            'room' => 'required|string',
        ]);

        $messages = ChatMessage::with('user')
            ->where('room', $request->input('room'))
            ->latest()
            ->take(50)
            ->get()
            ->reverse();

        return response()->json(['success' => true, 'messages' => $messages]);
    }

    public function removeMessage(Request $request)
    {
        $request->validate([
            'messageId' => 'required|string',
        ]);

        $message = ChatMessage::findOrFail($request->input('messageId'));

        // Add authorization check here if needed

        $message->delete();

        return response()->json(['success' => true]);
    }

    public function clearChat(Request $request)
    {
        $request->validate([
            'room' => 'required|string',
        ]);

        // Add authorization check here if needed

        ChatMessage::where('room', $request->input('room'))->delete();

        return response()->json(['success' => true]);
    }

    public function lockChat(Request $request)
    {
        $request->validate([
            'room' => 'required|string',
            'locked' => 'required|boolean',
        ]);

        // Add authorization check here if needed

        // Implement chat locking logic here
        // For example, you might have a 'locked' column in your rooms table
        // Room::where('name', $request->input('room'))->update(['locked' => $request->input('locked')]);

        return response()->json(['success' => true]);
    }
}
