@extends('layouts.admin')

@section('title', 'Manage Apartments')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Apartments</h1>
    <a href="{{ route('admin.apartments.create') }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md">
        + Add New Apartment
    </a>
</div>

<!-- Search & Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search apartments..."
               class="flex-1 px-4 py-2 border rounded-md focus:ring-brand focus:border-brand">
        <select name="status" class="px-4 py-2 border rounded-md">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
            Search
        </button>
    </form>
</div>

<!-- Apartments Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price/Night</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($apartments as $apartment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                <img class="h-10 w-10 rounded object-cover"
                                     src="{{ $apartment->images->first()->url ?? asset('img/bg/vedio-img.png') }}"
                                     alt="{{ $apartment->title }}"
                                     onerror="this.onerror=null;this.src='{{ asset('img/bg/vedio-img.png') }}';">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($apartment->title, 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $apartment->city }}, {{ $apartment->state }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        K{{ number_format($apartment->price_per_night) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $apartment->booking_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($apartment->rating)
                            ⭐ {{ number_format($apartment->rating, 1) }} ({{ $apartment->total_reviews }})
                        @else
                            <span class="text-gray-400">No reviews</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $apartment->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $apartment->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.apartments.edit', $apartment) }}" class="text-brand hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('admin.apartments.destroy', $apartment) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        No apartments found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($apartments->hasPages())
    <div class="mt-6">
        {{ $apartments->links() }}
    </div>
@endif
@endsection

