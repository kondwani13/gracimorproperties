@extends('layouts.admin')

@section('title', 'Record Complaint')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.complaints.index') }}" class="text-brand hover:text-indigo-700 text-sm font-medium">
        &larr; Back to Complaints
    </a>
</div>

<h1 class="text-2xl font-semibold mb-6">Record New Complaint</h1>

<form action="{{ route('admin.complaints.store') }}" method="POST" class="space-y-6">
    @csrf

    <!-- Complainant Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Complainant Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="complainant_name" class="block text-sm font-medium text-gray-700 mb-2">Complainant Name *</label>
                <input type="text" name="complainant_name" id="complainant_name" value="{{ old('complainant_name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                @error('complainant_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Related Tenant</label>
                <select name="tenant_id" id="tenant_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">-- Select Tenant (Optional) --</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->name }}
                        </option>
                    @endforeach
                </select>
                @error('tenant_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="booking_id" class="block text-sm font-medium text-gray-700 mb-2">Related Booking</label>
                <select name="booking_id" id="booking_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="">-- Select Booking (Optional) --</option>
                    @foreach($bookings as $booking)
                        <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                            Booking #{{ $booking->id }} - {{ $booking->user->name ?? 'N/A' }} - {{ $booking->apartment->title ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                @error('booking_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Complaint Details -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Complaint Details</h2>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                    <select name="priority" id="priority" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                        <option value="">-- Select Priority --</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea name="description" id="description" rows="6" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex space-x-4">
        <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
            Record Complaint
        </button>
        <a href="{{ route('admin.complaints.index') }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 text-sm font-medium text-gray-700">
            Cancel
        </a>
    </div>
</form>
@endsection

