<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//This endpoint is up here because if its below the apiResource for leaderboard controller, auth sanctum gets applied to it
//even if it isn't inside the route group for some reason
Route::get('/leaderboards/bestUsers', [LeaderboardController::class, 'getBestUsers']); 

Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::apiResource('users', UserController::class);
    Route::post('users/updateimg', [UserController::class,'updateimg']);


    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);
    
    Route::get('/leaderboards/paginatedLeaderboards', [LeaderboardController::class, 'indexPaginated']);
    Route::post('/leaderboards/updateLeaderboards', [LeaderboardController::class, 'updateLeaderboards']);
    Route::apiResource('leaderboards', LeaderboardController::class);

    Route::post('/rooms/leaveRoom', [RoomController::class, 'leaveRoom']);
    Route::post('/rooms/changePrivate/{room}', [RoomController::class, 'changePrivate']);
    Route::post('/rooms/transferOwnership', [RoomController::class, 'transferOwnership']);
    // Route::get('/rooms/kickUser', [RoomController::class, 'kickUser']); //Not implemented
    Route::post('/rooms/joinRoomWithCode', [RoomController::class, 'joinRoomWithCode']);
    Route::post('/rooms/joinPublicRoom/{room}', [RoomController::class, 'joinPublicRoom']);
    Route::get('/rooms/openRooms', [RoomController::class, 'openRooms']);
    Route::post('/rooms/hostRoom', [RoomController::class, 'hostRoom']);
    Route::apiResource('rooms', RoomController::class);

    Route::post('/messages/sent/{room}', [MessageController::class, 'sent']);

    Route::get('/games/gameState/{game}', [GameController::class, 'getUserGameStateById']);
    Route::post('/games/startGame/{room}', [GameController::class, 'startGame']);
    Route::post('/games/playCards', [GameController::class, 'playCards']);
    Route::post('/games/callLie/{game}', [GameController::class, 'callLie']);
    Route::apiResource('games', GameController::class);

    Route::get('role-list', [RoleController::class, 'getList']);
    Route::get('role-permissions/{id}', [PermissionController::class, 'getRolePermissions']);
    Route::put('/role-permissions', [PermissionController::class, 'updateRolePermissions']);
    Route::apiResource('permissions', PermissionController::class);
    
    Route::get('/user', [ProfileController::class, 'user']);
    Route::get('/user/signin', [ProfileController::class, 'user']);
    Route::put('/user', [ProfileController::class, 'update']);

    Route::get('abilities', function(Request $request) {
        return $request->user()->roles()->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values()
            ->toArray();
    });

});

