<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TakenCards implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $game;
    private $resultMsg;

    /**
     * Create a new event instance.
     */
    public function __construct($game, $resultMsg)
    {
        $this->game = $game;
        $this->resultMsg = $resultMsg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('game.room.'.$this->game->room_id),
        ];
    }

    public function broadcastWith() {
        return [
            'result' => $this->resultMsg,
        ];
    }
}
