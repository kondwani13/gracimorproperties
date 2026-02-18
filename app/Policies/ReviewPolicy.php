<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine if the user can view the review
     */
    public function view(User $user, Review $review): bool
    {
        return true; // Reviews are public
    }

    /**
     * Determine if the user can create a review
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create reviews
    }

    /**
     * Determine if the user can update the review
     */
    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    /**
     * Determine if the user can delete the review
     */
    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id || $user->isAdmin();
    }
}
