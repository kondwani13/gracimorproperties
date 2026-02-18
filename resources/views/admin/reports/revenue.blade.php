@extends('layouts.admin')

@section('title', 'Revenue Report')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Revenue Report</h1>

<!-- Filter Period -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex items-center gap-4 flex-wrap">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
            <input type="date" name="start_date" value="{{ is_string($startDate) ? $startDate : $startDate->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-md">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <input type="date" name="end_date" value="{{ is_string($endDate) ? $endDate : $endDate->format('Y-m-d') }}" class="px-4 py-2 border border-gray-300 rounded-md">
        </div>

        <div class="flex items-end">
            <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
                Apply Filter
            </button>
        </div>
    </form>
</div>

<!-- Revenue Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['total_revenue'], 2) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Paid Bookings</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['paid_bookings'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Average Booking</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['average_booking'], 2) }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Refunds</p>
        <p class="text-3xl font-bold text-red-600 mt-2">K{{ number_format($stats['total_refunds'], 2) }}</p>
    </div>
</div>

<!-- Revenue Chart -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Revenue Over Time</h2>
    @if(count($revenueData) > 0)
        @php $maxAmount = max(array_column($revenueData, 'amount')) ?: 1; @endphp
        <div class="h-64 flex items-end space-x-2">
            @foreach($revenueData as $data)
                <div class="flex-1 flex flex-col items-center">
                    <span class="text-xs text-gray-600 mb-1">K{{ number_format($data['amount'], 0) }}</span>
                    <div class="w-full bg-brand rounded-t hover:bg-brand-dark transition"
                         style="height: {{ ($data['amount'] / $maxAmount) * 200 }}px"
                         title="{{ $data['date'] }}: K{{ number_format($data['amount'], 2) }}">
                    </div>
                    <span class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($data['date'])->format('M Y') }}</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-8">No revenue data available for the selected period</p>
    @endif
</div>

<!-- Revenue by Apartment -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Revenue by Apartment</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Revenue</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Revenue</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($apartmentRevenue as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['apartment_name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item['bookings_count'] }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($item['total_revenue'], 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">K{{ number_format($item['avg_revenue'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">No revenue data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

