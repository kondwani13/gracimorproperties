@extends('layouts.admin')

@section('title', 'Edit Apartment')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.apartments.index') }}" class="text-brand hover:text-indigo-700">← Back to Apartments</a>
</div>

<h1 class="text-2xl font-semibold mb-6">Edit Apartment</h1>

<form action="{{ route('admin.apartments.update', $apartment) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-6">
            <p class="font-semibold text-red-700">Please fix the following errors:</p>
            <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Basic Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Basic Information</h2>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" value="{{ old('title', $apartment->title) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-md">{{ old('description', $apartment->description) }}</textarea>
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
                <input type="text" name="address" value="{{ old('address', $apartment->address) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                <input type="text" name="city" value="{{ old('city', $apartment->city) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                <input type="text" name="state" value="{{ old('state', $apartment->state) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Country *</label>
                <input type="text" name="country" value="{{ old('country', $apartment->country) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $apartment->postal_code) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('postal_code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Pricing</h2>

        <div class="grid grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Price per Night *</label>
                <input type="number" name="price_per_night" value="{{ old('price_per_night', $apartment->price_per_night) }}" required min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cleaning Fee</label>
                <input type="number" name="cleaning_fee" value="{{ old('cleaning_fee', $apartment->cleaning_fee) }}" min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Fee</label>
                <input type="number" name="service_fee" value="{{ old('service_fee', $apartment->service_fee) }}" min="0" step="0.01"
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
                <input type="number" name="bedrooms" value="{{ old('bedrooms', $apartment->bedrooms) }}" required min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms *</label>
                <input type="number" name="bathrooms" value="{{ old('bathrooms', $apartment->bathrooms) }}" required min="0" step="0.5"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Max Guests *</label>
                <input type="number" name="max_guests" value="{{ old('max_guests', $apartment->max_guests) }}" required min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Property Type *</label>
                <select name="property_type" required class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    <option value="apartment" {{ old('property_type', $apartment->property_type) == 'apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="house" {{ old('property_type', $apartment->property_type) == 'house' ? 'selected' : '' }}>House</option>
                    <option value="condo" {{ old('property_type', $apartment->property_type) == 'condo' ? 'selected' : '' }}>Condo</option>
                    <option value="villa" {{ old('property_type', $apartment->property_type) == 'villa' ? 'selected' : '' }}>Villa</option>
                </select>
                @error('property_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Current Images -->
    @if($apartment->images->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Current Images</h2>
            <div class="grid grid-cols-4 gap-4">
                @foreach($apartment->images as $image)
                    <div class="relative">
                        <img src="{{ $image->url }}" alt="" class="w-full h-32 object-cover rounded" onerror="this.onerror=null;this.src='{{ asset('img/bg/vedio-img.png') }}';">
                        <button type="button" onclick="if(confirm('Delete this image?')) document.getElementById('delete-image-{{ $image->id }}').submit();"
                                class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <form id="delete-image-{{ $image->id }}" action="{{ route('admin.apartments.images.destroy', [$apartment, $image]) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Add New Images -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Add New Images</h2>
        <input type="file" name="images[]" multiple accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-md">
        <p class="mt-1 text-sm text-gray-500">Select multiple images to upload</p>
    </div>

    <!-- Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Status</h2>
        <div class="flex items-center">
            <input type="hidden" name="is_available" value="0">
            <input type="checkbox" name="is_available" value="1" {{ old('is_available', $apartment->is_available) ? 'checked' : '' }}
                   class="h-4 w-4 text-brand border-gray-300 rounded">
            <label class="ml-2 block text-sm text-gray-900">Available</label>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex space-x-4">
        <button type="submit" class="px-6 py-2 bg-brand hover:bg-brand-dark text-white font-medium rounded-md">
            Update Apartment
        </button>
        <a href="{{ route('admin.apartments.index') }}" class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
            Cancel
        </a>
    </div>
</form>
@endsection

