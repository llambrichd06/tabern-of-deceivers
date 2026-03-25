<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'text', //Text of the message
        'user_id', //User that sent the message
        'room_id', //Room the message was sent it
    ];    

    public function room() : BelongsTo {
        return $this->belongsTo(Room::class);
    }
}
