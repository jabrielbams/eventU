<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        // Order by date descending by default
        $query->orderBy('date', 'desc');

        return response()->json($query->paginate(6));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event);
    }
}
