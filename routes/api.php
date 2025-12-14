<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserRole;

// Authentication API
Route::post('/register', [AuthController::class, 'apiRegister']);
Route::post('/login', [AuthController::class, 'apiLogin']);

// Public API - Get events and organizations
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::get('/organizations', [OrganizationController::class, 'index']);
Route::get('/organizations/{id}', [OrganizationController::class, 'show']);
Route::get('/organizations/{id}/members', [OrganizationController::class, 'getMembers']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'apiLogout']);

    // Organizer-only API routes
    Route::middleware(CheckUserRole::class.':organizer')->group(function () {
        // Event CRUD
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{id}', [EventController::class, 'update']);
        Route::delete('/events/{id}', [EventController::class, 'destroy']);

        // Event status management
        Route::patch('/events/status', [EventController::class, 'updateStatus']);

        // Event registrants management
        Route::get('/events/{id}/registrants', [EventController::class, 'getRegistrants']);
        Route::delete('/events/{eventId}/registrants/{userId}', [EventController::class, 'removeRegistrant']);

        // Organization CRUD
        Route::post('/organizations', [OrganizationController::class, 'store']);
        Route::put('/organizations/{id}', [OrganizationController::class, 'update']);
        Route::delete('/organizations/{id}', [OrganizationController::class, 'destroy']);

        // Organization user management
        Route::post('/organizations/users/approve', [OrganizationController::class, 'approveUser']);
        Route::post('/organizations/users/reject', [OrganizationController::class, 'rejectUser']);
    });
});

