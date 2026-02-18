<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Show review form
     */
    public function create(Booking $booking)
    {
        $this->authorize('review', $booking);

        if (!$booking->canBeReviewed()) {
            return redirect()->back()
                ->with('error', 'This booking cannot be reviewed.');
        }

        $booking->load('apartment');

        return view('reviews.create', compact('booking'));
    }

    /**
     * Store a new review
     */
    public function store(Request $request, Booking $booking)
    {
        $this->authorize('review', $booking);

        if (!$booking->canBeReviewed()) {
            return redirect()->back()
                ->with('error', 'This booking cannot be reviewed.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'accuracy_rating' => 'nullable|integer|min:1|max:5',
            'communication_rating' => 'nullable|integer|min:1|max:5',
            'location_rating' => 'nullable|integer|min:1|max:5',
            'value_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review = Review::create([
            'user_id' => auth()->id(),
            'apartment_id' => $booking->apartment_id,
            'booking_id' => $booking->id,
            ...$validated,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Thank you for your review!');
    }

    /**
     * Show edit review form
     */
    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update review
     */
    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'nullable|integer|min:1|max:5',
            'accuracy_rating' => 'nullable|integer|min:1|max:5',
            'communication_rating' => 'nullable|integer|min:1|max:5',
            'location_rating' => 'nullable|integer|min:1|max:5',
            'value_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('bookings.show', $review->booking)
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Delete review
     */
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $booking = $review->booking;
        $review->delete();

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Review deleted successfully.');
    }
}
