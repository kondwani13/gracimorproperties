@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Bookings Management</h1>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="grid grid-cols-5 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by booking ID, guest..."
               class="px-4 py-2 border rounded-md">
        <select name="status" class="px-4 py-2 border rounded-md">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <input type="date" name="from_date" value="{{ request('from_date') }}" placeholder="From Date"
               class="px-4 py-2 border rounded-md">
        <input type="date" name="to_date" value="{{ request('to_date') }}" placeholder="To Date"
               class="px-4 py-2 border rounded-md">
        <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
            Filter
        </button>
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $booking->booking_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ Str::limit($booking->apartment->title, 30) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        <div>{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->number_of_nights }} nights</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        K{{ number_format($booking->total_amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $booking->status == 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-brand hover:text-indigo-900">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No bookings found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($bookings->hasPages())
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
@endif
@endsection

