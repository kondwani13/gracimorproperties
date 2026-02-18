@extends('layouts.admin')

@section('title', 'Rent Collection')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Rent Collection</h1>
    <a href="{{ route('admin.rent-payments.create') }}"
       class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
        Add Rent Payment
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <select name="tenant_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Tenants</option>
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                    {{ $tenant->name }}
                </option>
            @endforeach
        </select>

        <input type="month" name="month" value="{{ request('month') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
               placeholder="Month">

        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Status</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
        </select>

        <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
            Filter
        </button>
    </form>
</div>

<!-- Payments Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($payments as $payment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $payment->tenant->name }}</div>
                        <div class="text-xs text-gray-500">{{ $payment->tenant->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $payment->tenant->apartment->title ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($payment->month . '-01')->format('F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        K{{ number_format($payment->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $payment->status_color }}-100 text-{{ $payment->status_color }}-800">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.rent-payments.show', $payment) }}" class="text-brand hover:text-indigo-900">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        No rent payments found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($payments->hasPages())
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
@endif
@endsection

