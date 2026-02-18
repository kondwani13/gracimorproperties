<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'apartment_id',
        'booking_id',
        'rating',
        'cleanliness_rating',
        'accuracy_rating',
        'communication_rating',
        'location_rating',
        'value_rating',
        'comment',
        'admin_response',
        'responded_at',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'responded_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $review->apartment->updateRating();
        });

        static::updated(function ($review) {
            $review->apartment->updateRating();
        });

        static::deleted(function ($review) {
            $review->apartment->updateRating();
        });
    }

    /**
     * Get the user that wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the apartment being reviewed
     */
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    /**
     * Get the booking for this review
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Calculate average of all ratings
     */
    public function getAverageRatingAttribute()
    {
        $ratings = array_filter([
            $this->rating,
            $this->cleanliness_rating,
            $this->accuracy_rating,
            $this->communication_rating,
            $this->location_rating,
            $this->value_rating,
        ]);

        return count($ratings) > 0 ? array_sum($ratings) / count($ratings) : 0;
    }

    /**
     * Add admin response
     */
    public function addResponse($response)
    {
        $this->update([
            'admin_response' => $response,
            'responded_at' => now(),
        ]);
    }

    /**
     * Approve review
     */
    public function approve()
    {
        $this->update(['is_approved' => true]);
    }

    /**
     * Reject review
     */
    public function reject()
    {
        $this->update(['is_approved' => false]);
    }
}
