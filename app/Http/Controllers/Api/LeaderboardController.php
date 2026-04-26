<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Leaderboards\IndexPaginatedRequest;
use App\Http\Requests\Leaderboards\StoreLeaderboardRequest;
use App\Http\Requests\Leaderboards\UpdateLeaderboardRequest;
use App\Models\Leaderboard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;

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

    public function indexPaginated(IndexPaginatedRequest $request) {
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
            ->orderByDesc('wins')
            ->orderByDesc('points')
            ->limit(3)
            ->get()
            ->map(function ($leaderboard) {
                return [
                    'id' => $leaderboard->id,
                    'user_id' => $leaderboard->user_id,
                    'wins' => $leaderboard->wins,
                    'losses' => $leaderboard->losses,
                    'matches' => $leaderboard->matches,
                    'points' => $leaderboard->points,
                    'user' => [
                        'id' => $leaderboard->user?->id,
                        'name' => $leaderboard->user?->name,
                        'email' => $leaderboard->user?->email,
                        'avatar' => $leaderboard->user?->getFirstMediaUrl('images-users') ?: asset('images/placeholder.png'),
                    ],
                ];
            });

        return response()->json([
            'leaderboards' => $bestUsers,
        ]);
    }
    
    public function store(StoreLeaderboardRequest $request) {
        $this->authorize('leaderboard-create');
        $data = $request->validated();
        $leaderboard = Leaderboard::create($data);
        return response()->json([ 'leaderboard' => $leaderboard ]);
        
    }

    public function update(UpdateLeaderboardRequest $request, Leaderboard $leaderboard) {
        $this->authorize('leaderboard-edit');
        $leaderboard = Leaderboard::find($leaderboard->id);
        $data = $request->validated();
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
