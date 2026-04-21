<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    protected $fillable = [
        'room_id', //id of room the game is related to
        'is_finished', //boolean for if the game is finished or not
        'game_state', //Json of the whole game state. Literally all the info of the game stored here in a json.
    ];
    public function leaderboards(): BelongsToMany
    {
        return $this->belongsToMany(Leaderboard::class)
            ->withTimestamps();
    }

    public function room() : BelongsTo {
        return $this->belongsTo(Room::class);
    }
}
