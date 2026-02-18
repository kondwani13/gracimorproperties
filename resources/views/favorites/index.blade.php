@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Favorite Apartments</h1>

    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favorites as $apartment)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="relative h-48">
                        @if($apartment->images->first())
                            <img src="{{ $apartment->images->first()->url }}"
                                 alt="{{ $apartment->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-4xl">🏠</span>
                            </div>
                        @endif

                        <!-- Remove from Favorites -->
                        <form action="{{ route('apartments.favorite', $apartment) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            <button type="submit" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100">
                                <svg class="w-5 h-5 text-red-500 fill-current" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">📍 {{ $apartment->city }}, {{ $apartment->state }}</span>
                            @if($apartment->rating)
                                <span class="flex items-center text-sm">
                                    ⭐ {{ number_format($apartment->rating, 1) }}
                                    <span class="text-gray-600 ml-1">({{ $apartment->total_reviews }})</span>
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate">{{ $apartment->title }}</h3>

                        <div class="flex items-center text-sm text-gray-600 mb-3 space-x-4">
                            <span>🛏️ {{ $apartment->bedrooms }} beds</span>
                            <span>🚿 {{ $apartment->bathrooms }} baths</span>
                            <span>👥 {{ $apartment->max_guests }} guests</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-gray-900">K{{ number_format($apartment->price_per_night) }}</span>
                                <span class="text-gray-600 text-sm">/ night</span>
                            </div>
                            <a href="{{ route('apartments.show', $apartment) }}"
                               class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium transition">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-6xl mb-4">❤️</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No favorites yet</h3>
            <p class="text-gray-600 mb-6">Start adding apartments to your favorites to see them here!</p>
            <a href="{{ route('apartments.index') }}"
               class="inline-block px-6 py-3 bg-brand hover:bg-brand-dark text-white rounded-md font-medium">
                Browse Apartments
            </a>
        </div>
    @endif
</div>
@endsection

