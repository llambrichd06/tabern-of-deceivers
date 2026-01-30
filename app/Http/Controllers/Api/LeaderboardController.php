<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index() {
        $leaderboard = Leaderboard::all();
        return $leaderboard;
    }

    public function updateLeaderboards(Request $request) {
        $leaderboard = Leaderboard::where('user_id', $request->user_id)->first();
        if (!$leaderboard) {
            $leaderboard = new Leaderboard();
            $leaderboard->user_id = $request->user_id;
            $leaderboard->wins = 0;
            $leaderboard->losses = 0;
            $leaderboard->matches = 0;
            $leaderboard->points = 0;

        }
            $leaderboard->matches += 1; 
            switch ($request->action) {
                case 'win':
                    $leaderboard->wins += 1; 
                    break;
                case 'loss':
                    $leaderboard->losses += 1;
                    break;
                default:
                    return response('action value must be win or loss', 412);
                    break;
            }
            $leaderboard->save();
            return $leaderboard;
    }
    public function show(Leaderboard $leaderboard) {
        return $leaderboard;
    }
    public function destroy(Leaderboard $leaderboard) {
        $leaderboard->delete();
        return "deleted successfully";
    }
}
