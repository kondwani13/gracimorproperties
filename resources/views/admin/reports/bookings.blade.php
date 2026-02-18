@extends('layouts.admin')

@section('title', 'Bookings Report')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Bookings Report</h1>

<!-- Filter Form -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand focus:border-brand sm:text-sm">
        </div>
        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand focus:border-brand sm:text-sm">
        </div>
        <button type="submit" class="col-span-1 md:col-span-1 px-4 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
            Apply Filter
        </button>
        <a href="{{ route('admin.reports.bookings') }}" class="col-span-1 md:col-span-1 px-4 py-2 text-center border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
            Clear Filter
        </a>
    </form>
</div>

<!-- Stats Grid - For Selected Period -->
<h2 class="text-xl font-semibold mb-4">Statistics for Selected Period</h2>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Bookings (Period)</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Confirmed (Period)</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['confirmed_bookings'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Pending (Period)</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_bookings'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Cancelled (Period)</p>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['cancelled_bookings'] ?? 0 }}</p>
    </div>
</div>

<!-- Stats Grid - All Time -->
<h2 class="text-xl font-semibold mb-4">All-Time Statistics</h2>
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Bookings (All Time)</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $allTimeStats['total_bookings'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Confirmed (All Time)</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $allTimeStats['confirmed_bookings'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Pending (All Time)</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $allTimeStats['pending_bookings'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Cancelled (All Time)</p>
        <p class="text-3xl font-bold text-red-600 mt-2">{{ $allTimeStats['cancelled_bookings'] ?? 0 }}</p>
    </div>
</div>

<!-- Bookings by Status -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Bookings by Status (Period)</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                    <span class="text-sm text-gray-700">Confirmed</span>
                </div>
                <span class="text-sm font-semibold">{{ $stats['confirmed_bookings'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-3"></div>
                    <span class="text-sm text-gray-700">Pending</span>
                </div>
                <span class="text-sm font-semibold">{{ $stats['pending_bookings'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                    <span class="text-sm text-gray-700">Completed</span>
                </div>
                <span class="text-sm font-semibold">{{ $stats['completed_bookings'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-3"></div>
                    <span class="text-sm font-semibold">{{ $stats['cancelled_bookings'] ?? 0 }}</span>
                </div>
                <span class="text-sm text-gray-700">Cancelled</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Booking Trends (Period)</h2>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Average Booking Value</span>
                    <span class="font-semibold">K{{ number_format($stats['avg_booking_value'] ?? 0, 2) }}</span>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Average Nights</span>
                    <span class="font-semibold">{{ number_format($stats['avg_nights'] ?? 0, 1) }} nights</span>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Average Guests</span>
                    <span class="font-semibold">{{ number_format($stats['avg_guests'] ?? 0, 1) }} guests</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Performing Apartments -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Top Performing Apartments (Period)</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Bookings</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Nights</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($topApartments ?? [] as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item['bookings_count'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item['total_nights'] }} nights</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($item['revenue'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No booking data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
