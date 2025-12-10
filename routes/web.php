<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Localization
Route::get('lang/{locale}', [\App\Http\Controllers\LocaleController::class, 'setLocale'])->name('lang.switch');

// Authentication
Route::get('register', [\App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
