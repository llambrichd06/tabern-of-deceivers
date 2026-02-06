<?php

namespace App\Http\Controllers;

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
        return $room;
    }
    
    // public function kickPlayer() //idea a futur
    
    public function store(Request $request) {
        $data = $request->validate([
            'room_code' => ['required', 'size:8'],
            'state' => ['required', 'in:lobby, on_going, completed']
        ]);
        $room = Room::create($data);
        return $room;
    }

    public function update(Request $request, Room $room) {
        $room = Room::find($room->id);
        $data = $request->validate([
            'room_code' => ['required', 'size:8'],
            'state' => ['required', 'in:lobby, on_going, completed']
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

