<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('chat');
    }

    /**
     * Data that should be broadcast with the event.
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->chatMessage->id,
            'user_id' => $this->chatMessage->user_id,
            'content' => $this->chatMessage->content,
            'created_at' => $this->chatMessage->created_at->toDateTimeString(),
        ];
    }
}
