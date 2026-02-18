<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\ApartmentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApartmentController extends Controller
{
    /**
     * Display a listing of apartments
     */
    public function index(Request $request)
    {
        $query = Apartment::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_available', $request->status === 'available');
        }

        $apartments = $query->withCount('bookings')
            ->latest()
            ->paginate(15);

        return view('admin.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new apartment
     */
    public function create()
    {
        return view('admin.apartments.create');
    }

    /**
     * Store a newly created apartment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_per_night' => 'required|numeric|min:0',
            'cleaning_fee' => 'nullable|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'size_sqft' => 'nullable|numeric|min:0',
            'property_type' => 'required|in:apartment,house,condo,villa',
            'amenities' => 'nullable|array',
            'house_rules' => 'nullable|array',
            'minimum_stay' => 'nullable|integer|min:1',
            'maximum_stay' => 'nullable|integer',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $validated['main_image'] = $request->file('main_image')->store('apartments', 'public');
        }

        $apartment = Apartment::create($validated);

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('apartments', 'public');
                
                ApartmentImage::create([
                    'apartment_id' => $apartment->id,
                    'image_path' => $path,
                    'order' => $index,
                    'is_main' => $index === 0 && !$request->hasFile('main_image'),
                ]);
            }
        }

        return redirect()->route('admin.apartments.index')
            ->with('success', 'Apartment created successfully.');
    }

    /**
     * Display the specified apartment
     */
    public function show(Apartment $apartment)
    {
        $apartment->load(['images', 'bookings.user', 'reviews']);
        
        return view('admin.apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified apartment
     */
    public function edit(Apartment $apartment)
    {
        $apartment->load('images');
        
        return view('admin.apartments.edit', compact('apartment'));
    }

    /**
     * Update the specified apartment
     */
    public function update(Request $request, Apartment $apartment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'price_per_night' => 'required|numeric|min:0',
            'cleaning_fee' => 'nullable|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'size_sqft' => 'nullable|numeric|min:0',
            'property_type' => 'required|in:apartment,house,condo,villa',
            'amenities' => 'nullable|array',
            'house_rules' => 'nullable|array',
            'minimum_stay' => 'nullable|integer|min:1',
            'maximum_stay' => 'nullable|integer',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            // Delete old image
            if ($apartment->main_image) {
                Storage::disk('public')->delete($apartment->main_image);
            }
            $validated['main_image'] = $request->file('main_image')->store('apartments', 'public');
        }

        $apartment->update($validated);

        // Handle additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('apartments', 'public');
                
                ApartmentImage::create([
                    'apartment_id' => $apartment->id,
                    'image_path' => $path,
                    'order' => $apartment->images()->max('order') + 1 + $index,
                ]);
            }
        }

        return redirect()->route('admin.apartments.edit', $apartment)
            ->with('success', 'Apartment updated successfully.');
    }

    /**
     * Remove the specified apartment
     */
    public function destroy(Apartment $apartment)
    {
        // Check for active bookings
        $activeBookings = $apartment->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_out', '>=', now())
            ->count();

        if ($activeBookings > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete apartment with active bookings.');
        }

        // Delete images
        if ($apartment->main_image) {
            Storage::disk('public')->delete($apartment->main_image);
        }

        foreach ($apartment->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $apartment->delete();

        return redirect()->route('admin.apartments.index')
            ->with('success', 'Apartment deleted successfully.');
    }

    /**
     * Delete apartment image
     */
    public function destroyImage(Apartment $apartment, ApartmentImage $image)
    {
        // Ensure the image belongs to the apartment
        if ($image->apartment_id !== $apartment->id) {
            abort(403, 'Unauthorized action.');
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Toggle apartment availability
     */
    public function toggleAvailability(Apartment $apartment)
    {
        $apartment->update(['is_available' => !$apartment->is_available]);

        return redirect()->back()
            ->with('success', 'Apartment availability updated.');
    }
}
