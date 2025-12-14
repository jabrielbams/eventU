<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\AuthenticateWithToken;
use App\Models\Event;
use App\Models\Category;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('Landing Page');

// Authentication
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');


// Authenticated routes (using bearer token)
Route::middleware(AuthenticateWithToken::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard - Industrial Control Panel
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Organizer-only routes
    Route::middleware(CheckUserRole::class.':organizer')->group(function () {
        Route::post('/events', [EventController::class, 'store'])->name('events.store');

        Route::get('/events/create', function () {
            $categories = Category::all();
            return view('events.create', compact('categories'));
        })->name('events.create');

        Route::get('/events/{id}/edit', function ($id) {
            $event = Event::findOrFail($id);
            $categories = Category::all();
            return view('events.edit', compact('event', 'categories'));
        })->name('events.edit');

        Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
        Route::get('/events/{id}/registrants', [EventController::class, 'registrants'])->name('events.registrants');
        Route::delete('/events/{eventId}/registrants/{userId}', [EventController::class, 'removeRegistrant'])->name('events.registrants.remove');
    });

    // Event Catalog (public for authenticated users)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{id}/register', [EventController::class, 'register'])->name('events.register');

    // User Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    // Student-only routes
    Route::middleware(CheckUserRole::class.':student')->group(function () {
        // Bookmark routes
        Route::post('/events/{id}/bookmark', [EventController::class, 'bookmarkEvent'])->name('events.bookmark');
        Route::delete('/events/{id}/bookmark', [EventController::class, 'unbookmarkEvent'])->name('events.unbookmark');
    });
});
