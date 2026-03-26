<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'auth:sanctum'], function() {

    Route::apiResource('users', UserController::class);
    Route::post('users/updateimg', [UserController::class,'updateimg']);


    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('roles', RoleController::class);
    
    Route::get('/leaderboards/getBestUsers', [LeaderboardController::class, 'getBestUsers']);
    Route::get('/leaderboards/paginatedLeaderboards', [LeaderboardController::class, 'indexPaginated']);
    Route::post('/leaderboards/updateLeaderboards', [LeaderboardController::class, 'updateLeaderboards']);
    Route::apiResource('leaderboards', LeaderboardController::class);

    Route::get('/rooms/leaveRoom', [RoomController::class, 'leaveRoom']);
    Route::get('/rooms/changePrivate', [RoomController::class, 'changePrivate']);
    Route::get('/rooms/transferOwnership', [RoomController::class, 'transferOwnership']);
    Route::get('/rooms/kickUser', [RoomController::class, 'kickUser']);
    Route::get('/rooms/joinRoomWithCode', [RoomController::class, 'joinRoomWithCode']);
    Route::get('/rooms/joinPublicRoom', [RoomController::class, 'joinPublicRoom']);
    Route::get('/rooms/openRooms', [RoomController::class, 'openRooms']);
    Route::apiResource('rooms', RoomController::class);

    Route::post('/messages/sent/{room}', [MessageController::class, 'sent']);

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

Route::get('category-list', [CategoryController::class, 'getList']);

