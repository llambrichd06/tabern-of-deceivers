<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'room_code', //Code for the users to join the room
        'state', //3 states lobby, on_going, completed
    ];
}
