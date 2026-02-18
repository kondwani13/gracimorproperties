@extends('layouts.admin')

@section('title', 'Add Rent Payment')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.rent-payments.index') }}" class="text-brand hover:text-indigo-900 text-sm">
        &larr; Back to Rent Collection
    </a>
</div>

<div class="max-w-2xl">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Add Rent Payment</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.rent-payments.store') }}">
            @csrf

            <!-- Tenant -->
            <div class="mb-4">
                <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-1">Tenant</label>
                <select name="tenant_id" id="tenant_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                        required>
                    <option value="">Select Tenant</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }} - {{ $tenant->apartment->title ?? 'No Apartment' }}
                        </option>
                    @endforeach
                </select>
                @error('tenant_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount') }}" step="0.01" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                       required>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Month -->
            <div class="mb-4">
                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                <input type="month" name="month" id="month" value="{{ old('month', now()->format('Y-m')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                       required>
                @error('month')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Method -->
            <div class="mb-4">
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" id="payment_method"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                        required>
                    <option value="">Select Method</option>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Payment Date -->
            <div class="mb-4">
                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                       required>
                @error('payment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Receipt Number -->
            <div class="mb-4">
                <label for="receipt_number" class="block text-sm font-medium text-gray-700 mb-1">Receipt Number</label>
                <input type="text" name="receipt_number" id="receipt_number" value="{{ old('receipt_number') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                       placeholder="Optional">
                @error('receipt_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                        required>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                          placeholder="Optional notes...">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex items-center space-x-4">
                <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
                    Save Payment
                </button>
                <a href="{{ route('admin.rent-payments.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

