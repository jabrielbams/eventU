<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request, $eventId)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $event = Event::findOrFail($eventId);
            $user = Auth::user();

            // Check if event has already passed (optional: only allow comments on past events)
            // if ($event->date > now()) {
            //     return redirect()->back()
            //         ->with('error', 'Anda hanya dapat memberikan komentar setelah event selesai.');
            // }

            Comment::create([
                'user_id' => $user->id,
                'event_id' => $eventId,
                'content' => $request->content,
            ]);

            return redirect()->back()
                ->with('success', 'Komentar berhasil ditambahkan!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan komentar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $comment = Comment::findOrFail($id);
            $user = Auth::user();

            // Check if user owns this comment
            if ($comment->user_id !== $user->id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit komentar ini.');
            }

            $comment->update([
                'content' => $request->content,
            ]);

            return redirect()->back()
                ->with('success', 'Komentar berhasil diperbarui!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui komentar: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified comment.
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $user = Auth::user();

            // Check if user owns this comment or is an organizer of the event
            $event = Event::find($comment->event_id);
            $isEventOwner = $event && $event->user_id === $user->id;

            if ($comment->user_id !== $user->id && !$isEventOwner) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus komentar ini.');
            }

            $comment->delete();

            return redirect()->back()
                ->with('success', 'Komentar berhasil dihapus!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus komentar: ' . $e->getMessage());
        }
    }
}
