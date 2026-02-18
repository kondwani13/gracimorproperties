@extends('layouts.app')

@section('title', 'Browse Our Apartments')

@section('content')
<div class="bg-white py-8 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Explore Gracimor Hyndland Apartments</h1>

        <!-- Filters -->
        <form method="GET" action="{{ route('apartments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text"
                       name="city"
                       value="{{ request('city') }}"
                       placeholder="City"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                <input type="number"
                       name="min_price"
                       value="{{ request('min_price') }}"
                       placeholder="$0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                <input type="number"
                       name="max_price"
                       value="{{ request('max_price') }}"
                       placeholder="Any"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                <select name="bedrooms" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">Any</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}+</option>
                    @endfor
                </select>
            </div>

            <div class="md:col-span-4 flex gap-4">
                <button type="submit" class="px-6 py-2 bg-brand hover:bg-brand-dark text-white rounded-md font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('apartments.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md font-medium">
                    Clear Filters
                </a>
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Results Count -->
    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-600">
            Found <span class="font-semibold">{{ $apartments->total() }}</span> apartments
        </p>

        <select onchange="window.location.href=this.value" class="px-4 py-2 border border-gray-300 rounded-md">
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                Latest
            </option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                Price: Low to High
            </option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                Price: High to Low
            </option>
            <option value="{{ request()->fullUrlWithQuery(['sort' => 'rating']) }}" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                Highest Rated
            </option>
        </select>
    </div>

    <!-- Apartments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($apartments as $apartment)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                <div class="relative h-48">
                        <img src="{{ $apartment->images->first()->url ?? asset('img/bg/vedio-img.png') }}"
                             alt="{{ $apartment->title }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null;this.src='{{ asset('img/bg/vedio-img.png') }}';">

                    @auth
                        <form action="{{ route('apartments.favorite', $apartment) }}" method="POST" class="absolute top-2 right-2">
                            @csrf
                            <button type="submit" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100">
                                <svg class="w-5 h-5 {{ $apartment->isFavoritedBy(auth()->user()) ? 'text-red-500 fill-current' : 'text-gray-600' }}"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </form>
                    @endauth
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

                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($apartment->description, 100) }}</p>

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
        @empty
            <div class="col-span-full text-center py-12">
                <span class="text-gray-400 text-6xl">🏠</span>
                <p class="text-gray-500 text-lg mt-4">No apartments found matching your search criteria.</p>
                <a href="{{ route('apartments.index') }}" class="inline-block mt-4 text-brand hover:text-indigo-700">
                    Clear filters and try again
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($apartments->hasPages())
        <div class="mt-8">
            {{ $apartments->links() }}
        </div>
    @endif
</div>
@endsection

