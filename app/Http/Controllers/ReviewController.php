<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class ReviewController extends Controller
{
    /**
     * Store a newly created review.
     */
    public function store(Request $request, $eventId)
    {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            $event = Event::findOrFail($eventId);
            $user = Auth::user();

            // Check if event has already passed or is completed (only allow reviews on past/completed events)
            $isPastOrCompleted = $event->date < now() || $event->status === 'completed';
            if (!$isPastOrCompleted) {
                return redirect()->back()
                    ->with('error', 'Anda hanya dapat memberikan review setelah event selesai.');
            }

            // Check if user has already reviewed this event
            $existingReview = Review::where('user_id', $user->id)
                ->where('event_id', $eventId)
                ->first();

            if ($existingReview) {
                return redirect()->back()
                    ->with('error', 'Anda sudah memberikan review untuk event ini.');
            }

            // Optional: Check if user was registered for this event (skip for now to allow all students to review)

            Review::create([
                'user_id' => $user->id,
                'event_id' => $eventId,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return redirect()->back()
                ->with('success', 'Review berhasil ditambahkan!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan review: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            $review = Review::findOrFail($id);
            $user = Auth::user();

            // Check if user owns this review
            if ($review->user_id !== $user->id) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk mengedit review ini.');
            }

            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            return redirect()->back()
                ->with('success', 'Review berhasil diperbarui!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui review: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified review.
     */
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $user = Auth::user();

            // Check if user owns this review or is an organizer of the event
            $event = Event::find($review->event_id);
            $isEventOwner = $event && $event->user_id === $user->id;

            if ($review->user_id !== $user->id && !$isEventOwner) {
                return redirect()->back()
                    ->with('error', 'Anda tidak memiliki akses untuk menghapus review ini.');
            }

            $review->delete();

            return redirect()->back()
                ->with('success', 'Review berhasil dihapus!');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus review: ' . $e->getMessage());
        }
    }
}
