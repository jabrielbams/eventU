<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'registerApi']);

Route::middleware('web')->group(function () {
    Route::post('/login', [\App\Http\Controllers\LoginController::class, 'login']);
});
