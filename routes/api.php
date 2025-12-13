<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventRegistrationController; // Added this line

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'registerApi']);

Route::middleware('web')->group(function () {
    Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
});

// Added API routes for event registration
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/events', [\App\Http\Controllers\Api\EventController::class, 'store']);
    Route::post('/events/{id}/register', [EventRegistrationController::class, 'store']);
    Route::get('/events/{id}/status', [EventRegistrationController::class, 'status']);
});

Route::get('/organizations/{id}', [\App\Http\Controllers\Api\OrganizationController::class, 'show']);

Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);
Route::get('/events/{id}', [\App\Http\Controllers\Api\EventController::class, 'show']);
