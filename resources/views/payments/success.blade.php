@extends('layouts.app')

@section('title', 'Payment Successful!')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <!-- Success Icon -->
        <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-green-100 mb-6">
            <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
        <p class="text-lg text-gray-600 mb-8">
            Your Gracimor Hyndland booking is confirmed and payment received.
        </p>

        <!-- Booking Details -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h2>
            <div class="space-y-3 text-left">
                <div class="flex justify-between">
                    <span class="text-gray-600">Booking Number:</span>
                    <span class="font-semibold">{{ $booking->booking_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Apartment:</span>
                    <span class="font-semibold">{{ $booking->apartment->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Check-in:</span>
                    <span class="font-semibold">{{ $booking->check_in->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Check-out:</span>
                    <span class="font-semibold">{{ $booking->check_out->format('M d, Y') }}</span>
                </div>
                <div class="border-t pt-3 mt-3 flex justify-between">
                    <span class="text-gray-900 font-semibold">Total Paid:</span>
                    <span class="text-2xl font-bold text-green-600">K{{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <h3 class="font-semibold text-blue-900 mb-2">📧 What's Next?</h3>
            <ul class="text-sm text-blue-800 text-left space-y-1">
                <li>• Confirmation email sent to {{ $booking->user->email }}</li>
                <li>• Your booking details are available in your Gracimor account</li>
                <li>• You can download your invoice from your booking page</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('bookings.show', $booking) }}"
               class="px-8 py-3 bg-brand hover:bg-brand-dark text-white font-semibold rounded-md transition">
                View Booking Details
            </a>
            <a href="{{ route('bookings.index') }}"
               class="px-8 py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-md transition">
                My Bookings
            </a>
            <a href="{{ route('apartments.index') }}"
               class="px-8 py-3 border-2 border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-md transition">
                Browse More
            </a>
        </div>
    </div>
</div>

<!-- Auto-redirect notification (optional) -->
<script>
    // Show a toast notification
    setTimeout(() => {
        console.log('Payment completed successfully!');
    }, 100);
</script>
@endsection

