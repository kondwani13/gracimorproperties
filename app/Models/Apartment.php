<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Apartment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'price_per_night',
        'cleaning_fee',
        'service_fee',
        'bedrooms',
        'bathrooms',
        'max_guests',
        'size_sqft',
        'property_type',
        'amenities',
        'house_rules',
        'main_image',
        'minimum_stay',
        'maximum_stay',
        'check_in_time',
        'check_out_time',
        'is_available',
        'is_featured',
        'rating',
        'total_reviews',
        'views_count',
        'booking_count',
    ];

    protected $casts = [
        'amenities' => 'array',
        'house_rules' => 'array',
        'price_per_night' => 'decimal:2',
        'cleaning_fee' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'decimal:2',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($apartment) {
            if (empty($apartment->slug)) {
                $apartment->slug = Str::slug($apartment->title);
            }
        });

        static::updating(function ($apartment) {
            if ($apartment->isDirty('title')) {
                $apartment->slug = Str::slug($apartment->title);
            }
        });
    }

    /**
     * Get apartment images
     */
    public function images()
    {
        return $this->hasMany(ApartmentImage::class)->orderBy('order');
    }

    /**
     * Get apartment bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get apartment reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    /**
     * Get blocked dates
     */
    public function blockedDates()
    {
        return $this->hasMany(BlockedDate::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    // New hasManyThrough relationship for RentPayments
    public function rentPayments()
    {
        return $this->hasManyThrough(RentPayment::class, Tenant::class);
    }

    public function maintenanceCosts()
    {
        return $this->hasMany(MaintenanceCost::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function consentForms()
    {
        return $this->hasMany(ConsentForm::class);
    }

    /**
     * Get users who favorited this apartment
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    /**
     * Check if apartment is favorited by a user
     */
    public function isFavoritedBy($user)
    {
        if (!$user) {
            return false;
        }

        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Scope for available apartments
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for featured apartments
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_available', true);
    }

    /**
     * Scope for filtering by city
     */
    public function scopeCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    /**
     * Scope for price range
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price_per_night', [$min, $max]);
    }

    /**
     * Scope for minimum bedrooms
     */
    public function scopeMinBedrooms($query, $bedrooms)
    {
        return $query->where('bedrooms', '>=', $bedrooms);
    }

    /**
     * Scope for minimum guests
     */
    public function scopeMinGuests($query, $guests)
    {
        return $query->where('max_guests', '>=', $guests);
    }

    /**
     * Check if apartment is available for given dates
     * @param string $checkIn Check-in date
     * @param string $checkOut Check-out date
     * @param int|null $excludeBookingId Booking ID to exclude from check (for editing existing bookings)
     */
    public function isAvailableForDates($checkIn, $checkOut, $excludeBookingId = null)
    {
        if (!$this->is_available) {
            return false;
        }

        // Check for overlapping bookings
        $query = $this->bookings()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                    });
            });

        // Exclude the booking being edited
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $hasBooking = $query->exists();

        if ($hasBooking) {
            return false;
        }

        // Check for blocked dates
        $hasBlockedDate = $this->blockedDates()
            ->whereBetween('date', [$checkIn, $checkOut])
            ->exists();

        return !$hasBlockedDate;
    }

    /**
     * Calculate total price for booking
     */
    public function calculatePrice($checkIn, $checkOut)
    {
        $nights = \Carbon\Carbon::parse($checkIn)->diffInDays($checkOut);
        $subtotal = $this->price_per_night * $nights;
        $total = $subtotal + $this->cleaning_fee + $this->service_fee;

        // Calculate tax (10%)
        $tax = $total * 0.10;

        return [
            'number_of_nights' => $nights,
            'nights' => $nights, // For backward compatibility
            'price_per_night' => $this->price_per_night,
            'subtotal' => $subtotal,
            'cleaning_fee' => $this->cleaning_fee,
            'service_fee' => $this->service_fee,
            'tax_amount' => $tax,
            'tax' => $tax, // For backward compatibility
            'total_amount' => $total + $tax,
            'total' => $total + $tax, // For backward compatibility
        ];
    }

    /**
     * Increment view count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Update rating
     */
    public function updateRating()
    {
        $avgRating = $this->reviews()->avg('rating');
        $totalReviews = $this->reviews()->count();

        $this->update([
            'rating' => $avgRating ?? 0,
            'total_reviews' => $totalReviews,
        ]);
    }

    /**
     * Get main image URL
     */
    public function getMainImageUrlAttribute()
    {
        if ($this->main_image) {
            if (str_starts_with($this->main_image, 'http://') || str_starts_with($this->main_image, 'https://')) {
                return $this->main_image;
            }
            return asset('storage/' . $this->main_image);
        }
        return $this->images->first()->url ?? asset('img/bg/vedio-img.png');
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->postal_code}, {$this->country}";
    }

    /**
     * Get route key name for model binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
