@extends('layouts.admin')

@section('title', 'Bills')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">Bills</h1>
    <a href="{{ route('admin.bills.create') }}"
       class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
        Add Bill
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Types</option>
            <option value="electricity" {{ request('type') == 'electricity' ? 'selected' : '' }}>Electricity</option>
            <option value="water" {{ request('type') == 'water' ? 'selected' : '' }}>Water</option>
            <option value="internet" {{ request('type') == 'internet' ? 'selected' : '' }}>Internet</option>
            <option value="security" {{ request('type') == 'security' ? 'selected' : '' }}>Security</option>
            <option value="garbage" {{ request('type') == 'garbage' ? 'selected' : '' }}>Garbage</option>
            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
        </select>

        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
        </select>

        <select name="apartment_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Apartments</option>
            @foreach($apartments as $apartment)
                <option value="{{ $apartment->id }}" {{ request('apartment_id') == $apartment->id ? 'selected' : '' }}>
                    {{ $apartment->title }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
            Filter
        </button>

        <a href="{{ route('admin.bills.index') }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 text-sm font-medium text-gray-700 text-center">
            Clear
        </a>
    </form>
</div>

<!-- Bills Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref #</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($bills as $bill)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $bill->type_label }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $bill->apartment->title ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                        {{ $bill->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        K{{ number_format($bill->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $bill->due_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $bill->paid_date ? $bill->paid_date->format('M d, Y') : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $bill->status_color }}-100 text-{{ $bill->status_color }}-800">
                            {{ ucfirst($bill->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $bill->reference_number ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('admin.bills.edit', $bill) }}" class="text-brand hover:text-indigo-900">Edit</a>
                        <span class="text-gray-300 mx-1">|</span>
                        <form method="POST" action="{{ route('admin.bills.destroy', $bill) }}" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this bill?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        No bills found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($bills->hasPages())
    <div class="mt-6">
        {{ $bills->links() }}
    </div>
@endif
@endsection

