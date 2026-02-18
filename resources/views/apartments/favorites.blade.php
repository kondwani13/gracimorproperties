@extends('layouts.app')

@section('title', 'My Favorites')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">My Favorite Gracimor Apartments</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($favorites as $apartment)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
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
                        <span class="text-sm text-gray-600">{{ $apartment->city }}, {{ $apartment->state }}</span>
                        @if($apartment->rating)
                            <span class="flex items-center text-sm">
                                {{ number_format($apartment->rating, 1) }}
                                <span class="text-gray-600 ml-1">({{ $apartment->total_reviews }})</span>
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 truncate">{{ $apartment->title }}</h3>

                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($apartment->description, 100) }}</p>

                    <div class="flex items-center text-sm text-gray-600 mb-3 space-x-4">
                        <span>{{ $apartment->bedrooms }} beds</span>
                        <span>{{ $apartment->bathrooms }} baths</span>
                        <span>{{ $apartment->max_guests }} guests</span>
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
        @empty
            <div class="col-span-full text-center py-12">
                <span class="text-gray-400 text-6xl">&#x2661;</span>
                <p class="text-gray-500 text-lg mt-4">You haven't saved any favorites yet.</p>
                <a href="{{ route('apartments.index') }}" class="inline-block mt-4 px-6 py-2 bg-brand hover:bg-brand-dark text-white rounded-md font-medium transition">
                    Browse Apartments
                </a>
            </div>
        @endforelse
    </div>

    @if($favorites->hasPages())
        <div class="mt-8">
            {{ $favorites->links() }}
        </div>
    @endif
</div>
@endsection

