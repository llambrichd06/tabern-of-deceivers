<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\SentMessageRequest;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function sent(SentMessageRequest $request, Room $room)  {
        $user = Auth::user();
        $message = $room->messages()->create([
            'user_id' => $user->id,
            'text' => $request->text,
        ]);

        broadcast(new MessageSent($message));
    }
}
