@extends('layouts.app')

@section('title', 'Book ' . $apartment->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Confirm Your Gracimor Booking</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" class="space-y-6">
                @csrf
                <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">

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
                        <input type="number" name="number_of_guests" value="{{ old('number_of_guests', $guests ?? 1) }}"
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
                              placeholder="Let us know if you have any special requirements...">{{ old('special_requests') }}</textarea>
                </div>

                <!-- Cancellation Policy -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="font-semibold text-blue-900 mb-2">📋 Cancellation Policy</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Cancel 14+ days before check-in: 100% refund</li>
                        <li>• Cancel 7-14 days before: 50% refund</li>
                        <li>• Cancel less than 7 days: No refund</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex space-x-4">
                    <button type="submit" class="flex-1 py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md">
                        Proceed to Payment
                    </button>
                    <a href="{{ route('apartments.show', $apartment) }}"
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
                        <span class="text-gray-600">K{{ number_format($apartment->price_per_night) }} × <span id="nights">1</span> nights</span>
                        <span id="subtotal">K{{ number_format($apartment->price_per_night) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cleaning fee</span>
                        <span>K{{ number_format($apartment->cleaning_fee ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Service fee</span>
                        <span>K{{ number_format($apartment->service_fee ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax (10%)</span>
                        <span id="tax">$0.00</span>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Total -->
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-lg">Total</span>
                    <span class="font-bold text-2xl text-brand" id="total">K{{ number_format($apartment->price_per_night + ($apartment->cleaning_fee ?? 0) + ($apartment->service_fee ?? 0)) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

