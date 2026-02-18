@extends('layouts.admin')

@section('title', 'Occupancy Report')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Occupancy Report</h1>

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
            <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">Apply Filter</button>
        </div>
    </form>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Average Occupancy</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['avg_occupancy'], 1) }}%</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Nights Booked</p>
        <p class="text-3xl font-bold text-brand mt-2">{{ $stats['total_nights_booked'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Available Nights</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['total_available_nights'] }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Peak Season</p>
        <p class="text-xl font-bold text-gray-900 mt-2">{{ $stats['peak_season'] }}</p>
    </div>
</div>

<!-- Monthly Occupancy Trend -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">Monthly Occupancy Rate</h2>
    @if(count($monthlyOccupancy) > 0)
        <div class="h-64 flex items-end space-x-2">
            @foreach($monthlyOccupancy as $data)
                <div class="flex-1 flex flex-col justify-end items-center">
                    <span class="text-xs text-gray-600 mb-1">{{ number_format($data['occupancy_rate'], 1) }}%</span>
                    <div class="w-full bg-brand rounded-t hover:bg-brand-dark transition"
                         style="height: {{ max($data['occupancy_rate'] * 2, 2) }}px"
                         title="{{ $data['month'] }}: {{ number_format($data['occupancy_rate'], 1) }}%">
                    </div>
                    <p class="text-xs text-center text-gray-500 mt-2">{{ \Carbon\Carbon::parse($data['month'])->format('M') }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-8">No occupancy data available</p>
    @endif
</div>

<!-- Occupancy by Apartment -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Occupancy by Apartment</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Nights</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booked Nights</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Occupancy Rate</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($apartmentOccupancy as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item['apartment_name'] }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item['total_nights'] }} nights</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $item['booked_nights'] }} nights</td>
                    <td class="px-6 py-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2" style="max-width: 100px;">
                                <div class="bg-brand h-2 rounded-full" style="width: {{ $item['occupancy_rate'] }}%"></div>
                            </div>
                            <span class="font-medium">{{ number_format($item['occupancy_rate'], 1) }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $item['occupancy_rate'] >= 70 ? 'bg-green-100 text-green-800' : '' }}
                            {{ $item['occupancy_rate'] >= 40 && $item['occupancy_rate'] < 70 ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $item['occupancy_rate'] < 40 ? 'bg-red-100 text-red-800' : '' }}">
                            {{ $item['occupancy_rate'] >= 70 ? 'High' : ($item['occupancy_rate'] >= 40 ? 'Medium' : 'Low') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No occupancy data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

