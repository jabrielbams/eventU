<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    /**
     * Handle the event registration.
     */
    public function store(Request $request, $id)
    {
        // Check if user is authenticated
        // Using 'sanctum' guard if API, or default if session. 
        // We will try to get the user from the request or Auth facade.
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $event = Event::findOrFail($id);

        // Check if duplicate registration
        if ($event->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You are already registered.'], 409);
        }

        // Attach user to event
        $event->users()->attach($user->id);

        // Get the ticket ID (pivot table ID)
        $ticket = \Illuminate\Support\Facades\DB::table('event_user')
                    ->where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->first();

        return response()->json([
            'message' => 'Registration successful!', 
            'status' => 'registered',
            'ticket_id' => 'TICKET-' . $ticket->id
        ], 201);
    }

    /**
     * Check registration status.
     */
    public function status(Request $request, $id)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['registered' => false]);
        }

        $event = Event::findOrFail($id);
        $isRegistered = $event->users()->where('user_id', $user->id)->exists();

        return response()->json(['registered' => $isRegistered]);
    }
}
