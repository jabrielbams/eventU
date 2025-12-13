<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'registerApi']);

Route::middleware('web')->group(function () {
    Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
});



Route::get('/organizations/{id}', [\App\Http\Controllers\Api\OrganizationController::class, 'show']);

Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);
Route::get('/events/{id}', [\App\Http\Controllers\Api\EventController::class, 'show']);
