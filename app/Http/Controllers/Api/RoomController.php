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
}
