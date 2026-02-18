<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
    /**
     * Display a listing of apartments
     */
    public function index(Request $request)
    {
        $query = Apartment::with('images')->available();

        // Search by city
        if ($request->filled('city')) {
            $query->city($request->city);
        }

        // Filter by price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Filter by bedrooms
        if ($request->filled('bedrooms')) {
            $query->minBedrooms($request->bedrooms);
        }

        // Filter by guests
        if ($request->filled('guests')) {
            $query->minGuests($request->guests);
        }

        // Filter by property type
        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Filter by amenities
        if ($request->filled('amenities')) {
            $amenities = is_array($request->amenities) ? $request->amenities : [$request->amenities];
            foreach ($amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'price') {
            $query->orderBy('price_per_night', $sortOrder);
        } elseif ($sortBy === 'rating') {
            $query->orderBy('rating', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $apartments = $query->paginate(12);

        return view('apartments.index', compact('apartments'));
    }

    /**
     * Display the specified apartment
     */
    public function show(Apartment $apartment)
    {
        $apartment->load(['images', 'reviews.user']);
        $apartment->incrementViews();

        // Get similar apartments
        $similarApartments = Apartment::available()
            ->where('id', '!=', $apartment->id)
            ->where('city', $apartment->city)
            ->orWhere('price_per_night', '>=', $apartment->price_per_night * 0.8)
            ->where('price_per_night', '<=', $apartment->price_per_night * 1.2)
            ->limit(4)
            ->get();

        return view('apartments.show', compact('apartment', 'similarApartments'));
    }

    /**
     * Check availability for given dates
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $apartment = Apartment::findOrFail($request->apartment_id);
        $isAvailable = $apartment->isAvailableForDates($request->check_in, $request->check_out);

        if ($isAvailable) {
            $pricing = $apartment->calculatePrice($request->check_in, $request->check_out);
            return response()->json([
                'available' => true,
                'pricing' => $pricing,
            ]);
        }

        return response()->json([
            'available' => false,
            'message' => 'This apartment is not available for the selected dates.',
        ], 422);
    }

    /**
     * Toggle favorite
     */
    public function toggleFavorite(Apartment $apartment)
    {
        $user = auth()->user();

        if ($user->hasFavorited($apartment)) {
            $user->favorites()->detach($apartment->id);
            return response()->json([
                'favorited' => false,
                'message' => 'Removed from favorites',
            ]);
        }

        $user->favorites()->attach($apartment->id);
        return response()->json([
            'favorited' => true,
            'message' => 'Added to favorites',
        ]);
    }

    /**
     * Show user's favorite apartments
     */
    public function favorites()
    {
        $favorites = auth()->user()->favorites()->with('images')->paginate(12);
        return view('apartments.favorites', compact('favorites'));
    }
}
