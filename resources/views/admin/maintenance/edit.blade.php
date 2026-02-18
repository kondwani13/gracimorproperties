@extends('layouts.admin')

@section('title', 'Edit Maintenance Cost')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.maintenance.index') }}" class="text-brand hover:text-indigo-800">&larr; Back to Maintenance Costs</a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <h2 class="text-xl font-semibold mb-6">Edit Maintenance Cost</h2>

    <form method="POST" action="{{ route('admin.maintenance.update', $maintenance) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Apartment -->
            <div>
                <label for="apartment_id" class="block text-sm font-medium text-gray-700 mb-1">Apartment</label>
                <select name="apartment_id" id="apartment_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">General / Building</option>
                    @foreach($apartments as $apartment)
                        <option value="{{ $apartment->id }}" {{ old('apartment_id', $maintenance->apartment_id) == $apartment->id ? 'selected' : '' }}>
                            {{ $apartment->title }}
                        </option>
                    @endforeach
                </select>
                @error('apartment_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                <select name="category" id="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">Select Category</option>
                    <option value="plumbing" {{ old('category', $maintenance->category) == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
                    <option value="electrical" {{ old('category', $maintenance->category) == 'electrical' ? 'selected' : '' }}>Electrical</option>
                    <option value="cleaning" {{ old('category', $maintenance->category) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                    <option value="general" {{ old('category', $maintenance->category) == 'general' ? 'selected' : '' }}>General</option>
                    <option value="painting" {{ old('category', $maintenance->category) == 'painting' ? 'selected' : '' }}>Painting</option>
                    <option value="appliance" {{ old('category', $maintenance->category) == 'appliance' ? 'selected' : '' }}>Appliance</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                <input type="number" name="amount" id="amount" value="{{ old('amount', $maintenance->amount) }}" step="0.01" min="0" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                <input type="date" name="date" id="date" value="{{ old('date', $maintenance->date->format('Y-m-d')) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="pending" {{ old('status', $maintenance->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $maintenance->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $maintenance->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vendor -->
            <div>
                <label for="vendor" class="block text-sm font-medium text-gray-700 mb-1">Vendor</label>
                <input type="text" name="vendor" id="vendor" value="{{ old('vendor', $maintenance->vendor) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('vendor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vendor Phone -->
            <div>
                <label for="vendor_phone" class="block text-sm font-medium text-gray-700 mb-1">Vendor Phone</label>
                <input type="text" name="vendor_phone" id="vendor_phone" value="{{ old('vendor_phone', $maintenance->vendor_phone) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('vendor_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
            <textarea name="description" id="description" rows="3" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('description', $maintenance->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes -->
        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea name="notes" id="notes" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('notes', $maintenance->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6 flex items-center gap-4">
            <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
                Update Maintenance Cost
            </button>
            <a href="{{ route('admin.maintenance.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>

    <!-- Delete -->
    <div class="mt-6 pt-6 border-t border-gray-200">
        <form action="{{ route('admin.maintenance.destroy', $maintenance) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this maintenance cost? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                Delete Maintenance Cost
            </button>
        </form>
    </div>
</div>
@endsection

