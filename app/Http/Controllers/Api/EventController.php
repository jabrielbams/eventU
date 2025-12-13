<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Organization Check
        $user = Auth::user();

        if (!$user->organization) {
            return response()->json([
                'message' => 'You must create an Organization Profile first.'
            ], 403);
        }

        // 2. Validation
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'category_id' => 'required|exists:category,id',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 3. File Handling
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image'] = $path;
        }

        // Category is already validated as ID
        // unset($validated['category']);

        // 4. Create Event
        // Add organization_id and user_id to the data
        $validated['organization_id'] = $user->organization->id;
        $validated['user_id'] = $user->id;
        
        // Status defaults to 'draft' in database, but we can explicitly set it if needed. 
        // For now, relying on default.

        $event = Event::create($validated);

        // 5. Response
        return response()->json($event, 201);
    }
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organization', 'user']);

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->has('category') && $request->input('category') !== 'all') {
            $categorySlug = $request->input('category');
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Order by latest
        $events = $query->orderBy('date', 'desc')->paginate(9);

        // Usage of API Resource is better, but doing inline transformation to match frontend expectation
        $events->getCollection()->transform(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'date' => $event->date->format('Y-m-d'), // Ensure Y-m-d for JS Date parsing
                'time' => $event->time->format('H:i'),
                'location' => $event->location,
                'image' => $event->image ? (str_starts_with($event->image, 'http') ? $event->image : asset('storage/' . $event->image)) : null,
                'category' => $event->category->name ?? 'Uncategorized', // Frontend expects string
                'status' => $event->status,
                'is_online' => $event->is_online,
                'organization' => $event->organization->name ?? 'Unknown',
            ];
        });

        return response()->json($events);

    }

    public function show($id)
    {
        $event = Event::with(['category', 'organization'])->find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'date' => $event->date->format('Y-m-d'),
            'time' => $event->time->format('H:i'),
            'location' => $event->location,
            'image' => $event->image ? (str_starts_with($event->image, 'http') ? $event->image : asset('storage/' . $event->image)) : null,
            'category' => $event->category->name ?? 'Uncategorized',
            'organization' => $event->organization->name ?? 'Unknown',
            'organizer' => $event->organization->name ?? 'Unknown', // Frontend expects 'organizer'
            'status' => $event->status,
            'is_online' => $event->is_online,
        ]);
    }
}
