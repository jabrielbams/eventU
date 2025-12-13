<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of events by calling the API.
     */
    public function index(Request $request)
    {
        try {
            $token = request()->cookie('access_token');

            $url = url('/api/events');
            $params = [];

            if ($request->has('page')) {
                $params['page'] = $request->page;
            }
            if ($request->has('category') && $request->category !== 'all') {
                $params['category'] = $request->category;
            }
            if ($request->has('search')) {
                $params['search'] = $request->search;
            }

            $response = Http::withToken($token)->get($url, $params);

            if ($response->successful()) {
                $events = $response->json();
                return view('events.index', compact('events'));
            }

            return view('events.index', ['events' => ['data' => []]]);
        } catch (\Exception $e) {
            return view('events.index', ['events' => ['data' => []]]);
        }
    }

    /**
     * Display the specified event by calling the API.
     */
    public function show($id)
    {
        try {
            $token = request()->cookie('access_token');

            $response = Http::withToken($token)->get(url("/api/events/{$id}"));

            if ($response->successful()) {
                $event = $response->json()['data'];
                return view('events.show', compact('event'));
            }

            return redirect()->route('events.index')
                ->with('error', 'Event tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created event by calling the API.
     */
    public function store(Request $request)
    {
        try {
            $token = request()->cookie('access_token');

            $response = Http::withToken($token)
                ->attach('image', $request->file('image') ? file_get_contents($request->file('image')->getRealPath()) : '', $request->file('image') ? $request->file('image')->getClientOriginalName() : '')
                ->post(url('/api/events'), [
                    'title' => $request->title,
                    'description' => $request->description,
                    'location' => $request->location,
                    'date' => $request->date,
                    'time' => $request->time,
                    'category_id' => $request->category_id,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->route('events.show', $data['data']['id'])
                    ->with('success', 'Event berhasil dibuat!');
            } elseif ($response->status() === 422) {
                return redirect()->back()
                    ->withErrors($response->json('errors'))
                    ->withInput();
            } else {
                return redirect()->back()
                    ->with('error', $response->json('message', 'Gagal membuat event'))
                    ->withInput();
            }
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
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Register for an event by calling the API.
     */
    public function register($id)
    {
        try {
            $token = request()->cookie('access_token');

            $response = Http::withToken($token)
                ->post(url("/api/events/{$id}/register"));

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->route('events.show', $id)
                    ->with('success', 'Registrasi berhasil! Ticket ID: ' . ($data['ticket_id'] ?? 'N/A'));
            } else {
                $message = $response->json('message', 'Gagal melakukan registrasi');
                return redirect()->route('events.show', $id)
                    ->with('error', $message);
            }
        } catch (\Exception $e) {
            return redirect()->route('events.show', $id)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
