<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leaderboard extends Model
{
    protected $fillable = ['user_id', 'points','wins', 'losses', 'matches'];

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }

}
