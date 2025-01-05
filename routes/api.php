<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MoveController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/create-game', [GameController::class, 'createGame']);
Route::post('/play-game/{gameId}', [GameController::class, 'playGame']);
Route::get('/moves', [MoveController::class, 'index']);
