@extends('layouts.admin')

@section('title', 'Maintenance Cost Report')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Maintenance Cost Report</h1>

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

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Cost</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['total_cost'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Pending</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">In Progress</p>
        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['in_progress'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Completed</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['completed'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- By Category -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Cost by Category</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($byCategory as $category => $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $category) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $data['count'] }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- By Apartment -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Cost by Apartment</h2>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($byApartment as $apartment => $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $apartment }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $data['count'] }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($data['total'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Monthly Trend -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Monthly Cost Trend</h2>
    @if($byMonth->count() > 0)
        <div class="h-64 flex items-end space-x-2">
            @php $maxVal = $byMonth->max() ?: 1; @endphp
            @foreach($byMonth as $month => $amount)
                <div class="flex-1 flex flex-col items-center">
                    <span class="text-xs text-gray-600 mb-1">K{{ number_format($amount, 0) }}</span>
                    <div class="w-full bg-orange-500 rounded-t hover:bg-orange-600 transition"
                         style="height: {{ ($amount / $maxVal) * 200 }}px"
                         title="{{ $month }}: K{{ number_format($amount, 2) }}">
                    </div>
                    <span class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}</span>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-center py-8">No data available for the selected period</p>
    @endif
</div>
@endsection

