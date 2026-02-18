@extends('layouts.admin')

@section('title', 'Bill Deposit Report')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Bill Deposit Report</h1>

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
        <p class="text-sm font-medium text-gray-600">Total Billed</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['total_amount'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Paid</p>
        <p class="text-3xl font-bold text-green-600 mt-2">K{{ number_format($stats['total_paid'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Pending</p>
        <p class="text-3xl font-bold text-yellow-600 mt-2">K{{ number_format($stats['total_pending'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Overdue</p>
        <p class="text-3xl font-bold text-red-600 mt-2">K{{ number_format($stats['total_overdue'], 2) }}</p>
    </div>
</div>

<!-- By Type -->
<div class="bg-white rounded-lg shadow overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Bills by Type</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Collection Rate</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($byType as $type => $data)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $type) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $data['count'] }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($data['total'], 2) }}</td>
                    <td class="px-6 py-4 text-sm text-green-600 font-medium">K{{ number_format($data['paid'], 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        @if($data['total'] > 0)
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(($data['paid'] / $data['total']) * 100, 100) }}%"></div>
                                </div>
                                <span>{{ number_format(($data['paid'] / $data['total']) * 100, 1) }}%</span>
                            </div>
                        @else
                            0%
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Monthly Trend -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Monthly Bill Trend</h2>
    @if($byMonth->count() > 0)
        <div class="h-64 flex items-end space-x-2">
            @php $maxVal = $byMonth->max(fn($m) => $m['total']) ?: 1; @endphp
            @foreach($byMonth as $month => $data)
                <div class="flex-1 flex flex-col items-center">
                    <span class="text-xs text-gray-600 mb-1">K{{ number_format($data['total'], 0) }}</span>
                    <div class="w-full relative rounded-t" style="height: {{ ($data['total'] / $maxVal) * 200 }}px">
                        <div class="absolute bottom-0 w-full bg-gray-300 rounded-t" style="height: 100%"></div>
                        @if($data['total'] > 0)
                            <div class="absolute bottom-0 w-full bg-green-500 rounded-t" style="height: {{ ($data['paid'] / $data['total']) * 100 }}%"></div>
                        @endif
                    </div>
                    <span class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}</span>
                </div>
            @endforeach
        </div>
        <div class="flex items-center gap-4 mt-4 justify-center text-xs text-gray-600">
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-green-500 rounded inline-block"></span> Paid</span>
            <span class="flex items-center gap-1"><span class="w-3 h-3 bg-gray-300 rounded inline-block"></span> Unpaid</span>
        </div>
    @else
        <p class="text-gray-500 text-center py-8">No data available for the selected period</p>
    @endif
</div>
@endsection

