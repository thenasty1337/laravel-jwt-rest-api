<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Auth;
use App\Jobs\SendMessage;

class MessageController extends Controller
{
    public function index()
    {
        return Message::with(['user' => function ($query) {
            $query->select('id', 'name', 'email');
        }])
        ->whereNull('deleted_at')
        ->select('id', 'message', 'user_id', 'created_at')
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($message) {
            $message->user->level = 1;
            $message->user->avatar = 'avatar/avatar-v1.webp';
            return $message;
        });
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required|string'
        ]);

        $message = new Message();
        $message->user_id = Auth::id();
        $message->message = $validatedData['message'];
        $message->save();

        $messageData = $this->formatMessage($message);

        SendMessage::dispatch($messageData);

        return response()->json($messageData, 201);
    }

    private function formatMessage(Message $message)
    {
        $user = $message->user()->select('id', 'name', 'email')->first();
        return [
            'id' => $message->id,
            'message' => $message->message,
            'user_id' => $message->user_id,
            'created_at' => $message->created_at->toDateTimeString(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'level' => 1,
                'avatar' => 'avatar/avatar-v1.webp',
            ],
        ];
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->deleted_by = Auth::id();
        $message->deleted_at = now();
        $message->save();




        return response()->json(['message' => 'Message deleted successfully']);
    }
}
