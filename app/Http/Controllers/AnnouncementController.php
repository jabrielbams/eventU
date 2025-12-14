<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class AnnouncementController extends Controller
{
    // Organizer: List all announcements (optionally filter by event)
    public function index(Request $request)
    {
        $query = Announcement::with(['event', 'user']);
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        $announcements = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    // Organizer: Show create form
    public function create(Request $request)
    {
        $events = Event::where('user_id', Auth::id())->get();
        return view('announcements.create', compact('events'));
    }

    // Organizer: Store new announcement
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_id' => 'nullable|exists:event,id',
        ]);
        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'event_id' => $request->event_id,
            'user_id' => Auth::id(),
        ]);
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diposting!');
    }

    // Organizer: Show edit form
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $events = Event::where('user_id', Auth::id())->get();
        return view('announcements.edit', compact('announcement', 'events'));
    }

    // Organizer: Update announcement
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        if ($announcement->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_id' => 'nullable|exists:event,id',
        ]);
        $announcement->update($request->only(['title', 'content', 'event_id']));
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    // Organizer: Delete announcement
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        if ($announcement->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Pengumuman berhasil dihapus!');
    }

    // Student: List announcements for registered events
    public function studentIndex()
    {
        $user = Auth::user();
        $eventIds = $user->events()->pluck('event.id');
        $announcements = Announcement::whereIn('event_id', $eventIds)
            ->orWhereNull('event_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('announcements.student', compact('announcements'));
    }
}
