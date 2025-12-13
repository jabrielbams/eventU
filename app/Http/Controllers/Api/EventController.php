<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{

    public function index(Request $request)
    {
        // Handling Request
        try {
            $query = Event::with(['category', 'organization', 'user']);

            // Handling Search
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            }

            // Handling Category Filter
            if ($request->has('category') && $request->input('category') !== 'all') {
                $categorySlug = $request->input('category');
                $query->whereHas('category', function ($q) use ($categorySlug) {
                    $q->where('slug', $categorySlug);
                });
            }

            // Order by latest
            $events = $query->orderBy('date', 'desc')->paginate(10);

            return EventResource::collection($events);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memproses data event',
                'error' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // // Handling Search
        // $query = Event::with(['category', 'organization', 'user']);
        // if ($request->has('search')) {
        //     $search = $request->input('search');
        //     $query->where(function ($q) use ($search) {
        //         $q->where('title', 'like', "%{$search}%")
        //         ->orWhere('description', 'like', "%{$search}%")
        //         ->orWhere('location', 'like', "%{$search}%");
        //     });
        // }

        // // Handling Category Filter
        // if ($request->has('category') && $request->input('category') !== 'all') {
        //     $categorySlug = $request->input('category');
        //     $query->whereHas('category', function ($q) use ($categorySlug) {
        //         $q->where('slug', $categorySlug);
        //     });
        // }

        // // Order by latest
        // $events = $query->orderBy('date', 'desc')->paginate(10);

        // return response()->json($events);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // 1. Check Organization Profile
            $user = Auth::user();
            if (!$user->organization) {
                return response()->json([
                    'message' => 'You must create an Organization Profile first.'
                ], Response::HTTP_FORBIDDEN);
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

            // 4. Create Event
            // Adding organization_id and user_id to the data
            $validated['organization_id'] = $user->organization->id;
            $validated['user_id'] = $user->id;

            $event = Event::create($validated);

            // 5. Response
            return new EventResource($event);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal membuat event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // $user = Auth::user();
        // if (!$user->organization) {
        //     return response()->json([
        //         'message' => 'You must create an Organization Profile first.'
        //     ], 403);
        // }

        // // 2. Validation
        // $validated = $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'location' => 'required|string|max:255',
        //     'date' => 'required|date',
        //     'time' => 'required|date_format:H:i',
        //     'category_id' => 'required|exists:category,id',
        //     'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        // ]);

        // // 3. File Handling
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('events', 'public');
        //     $validated['image'] = $path;
        // }

        // // Category is already validated as ID
        // // unset($validated['category']);

        // // 4. Create Event
        // // Add organization_id and user_id to the data
        // $validated['organization_id'] = $user->organization->id;
        // $validated['user_id'] = $user->id;

        // // Status defaults to 'draft' in database, but we can explicitly set it if needed.
        // // For now, relying on default.

        // $event = Event::create($validated);

        // // 5. Response
        // return response()->json($event, 201);
    }

    public function show($id)
    {
        try {
            $event = Event::with(['category', 'organization'])->find($id);

            if (!$event) {
                return response()->json(['message' => 'Event tidak ditemukan'], Response::HTTP_NOT_FOUND);
            }

            return new EventResource($event);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil detail event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $event = Event::find($id);
            if (!$event) {
                return response()->json(['message' => 'Event tidak ditemukan'], Response::HTTP_NOT_FOUND);
            }
            $user = Auth::user();
            if ($event->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            // 2. Validation
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'location' => 'sometimes|required|string|max:255',
                'date' => 'sometimes|required|date',
                'time' => 'sometimes|required|date_format:H:i',
                'category_id' => 'sometimes|required|exists:category,id',
                'image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
                'status' => 'sometimes|required|in:draft,published,cancelled',
            ]);
            // 3. File Handling
            if ($request->hasFile('image')) {
                if ($event->image) {
                    Storage::disk('public')->delete($event->image);
                }
                $path = $request->file('image')->store('events', 'public');
                $validated['image'] = $path;
            }
            // 4. Update Event
            $event->update($validated);
            // 5. Response
            return new EventResource($event);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $event = Event::find($id);
            if (!$event) {
                return response()->json(['message' => 'Event tidak ditemukan'], Response::HTTP_NOT_FOUND);
            }
            $user = Auth::user();
            if ($event->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }
            // Delete associated image
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $event->delete();
            return response()->json(['message' => 'Event berhasil dihapus'], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
