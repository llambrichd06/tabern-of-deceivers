<?php

use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

//broadcast "wildcards" (the stuff u put between {}) get put in the same order as the function parameters from left to right
//broadcast always has the authenticated user first in the function.

Broadcast::channel('chat.room.{roomId}', function ($user, $roomId) {
    $playerInRoom = Room::find($roomId)->players->contains($user->id);
    if ($playerInRoom) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            // 'avatar' => $user->profile_photo_url, // PUT THIS LATER IF WE WANT PFP's
        ];
    }
});

Broadcast::channel('game.room.{roomId}', function ($user, $roomId) {
    $playerInRoom = Room::find($roomId)->players->contains($user->id);
    if ($playerInRoom) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            // 'avatar' => $user->profile_photo_url, // PUT THIS LATER IF WE WANT PFP's
        ];
    }
});
