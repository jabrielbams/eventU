<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\Api\OrganizationController as ApiOrganizationController;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\AuthenticateWithToken;
use App\Models\Event;
use App\Models\Category;

// Public routes
Route::get('/', function () {
    // TODO: Optimize this query for high traffic (caching) and add error handling if no events are found.
    // Fetch 3 upcoming events
    $featuredEvents = \App\Models\Event::with('organization')
        ->where('date', '>=', now())
        ->orderBy('date', 'asc')
        ->take(3)
        ->get();
    return view('welcome', compact('featuredEvents'));
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
            // Announcement CRUD (organizer)
            Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
            Route::get('/announcements/create', [\App\Http\Controllers\AnnouncementController::class, 'create'])->name('announcements.create');
            Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
            Route::get('/announcements/{id}/edit', [\App\Http\Controllers\AnnouncementController::class, 'edit'])->name('announcements.edit');
            Route::put('/announcements/{id}', [\App\Http\Controllers\AnnouncementController::class, 'update'])->name('announcements.update');
            Route::delete('/announcements/{id}', [\App\Http\Controllers\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
        Route::get('/organizer/events', [EventController::class, 'organizerEvents'])->name('organizer.events');

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
        Route::patch('/events/{id}/status', [EventController::class, 'updateStatus'])->name('events.updateStatus');
        Route::get('/events/{id}/registrants', [EventController::class, 'registrants'])->name('events.registrants');
        Route::delete('/events/{eventId}/registrants/{userId}', [EventController::class, 'removeRegistrant'])->name('events.registrants.remove');

        // Add this route for deleting events
        Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

        // Organization CRUD routes
        Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
        Route::get('/organizations/create', [OrganizationController::class, 'create'])->name('organizations.create');
        Route::post('/organizations', [OrganizationController::class, 'store'])->name('organizations.store');
        Route::get('/organizations/{id}/edit', [OrganizationController::class, 'edit'])->name('organizations.edit');
        Route::put('/organizations/{id}', [OrganizationController::class, 'update'])->name('organizations.update');
        Route::delete('/organizations/{id}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');

        // Organization member management routes
        Route::get('/organizations/manage', [OrganizationController::class, 'manage'])->name('organizations.manage');
        Route::post('/organizations/{organizationId}/approve/{userId}', [OrganizationController::class, 'approveUser'])->name('organizations.approve');
        Route::post('/organizations/{organizationId}/reject/{userId}', [OrganizationController::class, 'rejectUser'])->name('organizations.reject');
        Route::delete('/organizations/{organizationId}/remove/{userId}', [OrganizationController::class, 'removeUser'])->name('organizations.remove');
    });

    // Event Catalog (public for authenticated users)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{id}/register', [EventController::class, 'register'])->name('events.register');

    // Organization Profile
    Route::get('/organizations/{id}', function ($id) {
        return view('organizations.show', ['organizationId' => $id]);
    })->name('organizations.show');

    // User Profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    // Comment CRUD routes
    Route::post('/events/{eventId}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Review CRUD routes
    Route::post('/events/{eventId}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Student-only routes
    Route::middleware(CheckUserRole::class.':student')->group(function () {
            // Student: View announcements for registered events
            Route::get('/my-announcements', [\App\Http\Controllers\AnnouncementController::class, 'studentIndex'])->name('announcements.student');
        // Bookmark routes
        Route::post('/events/{id}/bookmark', [EventController::class, 'bookmarkEvent'])->name('events.bookmark');
        Route::delete('/events/{id}/bookmark', [EventController::class, 'unbookmarkEvent'])->name('events.unbookmark');
    });
});
