<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaderboardController extends Controller
{
    public function index() {
        $leaderboard = Leaderboard::with('user')->get();
        return $leaderboard;
    }
    
    public function show(Leaderboard $leaderboard) {
        return response()->json([
            'data' => $leaderboard,
        ]);
    }

    public function indexPaginated(Request $request) {
        $leaderboard = Leaderboard::with('user')->orderByDesc('wins')->paginate($request->rows ?? 10);
        return $leaderboard;
    }

    public function getBestUsers() { //esto deberia estar en users????
        $bestUsers = Leaderboard::with('user')
            ->orderByDesc('wins', 'points')
            ->limit(3)
            ->get();
        return response()->json([
            'leaderboards' => $bestUsers,
            ]);
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
        $leaderboard->user_id = $data['user_id'] ?? $leaderboard->user_id;
        $leaderboard->points = $data['points'] ?? $leaderboard->points;
        $leaderboard->wins = $data['wins'] ?? $leaderboard->wins;
        $leaderboard->losses = $data['losses'] ?? $leaderboard->losses;
        $leaderboard->matches = $data['matches'] ?? $leaderboard->matches;
        $leaderboard->save();
        return $leaderboard;
    }

    public function destroy(Leaderboard $leaderboard) {
        $leaderboard->delete();
        return "deleted successfully";
    }
}
