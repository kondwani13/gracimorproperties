@extends('layouts.admin')

@section('title', 'Booking Details: ' . $booking->booking_number)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Booking Details: {{ $booking->booking_number }}</h1>
    <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
        &larr; Back to Bookings
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Booking Overview Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Booking Overview</h2>
        <div class="space-y-2">
            <p><strong>Booking ID:</strong> {{ $booking->booking_number }}</p>
            <p><strong>Status:</strong> 
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $booking->getStatusColorAttribute() == 'success' ? 'bg-green-100 text-green-800' : '' }} {{ $booking->getStatusColorAttribute() == 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }} {{ $booking->getStatusColorAttribute() == 'danger' ? 'bg-red-100 text-red-800' : '' }} {{ $booking->getStatusColorAttribute() == 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </p>
            <p><strong>Payment Status:</strong> 
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $booking->getPaymentStatusColorAttribute() == 'success' ? 'bg-green-100 text-green-800' : '' }} {{ $booking->getPaymentStatusColorAttribute() == 'warning' ? 'bg-yellow-100 text-yellow-800' : '' }} {{ $booking->getPaymentStatusColorAttribute() == 'danger' ? 'bg-red-1100 text-red-800' : '' }} {{ $booking->getPaymentStatusColorAttribute() == 'info' ? 'bg-blue-100 text-blue-800' : '' }}">
                    {{ ucfirst($booking->payment_status) }}
                </span>
            </p>
            <p><strong>Check-in:</strong> {{ $booking->check_in->format('M d, Y') }}</p>
            <p><strong>Check-out:</strong> {{ $booking->check_out->format('M d, Y') }}</p>
            <p><strong>Nights:</strong> {{ $booking->number_of_nights }}</p>
            <p><strong>Guests:</strong> {{ $booking->number_of_guests }}</p>
            <p class="text-lg font-bold"><strong>Total Amount:</strong> K{{ number_format($booking->total_amount, 2) }}</p>
            @if ($booking->special_requests)
                <p><strong>Special Requests:</strong> {{ $booking->special_requests }}</p>
            @endif
        </div>
    </div>

    <!-- Guest Information Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Guest Information</h2>
        <div class="flex items-center space-x-4 mb-4">
            <img src="{{ $booking->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($booking->user->name) }}" 
                 alt="{{ $booking->user->name }}" 
                 class="h-16 w-16 rounded-full object-cover">
            <div>
                <p class="text-lg font-medium">{{ $booking->user->name }}</p>
                <p class="text-gray-600">{{ $booking->user->email }}</p>
                @if ($booking->user->phone)
                    <p class="text-gray-600">{{ $booking->user->phone }}</p>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.users.show', $booking->user) }}" class="text-brand hover:text-indigo-900 text-sm">View Guest Profile &rarr;</a>
    </div>

    <!-- Apartment Information Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Apartment Details</h2>
        <img src="{{ $booking->apartment->main_image_url }}" alt="{{ $booking->apartment->title }}" class="rounded-md object-cover h-32 w-full mb-4">
        <p class="text-lg font-medium mb-2">{{ $booking->apartment->title }}</p>
        <p class="text-gray-600">{{ $booking->apartment->address }}, {{ $booking->apartment->city }}</p>
        <p class="text-gray-600">Price per night: K{{ number_format($booking->apartment->price_per_night, 2) }}</p>
        <div class="space-y-1 mt-2">
            <p>Subtotal: K{{ number_format($booking->subtotal, 2) }}</p>
            <p>Cleaning Fee: K{{ number_format($booking->cleaning_fee, 2) }}</p>
            <p>Service Fee: K{{ number_format($booking->service_fee, 2) }}</p>
            <p>Tax Amount: K{{ number_format($booking->tax_amount, 2) }}</p>
        </div>
        <a href="{{ route('admin.apartments.show', $booking->apartment) }}" class="text-brand hover:text-indigo-900 text-sm mt-4 block">View Apartment &rarr;</a>
    </div>

    <!-- Payment Information Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Payment Details</h2>
        @if ($booking->payment)
            <div class="space-y-2">
                <p><strong>Payment ID:</strong> {{ $booking->payment->id }}</p>
                <p><strong>Amount Paid:</strong> K{{ number_format($booking->payment->amount, 2) }}</p>
                <p><strong>Method:</strong> {{ $booking->payment->method }}</p>
                <p><strong>Status:</strong> 
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $booking->payment->status == 'completed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $booking->payment->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $booking->payment->status == 'failed' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $booking->payment->status == 'refunded' ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ ucfirst($booking->payment->status) }}
                    </span>
                </p>
                <p><strong>Transaction ID:</strong> {{ $booking->payment->transaction_id ?? 'N/A' }}</p>
                <p><strong>Paid At:</strong> {{ $booking->payment->created_at->format('M d, Y H:i') }}</p>
            </div>
        @else
            <p class="text-gray-600">No payment record found for this booking.</p>
        @endif
    </div>

    <!-- Review Information Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Review</h2>
        @if ($booking->review)
            <div class="space-y-2">
                <p><strong>Rating:</strong> {{ $booking->review->rating }} / 5</p>
                <p><strong>Comment:</strong> {{ $booking->review->comment }}</p>
                <p><strong>Reviewed At:</strong> {{ $booking->review->created_at->format('M d, Y') }}</p>
            </div>
        @else
            <p class="text-gray-600">No review submitted for this booking yet.</p>
        @endif
    </div>

    <!-- Update Booking Status Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Update Status</h2>
        <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Booking Status</label>
                <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand focus:border-brand sm:text-sm">
                    @foreach(['pending', 'confirmed', 'completed', 'cancelled', 'refunded'] as $status)
                        <option value="{{ $status }}" {{ $booking->status == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
                Update Status
            </button>
        </form>
        @error('status')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
        @if(session('success'))
            <p class="text-green-500 text-xs mt-1">{{ session('success') }}</p>
        @endif
    </div>

</div>
@endsection

