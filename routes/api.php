<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController; // Added by instruction

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'registerApi']);

// Event API Routes // Added by instruction
Route::get('/events', [EventController::class, 'index']); // Added by instruction
Route::get('/events/{id}', [EventController::class, 'show']); // Added by instruction

Route::get('/user', function (Request $request) { // Added by instruction
    return $request->user();
})->middleware('auth:sanctum'); // Added by instruction

Route::middleware('web')->group(function () {
    Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
});
