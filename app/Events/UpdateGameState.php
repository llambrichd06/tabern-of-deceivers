<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateGameState
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $gameId;
    private $gameState;

    /**
     * Create a new event instance.
     */
    public function __construct($gameId, $gameState)
    {
        $this->gameId = $gameId;
        $this->gameState = $gameState;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('chat.room.'.$this->gameState->room_id),
        ];
    }

    public function broadcastWith() { //data the event brings with it (THESE FUNCTIONS NEED EXACT NAMES OR THEY DON'T WORK)
        return [
            'game_state' => $this->gameState,
        ];
    }
}
