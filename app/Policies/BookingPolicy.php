<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    /**
     * Determine if the user can view the booking
     */
    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can update the booking
     */
    public function update(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can cancel the booking
     */
    public function cancel(User $user, Booking $booking): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $booking->user_id && $booking->canBeCancelled();
    }

    /**
     * Determine if the user can review the booking
     */
    public function review(User $user, Booking $booking): bool
    {
        return $user->id === $booking->user_id && $booking->canBeReviewed();
    }

    /**
     * Determine if the user can delete the booking
     */
    public function delete(User $user, Booking $booking): bool
    {
        return $user->isAdmin();
    }
}
