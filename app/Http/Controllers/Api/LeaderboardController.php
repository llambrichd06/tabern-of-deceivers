<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index() {
        
    }
    public function show(Leaderboard $leaderboard) {
        return $leaderboard;
    }
    public function destroy(Leaderboard $leaderboard) {
        $leaderboard->delete();
        return "deleted successfully";
    }
}
