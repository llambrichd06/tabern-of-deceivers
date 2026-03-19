<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index(Request $request) {
        $room = Room::with('host')->with('players')->get();
        return $room;
    }

    public function openRooms() {
        $pubRooms = Room::with('host')
            ->with('players')
            ->where('private', '0')
            ->where('state', 'lobby')
            ->orderBy('created_at')->get();
        return response()->json([
            'public_rooms' => $pubRooms,
        ]);
    }

    public function show(Room $room) {
        $room->load('host', 'players');
        return response()->json([
            'data' => $room,
        ]);
    }
    
    // public function kickPlayer() //idea a futur
    
    public function store(Request $request) {
        $data = $request->validate([
            'room_code' => ['nullable', 'size:8'],
            'state' => ['required', 'in:lobby,on_going,completed'],
            'host_id' => ['required', 'exists:users,id'],
            'private' => ['required', 'in:0,1']
        ]);
        if (!$data['room_code']) {//If a room code is not provided, we make a random one
            do {
                //We create 4 random bytes, which if we parse into hexadecimal, we get 8 characters, which is exactly what we want for a room code
                $data['room_code'] = strtoupper(bin2hex(random_bytes(4))); 

                //Check if the randomly generated room code exists already on a non-completed room, if it does, generate a new code and re-check
                $exists = Room::where('room_code', $data['room_code'])->where('state', '!=', 'completed')->first();
            } while ($exists);
        }
        $room = Room::create($data);
        
        //Add the host as a player aswell.
        $roomUser = new RoomUsers(); 
        $roomUser->user_id = $room->host_id;
        $roomUser->room_id = $room->id;
        $roomUser->save();

        return $room; //return created room for front, in case we need its data
    }

    public function update(Request $request, Room $room) {
        $room = Room::find($room->id);
        $data = $request->validate([
            'room_code' => ['required', 'size:8'],
            'state' => ['required', 'in:lobby,on_going,completed'],
            'host_id' => ['required', 'exists:users,id'],
            'private' => ['required', 'in:0,1']
        ]);
        $room->room_code = $data['room_code'] ?? $room->room_code;
        $room->state = $data['state'] ?? $room->state;
        $room->host_id = $data['host_id'] ?? $room->host_id;
        $room->private = $data['private'] ?? $room->private;
     
        $room->save();
        return $room;
    }

    public function destroy(Room $room) {
        $room->delete();
        return "deleted successfully";
    }

    /**
     * Function to make the currently logged in user join a room with the code of that room
     */
    public function joinRoomWithCode(Request $request) {
        $user = Auth::user();
        $room = Room::where('room_code', $request->room_code)
                ->where('state', 'lobby')
                ->first();
        if (!$room) return response()->json(['error' => 'Code doesen\'t belong to any room!'], 404); //If a room wasn't found, return an error
        return $this->joinRoom($user->id, $room->id);
    }

    /**
     * Function to make a user join a room
     */
    private function joinRoom($userId, $roomId) {
        try {
            return DB::transaction(function () use ($roomId, $userId) {
                //first or fail is like a normal first();, but if it doesen't find anything, it throws an exception (error) instead of a null
                //Since we are locking for update, other calls will have to wait until the first one is finished to run. 
                $room = Room::where('id', $roomId)->lockForUpdate()->firstOrFail(); 

                //a different way to count pivot tables i guess
                if ($room->players()->count() >= 6) {
                    return response()->json(['error' => 'Room is full!'], 422);
                }
                if ($room->players()->where('user_id', $userId)->first()) {
                    return response()->json(['error' => 'You are already in this room!'], 422);
                }

                // syncWithoutDetaching adds rows on the pivot table, relating however many users id's we put in the array, to the room that we are calling the players relation from
                // Basically, we make relations between the room we are calling from, and the id's inside the array that we define.
                // So if we call the relation from room 4, and we put the user id's 1, 2, and 3, on the pivot table, we add 3 rows of the 3 users all related to room 4 
                $room->players()->syncWithoutDetaching([$userId]);

                return response()->json(['message' => 'Joined the room successfully!', 'id' => $room->id]);
            });
        } catch (\Throwable $e) {
            // if the user is already as a player of the room, it will send an error cause its failing the primary key constraint
        }
    }

    public function changePrivate(Room $room) {
        $room = Room::find($room->id);
        $user = Auth::user();
        $host_id = $room -> host_id;
        if ($user == $host_id) {
            if ($room -> state == true) {
                $room -> state == false;
            } else {
                $room -> state == true;
            }
            $room->save();
        } else {
            return response()->json(['error' => 'The user is not the host of the room, so it can\'t be the one to change if it is private!'], 400);
        }
        return $room;
    }

    public function transferOwnership(Request $request) {
        $room = Room::find($request->room_id);
        $user = Auth::user();
        $host_id = $room -> host_id;
        $player = $room->players()->where($request -> player_id);
        if ($user == $host_id) {
             if ($player) {
                    $room->host_id = $player;
                    $room->save();
                    return $room;
            } else {
                return response()->json(['error' => 'The player you want to give host permision is not in this room'], 400);
            }
        } else {
            return response()->json(['error' => 'Only the host can do this action'], 400);
        }
    }

    public function leaveRoom(Request $request) {
        $room = Room::find($request->room_id);
        $user = Auth::user();
        $player = $room->players()->where($request -> player_id);
        if ($user == $player) {
            $result = $room->players()->detach($request -> player_id); //detach solo borra la relacion entre las filas.
            return response()->json(['success' => $room, 'action' => 'leaveRoom']);
        } else {
            return response()->json(['error' => 'You are not in the room or you have alredy leave it'], 400);
        }
        
    }

    public function kickUser(Request $request) {
        $room = Room::find($request->room_id);
        $user = Auth::user();
        $host_id = $room -> host_id;
        $player = $room->players()->where($request -> player_id);
        if ($user == $host_id) {
             if ($player) {
                    $result = $room->players()->detach($request -> player_id); //detach solo borra la relacion entre las filas.
                    return $result;
            } else {
                return response()->json(['error' => 'The player you want to kick is not in this room'], 400);
            }
        } else {
            return response()->json(['error' => 'Only the host can do this action'], 400);
        }
    }
    
}

