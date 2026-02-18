@extends('layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Your Review</h1>

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="md:flex">
            <div class="md:w-48 h-32 md:h-auto">
                @if($review->apartment->images->first())
                    <img src="{{ $review->apartment->images->first()->url }}"
                         alt="{{ $review->apartment->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-4xl">🏠</span>
                    </div>
                @endif
            </div>
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-1">{{ $review->apartment->title }}</h2>
                <p class="text-gray-600 text-sm">{{ $review->apartment->city }}, {{ $review->apartment->state }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('reviews.update', $review) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Overall Rating -->
        <div>
            <label class="block text-lg font-semibold text-gray-900 mb-3">Overall Rating *</label>
            <div class="flex items-center space-x-2">
                @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer">
                        <input type="radio" name="rating" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }} required class="sr-only peer">
                        <svg class="w-10 h-10 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 transition" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </label>
                @endfor
            </div>
        </div>

        <!-- Category Ratings -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cleanliness</label>
                <select name="cleanliness_rating" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    <option value="">Select rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $review->cleanliness_rating == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Accuracy</label>
                <select name="accuracy_rating" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    <option value="">Select rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $review->accuracy_rating == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <select name="location_rating" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    <option value="">Select rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $review->location_rating == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Value for Money</label>
                <select name="value_rating" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    <option value="">Select rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $review->value_rating == $i ? 'selected' : '' }}>{{ $i }} {{ $i == 1 ? 'Star' : 'Stars' }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <!-- Written Review -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Your Review *</label>
            <textarea name="comment" rows="6" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('comment', $review->comment) }}</textarea>
            @error('comment')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex space-x-4">
            <button type="submit" class="flex-1 py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md">
                Update Review
            </button>
            <a href="{{ route('bookings.show', $review->booking) }}" class="px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

