<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'organization']);

        // Filter by Category
        if ($request->has('category') && $request->category != 'all') {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)->orWhere('name', $categorySlug);
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination
        $events = $query->orderBy('event_date', 'asc')->paginate(6);

        // Transform Collection
        $events->getCollection()->transform(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'date' => $event->event_date->format('Y-m-d'), // Map event_date to date
                'time' => $event->event_time->format('H:i'),   // Map event_time to time
                'location' => $event->location,
                'category' => $event->category ? $event->category->name : 'Uncategorized', // Map relation to string
                'image' => $event->banner, // Map banner to image
                'organizer' => $event->organization ? $event->organization->name : 'Unknown', // Map relation to string
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

        // Transform Single Object
        $data = [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'info' => $event->description, // Validating check for detail view requirements
            'date' => $event->event_date->format('Y-m-d'),
            'time' => $event->event_time->format('H:i'),
            'location' => $event->location,
            'category' => $event->category ? $event->category->name : 'Uncategorized',
            'image' => $event->banner,
            'organizer' => $event->organization ? $event->organization->name : 'Unknown',
            'registration_link' => $event->registration_link
        ];

        return response()->json($data);
    }
}
