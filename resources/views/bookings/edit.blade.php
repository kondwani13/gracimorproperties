@extends('layouts.app')

@section('title', 'Edit Booking - ' . $apartment->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Your Booking</h1>
        <p class="text-gray-600">Booking Number: {{ $booking->booking_number }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('bookings.update', $booking) }}" method="POST" id="booking-form" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Trip Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Trip Details</h2>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date *</label>
                            <input type="date" name="check_in" value="{{ old('check_in', $checkIn) }}" required
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                            @error('check_in')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date *</label>
                            <input type="date" name="check_out" value="{{ old('check_out', $checkOut) }}" required
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                            @error('check_out')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Guests *</label>
                        <input type="number" name="number_of_guests" value="{{ old('number_of_guests', $guests) }}"
                               min="1" max="{{ $apartment->max_guests }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                        <p class="mt-1 text-sm text-gray-600">Maximum {{ $apartment->max_guests }} guests</p>
                        @error('number_of_guests')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Special Requests -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Special Requests (Optional)</h2>
                    <textarea name="special_requests" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                              placeholder="Let us know if you have any special requirements...">{{ old('special_requests', $booking->special_requests) }}</textarea>
                </div>

                <!-- Info Banner -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <svg class="h-6 w-6 text-yellow-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-900">Important</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Changes to your booking will update the total amount. You haven't paid yet, so the new amount will be reflected on the payment page.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md">
                        Update Booking & Proceed to Payment
                    </button>
                    <a href="{{ route('bookings.show', $booking) }}"
                       class="px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Booking Summary Sidebar -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="font-semibold text-lg mb-4">Booking Summary</h3>

                <!-- Apartment Info -->
                <div class="mb-4">
                    @if($apartment->images->first())
                        <img src="{{ $apartment->images->first()->url }}" alt="{{ $apartment->title }}"
                             class="w-full h-32 object-cover rounded-md mb-3">
                    @endif
                    <h4 class="font-medium">{{ $apartment->title }}</h4>
                    <p class="text-sm text-gray-600">{{ $apartment->city }}, {{ $apartment->state }}</p>
                </div>

                <hr class="my-4">

                <!-- Price Breakdown -->
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">K{{ number_format($pricing['price_per_night']) }} × {{ $pricing['number_of_nights'] }} night{{ $pricing['number_of_nights'] > 1 ? 's' : '' }}</span>
                        <span>K{{ number_format($pricing['subtotal']) }}</span>
                    </div>
                    @if($pricing['cleaning_fee'])
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cleaning fee</span>
                            <span>K{{ number_format($pricing['cleaning_fee']) }}</span>
                        </div>
                    @endif
                    @if($pricing['service_fee'])
                        <div class="flex justify-between">
                            <span class="text-gray-600">Service fee</span>
                            <span>K{{ number_format($pricing['service_fee']) }}</span>
                        </div>
                    @endif
                    @if($pricing['tax_amount'])
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span>K{{ number_format($pricing['tax_amount']) }}</span>
                        </div>
                    @endif
                </div>

                <hr class="my-4">

                <!-- Total -->
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-lg">Total</span>
                    <span class="font-bold text-2xl text-brand">K{{ number_format($pricing['total_amount'], 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

