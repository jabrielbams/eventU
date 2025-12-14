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

        // 1. Fetch Assigned/Joined Events (The "Mission Roster")
        // Assuming relationship 'events' exists for joined events
        $assignedEvents = $user->events()->with('category')->orderBy('date', 'asc')->get();

        // 2. Urgent: Next Mission (Closest Upcoming Event)
        $nextEvent = $user->events()
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->first();

        // Calculate days remaining if event exists
        $daysRemaining = null;
        if ($nextEvent) {
             $daysRemaining = ceil(now()->diffInDays($nextEvent->date, false));
             if ($daysRemaining < 0) $daysRemaining = 0; // Should be handled by query, but safe fallbacks
        }

        $accountHealth = 'ACTIVE';

        return view('dashboard.index', compact(
            'user',
            'assignedEvents',
            'nextEvent',
            'daysRemaining',
            'accountHealth'
        ));
    }
}
