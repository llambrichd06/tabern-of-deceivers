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
        $this->authorize('leaderboard-list');
        $leaderboard = Leaderboard::with('user')->get();
        return response()->json([ 'leaderboard' => $leaderboard ]);
    }
    
    public function show(Leaderboard $leaderboard) {
        $this->authorize('leaderboard-list');
        return response()->json([
            'leaderboard' => $leaderboard,
        ]);
    }

    public function indexPaginated(Request $request) {
        //FALTA VALIDAR REQUEST
        $query = Leaderboard::with('user');

        // data filtering
        if ($request->filled('filterValue')) {
            $filterVal = $request->filterValue;
            $filterField = $request->filterField;
            //since user is in a different table we have a different query
            if ($filterField === 'user.name') {
                $query->whereHas('user', function($q) use ($filterVal) {
                    $q->where('name', 'like', "%{$filterVal}%");
            });
            } else {
                $query->where($filterField, 'like', "%{$filterVal}%");
            }
        }

        // data sorting (after filtering so we sort the fliter)
        $direction = $request->sortOrder == 1 ? 'asc' : 'desc';
        $sortField = $request->sortField ?? 'points';

        if ($sortField === 'user.name') {
            $query->join('users', 'leaderboards.user_id', '=', 'users.id')
                  ->orderBy('users.name', $direction)
                  ->select('leaderboards.*');
        } else {
            $query->orderBy($sortField, $direction);
        }

        return response()->json([ 'leaderboard' => $query->paginate($request->rows ?? 10) ]);
    }

    public function getBestUsers() {
        $bestUsers = Leaderboard::with('user')
            ->orderByDesc('wins', 'points')
            ->limit(3)
            ->get();
        return response()->json([
            'leaderboards' => $bestUsers,
            ]);
    }
    
    public function store(Request $request) {
        $this->authorize('leaderboard-create');
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'points' => ['nullable', 'min:0'],
            'wins' => ['required', 'min:0'],
            'losses' => ['required', 'min:0'],
            'matches' => ['required', 'min:0'],
        ]);
        $leaderboard = Leaderboard::create($data);
        return response()->json([ 'leaderboard' => $leaderboard ]);
        
    }

    public function update(Request $request, Leaderboard $leaderboard) {
        $this->authorize('leaderboard-edit');
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
        return response()->json([ 'leaderboard' => $leaderboard ]);
    }

    public function destroy(Leaderboard $leaderboard) {
        $this->authorize('leaderboard-delete');
        $leaderboard->delete();
        return response()->json([ 'data' => 'deleted successfully' ]);
    }
}
