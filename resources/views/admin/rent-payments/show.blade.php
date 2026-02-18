@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.rent-payments.index') }}" class="text-brand hover:text-indigo-900 text-sm">
        &larr; Back to Rent Collection
    </a>
</div>

<div class="max-w-3xl">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Payment Detail</h1>

    <!-- Payment Info Card -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Payment Information</h2>
            <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $rentPayment->status_color }}-100 text-{{ $rentPayment->status_color }}-800">
                {{ ucfirst($rentPayment->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Amount</p>
                <p class="text-lg font-semibold text-gray-900">K{{ number_format($rentPayment->amount, 2) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Month</p>
                <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($rentPayment->month . '-01')->format('F Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Payment Method</p>
                <p class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $rentPayment->payment_method)) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Payment Date</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->payment_date ? $rentPayment->payment_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Receipt Number</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->receipt_number ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Recorded On</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        @if($rentPayment->notes)
            <div class="mt-6 pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-1">Notes</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Tenant Info Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Tenant Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Name</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->tenant->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->tenant->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Phone</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->tenant->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Apartment</p>
                <p class="text-sm text-gray-900">{{ $rentPayment->tenant->apartment->title ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Lease Period</p>
                <p class="text-sm text-gray-900">
                    {{ $rentPayment->tenant->lease_start ? $rentPayment->tenant->lease_start->format('M d, Y') : 'N/A' }}
                    -
                    {{ $rentPayment->tenant->lease_end ? $rentPayment->tenant->lease_end->format('M d, Y') : 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Monthly Rent</p>
                <p class="text-sm text-gray-900">K{{ number_format($rentPayment->tenant->rent_amount, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

