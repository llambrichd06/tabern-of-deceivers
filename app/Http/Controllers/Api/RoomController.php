<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomUsers;
use Illuminate\Http\Request;
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
}

