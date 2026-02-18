@extends('layouts.admin')

@section('title', 'Edit Bill')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bills.index') }}" class="text-brand hover:text-indigo-900 text-sm">
        &larr; Back to Bills
    </a>
</div>

<div class="max-w-2xl">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Bill: {{ $bill->type_label }}</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.bills.update', $bill) }}">
            @csrf
            @method('PUT')

            <!-- Apartment -->
            <div class="mb-4">
                <label for="apartment_id" class="block text-sm font-medium text-gray-700 mb-1">Apartment</label>
                <select name="apartment_id" id="apartment_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">No Apartment (General Bill)</option>
                    @foreach($apartments as $apartment)
                        <option value="{{ $apartment->id }}" {{ old('apartment_id', $bill->apartment_id) == $apartment->id ? 'selected' : '' }}>
                            {{ $apartment->title }}
                        </option>
                    @endforeach
                </select>
                @error('apartment_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                <select name="type" id="type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                        required>
                    <option value="">Select Type</option>
                    <option value="electricity" {{ old('type', $bill->type) == 'electricity' ? 'selected' : '' }}>Electricity</option>
                    <option value="water" {{ old('type', $bill->type) == 'water' ? 'selected' : '' }}>Water</option>
                    <option value="internet" {{ old('type', $bill->type) == 'internet' ? 'selected' : '' }}>Internet</option>
                    <option value="security" {{ old('type', $bill->type) == 'security' ? 'selected' : '' }}>Security</option>
                    <option value="garbage" {{ old('type', $bill->type) == 'garbage' ? 'selected' : '' }}>Garbage</option>
                    <option value="other" {{ old('type', $bill->type) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                          placeholder="Optional description...">{{ old('description', $bill->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount *</label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $bill->amount) }}" step="0.01" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                       required>
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Due Date -->
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date *</label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $bill->due_date->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                       required>
                @error('due_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Paid Date -->
            <div class="mb-4">
                <label for="paid_date" class="block text-sm font-medium text-gray-700 mb-1">Paid Date</label>
                <input type="date" name="paid_date" id="paid_date" value="{{ old('paid_date', $bill->paid_date?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('paid_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                        required>
                    <option value="pending" {{ old('status', $bill->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('status', $bill->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="overdue" {{ old('status', $bill->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Reference Number -->
            <div class="mb-4">
                <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                <input type="text" name="reference_number" id="reference_number" value="{{ old('reference_number', $bill->reference_number) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                       placeholder="Optional">
                @error('reference_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand"
                          placeholder="Optional notes...">{{ old('notes', $bill->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
                        Update Bill
                    </button>
                    <a href="{{ route('admin.bills.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                </div>
        </form>

                <!-- Delete Button -->
                <form method="POST" action="{{ route('admin.bills.destroy', $bill) }}"
                      onsubmit="return confirm('Are you sure you want to delete this bill?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium">
                        Delete Bill
                    </button>
                </form>
            </div>
    </div>
</div>
@endsection

