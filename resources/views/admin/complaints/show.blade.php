@extends('layouts.admin')

@section('title', 'Complaint Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.complaints.index') }}" class="text-brand hover:text-indigo-700 text-sm font-medium">
        &larr; Back to Complaints
    </a>
</div>

<h1 class="text-2xl font-semibold text-gray-800 mb-6">Complaint Details</h1>

<!-- Two-column layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Left: Complaint Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Complaint Information</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Complainant:</span>
                <span class="font-medium text-gray-900">{{ $complaint->complainant_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Subject:</span>
                <span class="font-medium text-gray-900">{{ $complaint->subject }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Priority:</span>
                <span>
                    @php
                        $priorityColors = [
                            'high' => 'bg-red-100 text-red-800',
                            'medium' => 'bg-yellow-100 text-yellow-800',
                            'low' => 'bg-green-100 text-green-800',
                        ];
                        $priorityClass = $priorityColors[$complaint->priority] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $priorityClass }}">
                        {{ ucfirst($complaint->priority) }}
                    </span>
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status:</span>
                <span>
                    @php
                        $statusColors = [
                            'open' => 'bg-red-100 text-red-800',
                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                            'resolved' => 'bg-green-100 text-green-800',
                            'closed' => 'bg-gray-100 text-gray-800',
                        ];
                        $statusClass = $statusColors[$complaint->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Created:</span>
                <span class="font-medium text-gray-900">{{ $complaint->created_at->format('M d, Y h:i A') }}</span>
            </div>
            @if($complaint->tenant)
                <div class="flex justify-between">
                    <span class="text-gray-500">Tenant:</span>
                    <span class="font-medium text-gray-900">{{ $complaint->tenant->name }}</span>
                </div>
                @if($complaint->tenant->apartment)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Apartment:</span>
                        <span class="font-medium text-gray-900">{{ $complaint->tenant->apartment->title }}</span>
                    </div>
                @endif
            @endif
            @if($complaint->booking)
                <div class="flex justify-between">
                    <span class="text-gray-500">Booking:</span>
                    <span class="font-medium text-gray-900">
                        #{{ $complaint->booking->id }}
                        @if($complaint->booking->apartment)
                            - {{ $complaint->booking->apartment->title }}
                        @endif
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Right: Update Status Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Status</h3>
        <form action="{{ route('admin.complaints.update-status', $complaint) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
                    <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-2">Resolution Notes</label>
                <textarea name="resolution_notes" id="resolution_notes" rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">{{ old('resolution_notes', $complaint->resolution_notes) }}</textarea>
                @error('resolution_notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
                Update Status
            </button>
        </form>
    </div>
</div>

<!-- Full Description -->
<div class="bg-white rounded-lg shadow p-6 mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Description</h3>
    <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $complaint->description }}</div>
</div>

<!-- Resolution Notes (if resolved/closed) -->
@if($complaint->resolved_at && $complaint->resolution_notes)
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Resolution</h3>
        <div class="space-y-3">
            <div class="text-sm">
                <span class="text-gray-500">Resolved At:</span>
                <span class="font-medium text-gray-900 ml-2">{{ $complaint->resolved_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="text-sm text-gray-700 whitespace-pre-wrap mt-2">{{ $complaint->resolution_notes }}</div>
        </div>
    </div>
@endif
@endsection

