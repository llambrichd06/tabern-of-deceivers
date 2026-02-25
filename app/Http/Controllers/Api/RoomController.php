<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
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
            ->orderBy('created_at')
            ->paginate(4); //NEED TO PAGINATE THIS TO LIKE 4
        return response()->json([
            'Public Rooms' => $pubRooms,
        ]);
    }

    public function show(Room $room) {
        $room->load('host');
        return response()->json([
            'data' => $room,
        ]);
    }
    
    // public function kickPlayer() //idea a futur
    
    public function store(Request $request) {
        $data = $request->validate([
            'room_code' => ['required', 'size:8'],
            'state' => ['required', 'in:lobby,on_going,completed'],
            'host_id' => ['required', 'exists:users,id'],
            'private' => ['required', 'in:0,1']
        ]);
        $room = Room::create($data);
        return $room;
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

