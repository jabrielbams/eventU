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
Route::post('logout', [\App\Http\Controllers\LoginController::class, 'logout'])->name('logout');

Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

// Event Catalog
Route::get('/events', function () {
    return view('events.index');
})->name('events.index');

// Event Creation (Moved before dynamic ID route to prevent conflict)
Route::get('/events/create', function () {
    $categories = \App\Models\Category::all();
    return view('events.create', compact('categories'));
})->middleware('auth')->name('events.create');

Route::get('/events/{id}', function ($id) {
    return view('events.show', ['id' => $id]);
})->name('events.show');


// Organization Profile
Route::get('/organizations/create', function () {
    if (auth()->user()->role !== 'organizer') {
        abort(403, 'Unauthorized action.');
    }
    return view('organizations.create');
})->middleware('auth')->name('organizations.create');

// User Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});


// Internal API route for creating organizations (Using web middleware for Session Auth)
Route::post('/api/organizations', [\App\Http\Controllers\Api\OrganizationController::class, 'store'])
    ->middleware('auth')
    ->name('api.organizations.store');

Route::get('/organizations/{id}', function ($id) {
    return view('organizations.show', ['id' => $id]);
})->name('organizations.show');
