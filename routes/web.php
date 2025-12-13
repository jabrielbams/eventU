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

Route::get('login', [\App\Http\Controllers\LoginController::class, 'showLogin'])->name('login');

Route::get('/events', function () {
    return view('events.index');
})->name('events.index');

Route::get('/events/{id}', function ($id) {
    return view('events.show', ['id' => $id]);
})->name('events.show');

Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');
