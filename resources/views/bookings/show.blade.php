@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Booking Details</h1>
        <a href="{{ route('bookings.index') }}" class="text-brand hover:text-indigo-700">← Back to Bookings</a>
    </div>

    <!-- Status Banner -->
    <div class="mb-6 px-6 py-4 rounded-lg {{ $booking->status == 'confirmed' ? 'bg-green-50 border border-green-200' : '' }}
        {{ $booking->status == 'pending' ? 'bg-yellow-50 border border-yellow-200' : '' }}
        {{ $booking->status == 'cancelled' ? 'bg-red-50 border border-red-200' : '' }}
        {{ $booking->status == 'completed' ? 'bg-blue-50 border border-blue-200' : '' }}">
        <p class="font-semibold {{ $booking->status == 'confirmed' ? 'text-green-900' : '' }}
            {{ $booking->status == 'pending' ? 'text-yellow-900' : '' }}
            {{ $booking->status == 'cancelled' ? 'text-red-900' : '' }}
            {{ $booking->status == 'completed' ? 'text-blue-900' : '' }}">
            Status: {{ ucfirst($booking->status) }}
        </p>
        <p class="text-sm mt-1 {{ $booking->status == 'confirmed' ? 'text-green-700' : '' }}
            {{ $booking->status == 'pending' ? 'text-yellow-700' : '' }}
            {{ $booking->status == 'cancelled' ? 'text-red-700' : '' }}
            {{ $booking->status == 'completed' ? 'text-blue-700' : '' }}">
            Booking Number: {{ $booking->booking_number }}
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Apartment Info -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48">
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
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-2">{{ $booking->apartment->title }}</h2>
                    <p class="text-gray-600">📍 {{ $booking->apartment->address }}, {{ $booking->apartment->city }}, {{ $booking->apartment->state }}</p>
                    <a href="{{ route('apartments.show', $booking->apartment) }}"
                       class="inline-block mt-4 text-brand hover:text-indigo-700">
                        View Apartment Details →
                    </a>
                </div>
            </div>

            <!-- Trip Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Trip Details</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Check-in</p>
                        <p class="font-semibold text-lg">{{ $booking->check_in->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->check_in->format('l') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Check-out</p>
                        <p class="font-semibold text-lg">{{ $booking->check_out->format('M d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->check_out->format('l') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Duration</p>
                        <p class="font-semibold">{{ $booking->number_of_nights }} {{ Str::plural('night', $booking->number_of_nights) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Guests</p>
                        <p class="font-semibold">{{ $booking->number_of_guests }} {{ Str::plural('guest', $booking->number_of_guests) }}</p>
                    </div>
                </div>

                @if($booking->special_requests)
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-600 mb-1">Special Requests</p>
                        <p class="text-gray-800">{{ $booking->special_requests }}</p>
                    </div>
                @endif
            </div>

            <!-- Actions -->
            @if($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-4">Manage Booking</h3>
                    <div class="space-y-3">
                        <a href="{{ route('bookings.edit', $booking) }}"
                           class="block w-full py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md text-center">
                            Edit Booking Details
                        </a>
                        <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">
                                Cancel This Booking
                            </button>
                        </form>
                        <p class="text-xs text-gray-600 text-center">
                            You can edit your booking details or cancel it before payment
                        </p>
                    </div>
                </div>
            @elseif($booking->canBeCancelled())
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-semibold mb-4">Manage Booking</h3>
                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md">
                            Cancel This Booking
                        </button>
                        <p class="text-sm text-gray-600 mt-2 text-center">
                            Refund will be processed according to cancellation policy
                        </p>
                    </form>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Price Breakdown -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4">Price Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">K{{ number_format($booking->price_per_night) }} × {{ $booking->number_of_nights }} nights</span>
                        <span class="font-medium">K{{ number_format($booking->subtotal) }}</span>
                    </div>
                    @if($booking->cleaning_fee)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Cleaning fee</span>
                            <span class="font-medium">K{{ number_format($booking->cleaning_fee) }}</span>
                        </div>
                    @endif
                    @if($booking->service_fee)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Service fee</span>
                            <span class="font-medium">K{{ number_format($booking->service_fee) }}</span>
                        </div>
                    @endif
                    @if($booking->tax_amount)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">K{{ number_format($booking->tax_amount) }}</span>
                        </div>
                    @endif
                </div>
                <div class="border-t mt-4 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-lg">Total</span>
                        <span class="font-bold text-2xl text-brand">K{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            @if($booking->payment)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3">Payment Information</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status</span>
                            <span class="font-medium {{ $booking->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($booking->payment->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Method</span>
                            <span class="font-medium">{{ ucfirst($booking->payment->payment_method) }}</span>
                        </div>
                        @if($booking->payment->transaction_id)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Transaction ID</span>
                                <span class="font-medium text-xs">{{ Str::limit($booking->payment->transaction_id, 20) }}</span>
                            </div>
                        @endif
                    </div>
                    @if($booking->payment->receipt_url)
                        <a href="{{ $booking->payment->receipt_url }}" target="_blank"
                           class="block mt-4 text-center py-2 border border-indigo-600 text-brand rounded-md hover:bg-indigo-50">
                            View Receipt
                        </a>
                    @endif
                </div>
            @endif

            <!-- Pay Now Button (for unpaid bookings) -->
            @if($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-3 text-yellow-900">Payment Required</h3>
                    <p class="text-sm text-gray-600 mb-4">Complete your payment to confirm this booking.</p>
                    <a href="{{ route('payments.show', $booking) }}"
                       class="block w-full py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md text-center transition">
                        Pay Now - K{{ number_format($booking->total_amount, 2) }}
                    </a>
                </div>
            @endif

            <!-- Download Invoice (only for paid bookings) -->
            @if($booking->payment_status === 'paid')
                <a href="{{ route('bookings.invoice', $booking) }}" target="_blank"
                   class="block bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition text-center">
                    <div class="text-3xl mb-2">📄</div>
                    <span class="font-semibold text-brand">Download Invoice</span>
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

