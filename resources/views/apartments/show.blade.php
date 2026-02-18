@extends('layouts.app')

@section('title', $apartment->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Title -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $apartment->title }}</h1>
            <div class="flex items-center space-x-4 text-sm text-gray-600">
                <span>📍 {{ $apartment->address }}, {{ $apartment->city }}, {{ $apartment->state }}</span>
                @if($apartment->rating)
                    <span>⭐ {{ number_format($apartment->rating, 1) }} ({{ $apartment->total_reviews }} reviews)</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Images Gallery -->
    <div class="mb-8" x-data="{ activeImage: 0 }">
        <!-- Main Image -->
        <div class="rounded-lg overflow-hidden h-96 mb-3">
            @if($apartment->images->count() > 0)
                @foreach($apartment->images as $index => $image)
                    <img x-show="activeImage === {{ $index }}"
                         src="{{ $image->url }}"
                         alt="{{ $apartment->title }} - Image {{ $index + 1 }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null;this.src='{{ asset('img/bg/vedio-img.png') }}';">
                @endforeach
            @else
                <img src="{{ asset('img/bg/vedio-img.png') }}" alt="{{ $apartment->title }}" class="w-full h-full object-cover">
            @endif
        </div>
        <!-- Thumbnails -->
        @if($apartment->images->count() > 1)
            <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                @foreach($apartment->images as $index => $image)
                    <div @click="activeImage = {{ $index }}"
                         :class="activeImage === {{ $index }} ? 'ring-2 ring-brand' : 'opacity-70 hover:opacity-100'"
                         class="cursor-pointer rounded overflow-hidden h-16 transition">
                        <img src="{{ $image->url }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover"
                             onerror="this.onerror=null;this.src='{{ asset('img/bg/vedio-img.png') }}';">
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">Apartment Details</h2>
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl mb-2">🛏️</div>
                        <div class="text-sm text-gray-600">Bedrooms</div>
                        <div class="font-semibold">{{ $apartment->bedrooms }}</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl mb-2">🚿</div>
                        <div class="text-sm text-gray-600">Bathrooms</div>
                        <div class="font-semibold">{{ $apartment->bathrooms }}</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl mb-2">👥</div>
                        <div class="text-sm text-gray-600">Guests</div>
                        <div class="font-semibold">{{ $apartment->max_guests }}</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl mb-2">💰</div>
                        <div class="text-sm text-gray-600">Per Night</div>
                        <div class="font-semibold">K{{ number_format($apartment->price_per_night) }}</div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-3">Description</h3>
                <div class="text-gray-700 whitespace-pre-line">{{ $apartment->description }}</div>
            </div>

            @if($apartment->amenities)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-semibold mb-4">Amenities</h2>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($apartment->amenities as $amenity)
                        <div class="flex items-center space-x-2">
                            <span class="text-green-500">✓</span>
                            <span>{{ $amenity }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Booking Card -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <div class="text-center mb-6">
                    <span class="text-3xl font-bold">K{{ number_format($apartment->price_per_night) }}</span>
                    <span class="text-gray-600">/ night</span>
                </div>

                <form action="{{ route('bookings.create') }}" method="GET">
                    <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">
                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Check-in</label>
                            <input type="date" name="check_in" required min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Check-out</label>
                            <input type="date" name="check_out" required min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Guests</label>
                            <input type="number" name="guests" min="1" max="{{ $apartment->max_guests }}" value="1" required
                                   class="w-full px-4 py-2 border rounded-md">
                        </div>
                    </div>

                    @auth
                        <button type="submit" class="w-full py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md">
                            Check Availability
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="block text-center w-full py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md">
                            Login to Book
                        </a>
                    @endauth
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

