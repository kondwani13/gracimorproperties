@extends('layouts.admin')

@section('title', 'Edit Tenant')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.tenants.index') }}" class="text-brand hover:text-indigo-700 text-sm font-medium">
        &larr; Back to Tenants
    </a>
</div>

<h1 class="text-2xl font-semibold mb-6">Edit Tenant: {{ $tenant->name }}</h1>

<form action="{{ route('admin.tenants.update', $tenant) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Personal Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Personal Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $tenant->email) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $tenant->phone) }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">ID Number</label>
                <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $tenant->id_number) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('id_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Emergency Contact -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Emergency Contact</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact Name</label>
                <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $tenant->emergency_contact_name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('emergency_contact_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $tenant->emergency_contact_phone) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('emergency_contact_phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Lease Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Lease Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="apartment_id" class="block text-sm font-medium text-gray-700 mb-2">Apartment</label>
                <select name="apartment_id" id="apartment_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">Select an apartment</option>
                    @foreach($apartments as $apartment)
                        <option value="{{ $apartment->id }}" {{ old('apartment_id', $tenant->apartment_id) == $apartment->id ? 'selected' : '' }}>
                            {{ $apartment->title }} - {{ $apartment->address }}
                        </option>
                    @endforeach
                </select>
                @error('apartment_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rent_amount" class="block text-sm font-medium text-gray-700 mb-2">Rent Amount</label>
                <input type="number" name="rent_amount" id="rent_amount" value="{{ old('rent_amount', $tenant->rent_amount) }}" min="0" step="0.01"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('rent_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="lease_start" class="block text-sm font-medium text-gray-700 mb-2">Lease Start Date</label>
                <input type="date" name="lease_start" id="lease_start" value="{{ old('lease_start', $tenant->lease_start?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('lease_start')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="lease_end" class="block text-sm font-medium text-gray-700 mb-2">Lease End Date</label>
                <input type="date" name="lease_end" id="lease_end" value="{{ old('lease_end', $tenant->lease_end?->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('lease_end')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $tenant->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="evicted" {{ old('status', $tenant->status) == 'evicted' ? 'selected' : '' }}>Evicted</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Additional Notes</h2>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
            <textarea name="notes" id="notes" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('notes', $tenant->notes) }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex space-x-4">
        <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
            Update Tenant
        </button>
        <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 text-sm font-medium text-gray-700">
            Cancel
        </a>
    </div>
</form>
@endsection

