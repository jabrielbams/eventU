<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\EventController as ApiEventController;
use App\Models\Event;
use App\Models\Category;
use Exception;

class EventController extends Controller
{
    /**
     * Display a listing of events by calling the API.
     */
    public function index(Request $request)
    {
        try {
            $apiController = new ApiEventController();

            // Check if user is filtering by bookmarks
            if ($request->input('category') === 'bookmark') {
                $response = $apiController->getBookmarkedEvents($request);
            } else {
                $response = $apiController->index($request);
            }

            $httpResponse = $response->toResponse($request);
            $responseData = json_decode($httpResponse->content(), true);

            $categories = Category::select('id', 'name', 'slug')->get();

            return view('events.index', [
                'events' => $responseData,
                'categories' => $categories
            ]);
        } catch (Exception $e) {
            $categories = Category::select('id', 'name', 'slug')->get();
            return view('events.index', [
                'events' => ['data' => [], 'links' => ['prev' => null, 'next' => null]],
                'categories' => $categories
            ]);
        }
    }

    /**
     * Display the specified event by calling the API.
     */
    public function show($id)
    {
        try {
            $apiController = new ApiEventController();
            $request = new Request();
            $response = $apiController->show($id);

            $httpResponse = $response->toResponse($request);
            $responseData = json_decode($httpResponse->content(), true);
            $event = $responseData['data'];

            return view('events.show', compact('event'));
        } catch (Exception $e) {
            return redirect()->route('events.index')
                ->with('error', 'Event tidak ditemukan.');
        }
    }

    /**
     * Store a newly created event by calling the API controller directly.
     */
    public function store(Request $request)
    {
        try {
            $apiController = new ApiEventController();
            $response = $apiController->store($request);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                $statusCode = $response->status();

                if ($statusCode === 201) {
                    $eventId = $data['data']['id'] ?? $data['id'] ?? null;

                    if ($eventId) {
                        return redirect()->route('events.show', $eventId)
                            ->with('success', 'Event berhasil dibuat!');
                    }

                    return redirect()->route('events.index')
                        ->with('success', 'Event berhasil dibuat!');

                } elseif ($statusCode === 422) {
                    return redirect()->back()
                        ->withErrors($data['errors'] ?? [])
                        ->withInput();
                } else {
                    return redirect()->back()
                        ->with('error', $data['message'] ?? 'Gagal membuat event')
                        ->withInput();
                }
            }

            return redirect()->back()
                ->with('error', 'Response type: ' . get_class($response))
                ->withInput();

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified event by calling the API.
     */
    public function update(Request $request, $id)
    {
        try {
            $token = request()->cookie('access_token');

            $apiRequest = Http::withToken($token);

            if ($request->hasFile('image')) {
                $apiRequest->attach('image', file_get_contents($request->file('image')->getRealPath()), $request->file('image')->getClientOriginalName());
            }

            $response = $apiRequest->post(url("/api/events/{$id}"), array_merge(
                $request->only(['title', 'description', 'location', 'date', 'time', 'category_id']),
                ['_method' => 'PUT']
            ));

            if ($response->successful()) {
                return redirect()->route('events.show', $id)
                    ->with('success', 'Event berhasil diperbarui!');
            } elseif ($response->status() === 422) {
                return redirect()->back()
                    ->withErrors($response->json('errors'))
                    ->withInput();
            } else {
                return redirect()->back()
                    ->with('error', $response->json('message', 'Gagal memperbarui event'))
                    ->withInput();
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display event registrants for organizers.
     */
    public function registrants($id)
    {
        try {
            $apiController = new ApiEventController();
            $response = $apiController->getRegistrants($id);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                $statusCode = $response->status();

                if ($statusCode === 200) {
                    $event = Event::findOrFail($id);
                    $registrants = $data['data'] ?? [];

                    return view('events.registrants', compact('event', 'registrants'));
                } else {
                    return redirect()->back()
                        ->with('error', $data['message'] ?? 'Gagal memuat data registrants');
                }
            }

            return redirect()->back()
                ->with('error', 'Gagal memuat data registrants');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Register user for an event.
     */
    public function register(Request $request, $id)
    {
        try {
            $apiController = new ApiEventController();
            $apiRequest = new Request();
            $response = $apiController->registerForEvent($id);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                $statusCode = $response->status();

                if ($statusCode === 200) {
                    return redirect()->back()
                        ->with('success', $data['message'] ?? 'Berhasil mendaftar ke event!');
                } else {
                    return redirect()->back()
                        ->with('error', $data['message'] ?? 'Gagal mendaftar ke event');
                }
            }

            return redirect()->back()
                ->with('error', 'Gagal mendaftar ke event');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove a registrant from an event.
     */
    public function removeRegistrant($eventId, $userId)
    {
        try {
            $apiController = new ApiEventController();
            $response = $apiController->removeRegistrant($eventId, $userId);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                $statusCode = $response->status();

                if ($statusCode === 200) {
                    return redirect()->back()
                        ->with('success', $data['message'] ?? 'Registrant berhasil dihapus');
                } else {
                    return redirect()->back()
                        ->with('error', $data['message'] ?? 'Gagal menghapus registrant');
                }
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus registrant');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display all events created by the organizer.
     */
    public function organizerEvents(Request $request)
    {
        try {
            $user = Auth::user();

            $query = Event::where('user_id', $user->id)
                ->with(['category', 'organization', 'users']);

            // Search functionality
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && $request->input('status') !== 'all') {
                $query->where('status', $request->input('status'));
            }

            $events = $query->orderBy('created_at', 'desc')->paginate(10);
            $categories = Category::select('id', 'name', 'slug')->get();

            return view('organizer.events', compact('events', 'categories'));
        } catch (Exception $e) {
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Bookmark an event.
     */
    public function bookmarkEvent($id)
    {
        try {
            $apiController = new ApiEventController();
            $response = $apiController->bookmarkEvent($id);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                $statusCode = $response->status();

                if ($statusCode === 200) {
                    return redirect()->back()
                        ->with('success', $data['message'] ?? 'Event berhasil dibookmark');
                } else {
                    return redirect()->back()
                        ->with('error', $data['message'] ?? 'Gagal bookmark event');
                }
            }

            return redirect()->back()
                ->with('error', 'Gagal bookmark event');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove bookmark from an event.
     */
    public function unbookmarkEvent($id)
    {
        try {
            $apiController = new ApiEventController();
            $response = $apiController->unbookmarkEvent($id);

            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData(true);
                $statusCode = $response->status();

                if ($statusCode === 200) {
                    return redirect()->back()
                        ->with('success', $data['message'] ?? 'Bookmark berhasil dihapus');
                } else {
                    return redirect()->back()
                        ->with('error', $data['message'] ?? 'Gagal menghapus bookmark');
                }
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus bookmark');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
