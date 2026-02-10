<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index() {
        $room = Room::all();
        return $room;
    }

    public function show(Room $room) {
        $host = $room->host();
        return response()->json([ //this doesent work currently
            'room_owner' => $host
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
     
        $room->room_code = $data->room_code ?? $room->room_code;
        $room->state = $data->state ?? $room->state;
     
        $room->save();
        return $room;
    }

    public function destroy(Room $room) {
        $room->delete();
        return "deleted successfully";
    }
}

