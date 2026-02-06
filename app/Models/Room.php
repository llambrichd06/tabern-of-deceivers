<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_code', //Code for the users to join the room
        'player_1',  //Users that are able to join the room
        'player_2',
        'player_3',
        'player_4',
        'player_5',
        'player_6',
    ];
}
