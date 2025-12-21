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
            $query = Event::with(['category', 'organization', 'user'])
                ->whereIn('status', ['published', 'completed']); // Show published and completed events

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
            // 1. Check if user is organizer and has an organization
            $user = Auth::user();

            if (!$user->isOrganizer()) {
                return response()->json([
                    'message' => 'Only organizers can create events.'
                ], Response::HTTP_FORBIDDEN);
            }

            $organization = $user->organizations()
                ->wherePivot('status', 'approved')
                ->first();

            if (!$organization) {
                return response()->json([
                    'message' => 'Kamu harus bergabung dengan organisasi terlebih dahulu.'
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
            $validated['organization_id'] = $organization->id;
            $validated['user_id'] = $user->id;

            $event = Event::create($validated);

            // 5. Response
            return (new EventResource($event))
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
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

    /**
     * Get all registered users for an event (Organizer only).
     */
    public function getRegistrants($id)
    {
        try {
            $event = Event::with(['users' => function($query) {
                $query->select('users.id', 'users.name', 'users.email', 'users.role')
                      ->withPivot('created_at');
            }])->find($id);

            if (!$event) {
                return response()->json(['message' => 'Event tidak ditemukan'], Response::HTTP_NOT_FOUND);
            }

            $registrants = $event->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'registered_at' => $user->pivot->created_at->format('d M Y H:i'),
                ];
            });

            return response()->json([
                'message' => 'Registrants retrieved successfully',
                'data' => $registrants,
                'total' => $registrants->count(),
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data peserta',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove a user from event registration (Organizer only).
     */
    public function removeRegistrant($eventId, $userId)
    {
        try {
            $event = Event::find($eventId);

            if (!$event) {
                return response()->json(['message' => 'Event tidak ditemukan'], Response::HTTP_NOT_FOUND);
            }

            // Check if user is registered
            $isRegistered = $event->users()->where('user_id', $userId)->exists();

            if (!$isRegistered) {
                return response()->json(['message' => 'Pengguna tidak terdaftar di event ini'], Response::HTTP_NOT_FOUND);
            }

            // Remove registration
            $event->users()->detach($userId);

            return response()->json([
                'message' => 'Peserta berhasil dihapus dari event',
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus peserta',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update event status (Organizer only).
     * Available statuses: draft, published, cancelled, completed
     */
    public function updateStatus(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'event_id' => 'required|integer|exists:event,id',
                'status' => 'required|in:draft,published,cancelled,completed'
            ]);

            $event = Event::findOrFail($validated['event_id']);

            // Check if user owns this event
            $user = Auth::user();
            if ($event->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses untuk mengubah status event ini'
                ], Response::HTTP_FORBIDDEN);
            }

            // Update status
            $event->status = $validated['status'];
            $event->save();

            return response()->json([
                'message' => 'Status event berhasil diubah',
                'data' => new EventResource($event)
            ], Response::HTTP_OK);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah status event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Register the authenticated user for an event.
     */
    public function registerForEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            $user = Auth::user();

            // Check if event is published
            if ($event->status !== 'published') {
                return response()->json([
                    'message' => 'Event ini belum dipublikasikan'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Check if already registered
            if ($event->users()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'message' => 'Anda sudah terdaftar di event ini'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Register user
            $event->users()->attach($user->id);

            return response()->json([
                'message' => 'Berhasil mendaftar ke event',
                'data' => new EventResource($event)
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendaftar ke event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Bookmark an event for the authenticated user.
     */
    public function bookmarkEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            $user = Auth::user();

            // Check if already bookmarked
            if ($user->bookmarks()->where('event_id', $event->id)->exists()) {
                return response()->json([
                    'message' => 'Event sudah dibookmark'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Create bookmark
            $user->bookmarks()->create(['event_id' => $event->id]);

            return response()->json([
                'message' => 'Event berhasil dibookmark',
                'data' => new EventResource($event)
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal bookmark event',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove bookmark from an event for the authenticated user.
     */
    public function unbookmarkEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            $user = Auth::user();

            $bookmark = $user->bookmarks()->where('event_id', $event->id)->first();

            if (!$bookmark) {
                return response()->json([
                    'message' => 'Bookmark tidak ditemukan'
                ], Response::HTTP_NOT_FOUND);
            }

            $bookmark->delete();

            return response()->json([
                'message' => 'Bookmark berhasil dihapus'
            ], Response::HTTP_OK);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Event tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus bookmark',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all bookmarked events for the authenticated user.
     */
    public function getBookmarkedEvents(Request $request)
    {
        try {
            $user = Auth::user();

            // Get event IDs from bookmarks
            $eventIds = $user->bookmarks()->pluck('event_id');

            // Query events with same filters as index
            $query = Event::with(['category', 'organization', 'user'])
                ->whereIn('id', $eventIds)
                ->whereIn('status', ['published', 'completed']);

            // Handling Search
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                });
            }

            // Order by latest
            $events = $query->orderBy('date', 'desc')->paginate(10);

            return EventResource::collection($events);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil bookmarked events',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
