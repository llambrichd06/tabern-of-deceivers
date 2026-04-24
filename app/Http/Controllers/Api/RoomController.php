<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rooms\ChangePrivateRequest;
use App\Http\Requests\Rooms\JoinRoomRequest;
use App\Http\Requests\Rooms\KickUser;
use App\Http\Requests\Rooms\LeaveRoom;
use App\Http\Requests\Rooms\StoreRoomRequest;
use App\Http\Requests\Rooms\TransferOwnership;
use App\Http\Requests\Rooms\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index() {
        $this->authorize('room-list');
        $room = Room::with('host')->with('players')->get();
        return response()->json([ 'room' => $room ]);
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
        // We don't check for authorisation because you can see any room if you just join it

        if(!$room) return response()->json(['error' => 'Room not found'], 404);

        $room->load('host', 'players');
        return response()->json([
            'room' => $room,
        ]);
    }
        
    public function store(StoreRoomRequest $request) {
        $this->authorize('room-create');
        $data = $request->validated();

        $room = $this->storeRoom($data);
        return response()->json([ 'room' => $room ]);
    }

    public function hostRoom(StoreRoomRequest $request) {
        $data = $request->validated();

        $room = $this->storeRoom($data);
        return response()->json([ 'room' => $room ]); //return created room for front, in case we need its data

    }

    private function storeRoom($data) {
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
        return $room;
    }

    public function update(UpdateRoomRequest $request, Room $room) {
        $this->authorize('room-edit');

        if(!$room) return response()->json(['error' => 'Room not found'], 404);

        $room = Room::find($room->id);
        $data = $request->validated();

        $room->room_code = $data['room_code'] ?? $room->room_code;
        $room->state = $data['state'] ?? $room->state;
        $room->host_id = $data['host_id'] ?? $room->host_id;
        $room->private = $data['private'] ?? $room->private;
     
        $room->save();
        return response()->json([ 'room' => $room ]);
    }

    public function destroy(Room $room) {
        $this->authorize('room-delete');

        if(!$room) return response()->json(['error' => 'Room not found'], 404);

        $room->delete();
        return response()->json([ 'data' => 'deleted successfully' ]);
    }

    /**
     * Function to make the currently logged in user join a room with the code of that room
     */
    public function joinRoomWithCode(JoinRoomRequest $request) {
        $user = Auth::user();
        $room = Room::where('room_code', $request->room_code)
                ->where('state', 'lobby')
                ->first();
        if (!$room) return response()->json(['error' => 'Code doesen\'t belong to any room!'], 404); //If a room wasn't found, return an error
        return $this->joinRoom($user->id, $room->id);
    }

    public function joinPublicRoom(Room $room) {
        if(!$room) return response()->json(['error' => 'Room not found'], 404);

        $user = Auth::user();
        $room = Room::where('id', $room->id)
                ->where('state', 'lobby')
                ->where('private', '0')
                ->first();
        if (!$room) return response()->json(['error' => 'Id doesen\'t belong to any open room!'], 404); //If a room wasn't found, return an error
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
                    return response()->json(['message' => 'You are already in this room!', 'id' => $room->id]);
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
        if(!$room) return response()->json(['error' => 'Room not found'], 404);
        $user = Auth::user();
        $host_id = $room -> host_id;
        if ($user -> id == $host_id) {
            if ($room -> private == '1') {
                $room -> private = '0';
            } else {
                $room -> private = '1';
            }
            $room->save();
        } else {
            return response()->json(['error' => 'Only the admin of the room can change if the room is private or not'], 400);
        }
        return response()->json([ 'room' => $room ]);
    }

    public function transferOwnership(TransferOwnership $request) {
        $room = Room::find($request->room_id);
        $user = Auth::user();
        $host_id = $room -> host_id;
        $player = $room->players()->where('user_id', $request -> player_id)->first();
        if ($user -> id == $host_id) {
             if ($player) {
                    $room->host_id = $player -> id;
                    $room->save();
                    return $room;
            } else {
                return response()->json(['error' => 'The player you want to give host permisson is not in this room'], 404);
            }
        } else {
            return response()->json(['error' => 'Only the host can do this action'], 403);
        }
    }

    public function leaveRoom(LeaveRoom $request) {
        $room = Room::find($request->room_id);
        $user = Auth::user();
        $player = $room->players()->where('user_id', $user -> id)->first();
        if ($player) {
            $result = $room->players()->detach($user -> id); //detach solo borra la relacion entre las filas.
            if ($user->id == $room->host_id) { //THEY ARE THE SAME WHY NO WORK
                $room->host_id = $room->players()->first()->id ?? $player->id;
                $room->save();
            }
            $this->completeRoomIfEmpty($room); //if room is empty, we complete it, since we don't want to show an empty room

            return response()->json(['success' => $result]);
        } else {
            return response()->json(['error' => 'You aren\'t in this room'], 400);
        }
    }

    public function kickUser(KickUser $request) { //Not implemented, probablly not going to get implemented aswell
        return response()->json(['error' => 'Not implemented'], 404);
        $room = Room::find($request->room_id);
        $user = Auth::user();
        $host_id = $room -> host_id;
        $player = $room->players()->where('user_id', $request -> player_id)->exists();
        if ($user -> id == $host_id) {
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
    /**
     * Function to delete a room if its empty
     */
    private function completeRoomIfEmpty(Room $room) {
        if ($room->players()->count() <= 0) {
            $room->state = 'completed';
            $room->save();
        }
    }
}

