@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
        <a href="{{ route('apartments.index') }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md">
            Book New Apartment
        </a>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <a href="{{ route('bookings.index', ['status' => 'upcoming']) }}"
                   class="py-4 px-1 border-b-2 {{ $status == 'upcoming' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                    Upcoming
                </a>
                <a href="{{ route('bookings.index', ['status' => 'current']) }}"
                   class="py-4 px-1 border-b-2 {{ $status == 'current' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                    Current
                </a>
                <a href="{{ route('bookings.index', ['status' => 'past']) }}"
                   class="py-4 px-1 border-b-2 {{ $status == 'past' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                    Past
                </a>
                <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
                   class="py-4 px-1 border-b-2 {{ $status == 'cancelled' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                    Cancelled
                </a>
                <a href="{{ route('bookings.index', ['status' => 'all']) }}"
                   class="py-4 px-1 border-b-2 {{ $status == 'all' ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                    All
                </a>
            </nav>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="space-y-6">
        @forelse($bookings as $booking)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="md:flex">
                    <!-- Apartment Image -->
                    <div class="md:w-64 h-48 md:h-auto">
                        @if($booking->apartment->images->first())
                            <img src="{{ $booking->apartment->images->first()->url }}"
                                 alt="{{ $booking->apartment->title }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-4xl">🏠</span>
                            </div>
                        @endif
                    </div>

                    <!-- Booking Details -->
                    <div class="flex-1 p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $booking->apartment->title }}</h3>
                                <p class="text-gray-600 text-sm">📍 {{ $booking->apartment->city }}, {{ $booking->apartment->state }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $booking->status == 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Booking ID</p>
                                <p class="font-medium">{{ $booking->booking_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Check-in</p>
                                <p class="font-medium">{{ $booking->check_in->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Check-out</p>
                                <p class="font-medium">{{ $booking->check_out->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Guests</p>
                                <p class="font-medium">{{ $booking->number_of_guests }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-gray-900">K{{ number_format($booking->total_amount, 2) }}</span>
                                <span class="text-gray-600 text-sm">for {{ $booking->number_of_nights }} nights</span>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('bookings.show', $booking) }}"
                                   class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                                    View Details
                                </a>
                                @if($booking->canBeCancelled())
                                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                            Cancel Booking
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No bookings found</h3>
                <p class="text-gray-600 mb-6">Start exploring Gracimor Hyndland apartments!</p>
                <a href="{{ route('apartments.index') }}"
                   class="inline-block px-6 py-3 bg-brand hover:bg-brand-dark text-white rounded-md font-medium">
                    Browse Apartments
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

