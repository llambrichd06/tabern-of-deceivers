<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index() {
        $leaderboard = Leaderboard::all();
        return $leaderboard;
        }
        
        public function show(Leaderboard $leaderboard) {
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

    public function getBestUsers() {
        $bestUsers = User::from('users u')
            ->join('leaderboard l', 'u.id', '=', 'l.user_id')
            ->orderBy('l.wins')
            ->limit(3)
            ->get();
    }
    
    public function store(Request $request) {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'points' => ['nullable', 'min:0'],
            'wins' => ['required', 'min:0'],
            'losses' => ['required', 'min:0'],
            'matches' => ['required', 'min:0'],
        ]);
        $leaderboard = Leaderboard::create($data);
        return $leaderboard;
        
    }

    public function update(Request $request, Leaderboard $leaderboard) {
        $leaderboard = Leaderboard::find($leaderboard->id);
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'points' => ['nullable', 'min:0'],
            'wins' => ['nullable', 'min:0'],
            'losses' => ['nullable', 'min:0'],
            'matches' => ['nullable', 'min:0'],
        ]);
        $leaderboard->user_id = $data->user_id ?? $leaderboard->user_id;
        $leaderboard->points = $data->points ?? $leaderboard->points;
        $leaderboard->wins = $data->wins ?? $leaderboard->wins;
        $leaderboard->losses = $data->losses ?? $leaderboard->losses;
        $leaderboard->matches = $data->matches ?? $leaderboard->matches;
        
        $leaderboard->save();
        return $leaderboard;
    }

    public function destroy(Leaderboard $leaderboard) {
        $leaderboard->delete();
        return "deleted successfully";
    }
}
