<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    private $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array //in which channel is the event sent
    {
        return [
            new PresenceChannel('chat.room.'.$this->message->room_id),
        ];
    }

    public function broadcastWith() { //data the event brings with it (THESE FUNCTIONS NEED EXACT NAMES OR THEY DON'T WORK)
        $user = User::find($this->message->user_id);
        return [
            'message' => [
                'user_name' => $user->name,
                'room_id' => $this->message->room_id,
                'text' => $this->message->text,
            ]
        ];
    }
}
