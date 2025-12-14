<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isStudent()) {
            // For students: Show registered events ordered by closest date
            $assignedEvents = $user->events()
                ->with('category')
                ->where('date', '>=', now())
                ->orderBy('date', 'asc')
                ->limit(5)
                ->get();

            // Next upcoming event
            $nextEvent = $assignedEvents->first();

            // Calculate days remaining if event exists
            $daysRemaining = null;
            if ($nextEvent) {
                $daysRemaining = ceil(now()->diffInDays($nextEvent->date, false));
                if ($daysRemaining < 0) $daysRemaining = 0;
            }

            $ctaRoute = 'events.index';
            $ctaText = 'Temukan Semua Event';

        } else {
            // For organizers: Show created events
            $assignedEvents = Event::where('user_id', $user->id)
                ->with(['category', 'users'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Next upcoming event from created events
            $nextEvent = Event::where('user_id', $user->id)
                ->where('date', '>=', now())
                ->orderBy('date', 'asc')
                ->first();

            // Calculate days remaining if event exists
            $daysRemaining = null;
            if ($nextEvent) {
                $daysRemaining = ceil(now()->diffInDays($nextEvent->date, false));
                if ($daysRemaining < 0) $daysRemaining = 0;
            }

            $ctaRoute = 'organizer.events';
            $ctaText = 'Lihat Semua Event';
        }

        $accountHealth = 'ACTIVE';

        return view('dashboard.index', compact(
            'user',
            'assignedEvents',
            'nextEvent',
            'daysRemaining',
            'accountHealth',
            'ctaRoute',
            'ctaText'
        ));
    }
}
