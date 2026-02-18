@extends('layouts.admin')

@section('title', 'Add New Apartment')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.apartments.index') }}" class="text-brand hover:text-indigo-700">← Back to Apartments</a>
</div>

<h1 class="text-2xl font-semibold mb-6">Add New Apartment</h1>

<form action="{{ route('admin.apartments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Basic Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Basic Information</h2>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Location -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Location</h2>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                <input type="text" name="address" value="{{ old('address') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                <input type="text" name="city" value="{{ old('city') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('city')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                <input type="text" name="state" value="{{ old('state') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('state')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                <input type="text" name="country" value="{{ old('country') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('country')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Pricing</h2>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price per Night *</label>
                <input type="number" name="price_per_night" value="{{ old('price_per_night') }}" required min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('price_per_night')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cleaning Fee</label>
                <input type="number" name="cleaning_fee" value="{{ old('cleaning_fee', 0) }}" min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Fee</label>
                <input type="number" name="service_fee" value="{{ old('service_fee', 0) }}" min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>
        </div>
    </div>

    <!-- Property Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Property Details</h2>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms *</label>
                <input type="number" name="bedrooms" value="{{ old('bedrooms') }}" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('bedrooms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms *</label>
                <input type="number" name="bathrooms" value="{{ old('bathrooms') }}" required min="0" step="0.5"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('bathrooms')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Max Guests *</label>
                <input type="number" name="max_guests" value="{{ old('max_guests') }}" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('max_guests')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Images -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Images</h2>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
            <input type="file" name="images[]" multiple accept="image/*"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md">
            <p class="mt-1 text-sm text-gray-500">You can select multiple images. First image will be the main image.</p>
        </div>
    </div>

    <!-- Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Status</h2>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                   class="h-4 w-4 text-brand focus:ring-brand border-gray-300 rounded">
            <label class="ml-2 block text-sm text-gray-900">Active (visible to users)</label>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex space-x-4">
        <button type="submit" class="px-6 py-2 bg-brand hover:bg-brand-dark text-white font-medium rounded-md">
            Create Apartment
        </button>
        <a href="{{ route('admin.apartments.index') }}" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
            Cancel
        </a>
    </div>
</form>
@endsection

