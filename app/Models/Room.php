<?php

namespace App\Models;

use App\Events\MessageSent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'room_code', //Code for the users to join the room
        'state', //3 states lobby, on_going, completed
        'host_id', //Id of the host user
        'private', //boolean for private room or not (shows up in the room list)
    ];

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function host() : BelongsTo {
        return $this->belongsTo(User::class,'host_id');
    }

    public function messages() : HasMany {
        return $this->hasMany(Message::class);
    }
}
