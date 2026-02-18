@extends('layouts.admin')

@section('title', 'Maintenance Costs')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Maintenance Costs</h1>
    <a href="{{ route('admin.maintenance.create') }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md">
        + Add Maintenance Cost
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <select name="category" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Categories</option>
            <option value="plumbing" {{ request('category') == 'plumbing' ? 'selected' : '' }}>Plumbing</option>
            <option value="electrical" {{ request('category') == 'electrical' ? 'selected' : '' }}>Electrical</option>
            <option value="cleaning" {{ request('category') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
            <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
            <option value="painting" {{ request('category') == 'painting' ? 'selected' : '' }}>Painting</option>
            <option value="appliance" {{ request('category') == 'appliance' ? 'selected' : '' }}>Appliance</option>
        </select>

        <select name="status" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>

        <select name="apartment_id" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Apartments</option>
            @foreach($apartments as $apartment)
                <option value="{{ $apartment->id }}" {{ request('apartment_id') == $apartment->id ? 'selected' : '' }}>
                    {{ $apartment->title }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark">
            Filter
        </button>

        @if(request('category') || request('status') || request('apartment_id'))
            <a href="{{ route('admin.maintenance.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                Clear
            </a>
        @endif
    </form>
</div>

<!-- Maintenance Costs Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($costs as $cost)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                        {{ $cost->description }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $cost->apartment ? $cost->apartment->title : 'General/Building' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $cost->category_label }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        K{{ number_format($cost->amount, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $cost->date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($cost->status === 'pending')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($cost->status === 'in_progress')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">In Progress</span>
                        @elseif($cost->status === 'completed')
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Completed</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $cost->vendor ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.maintenance.edit', $cost) }}" class="text-brand hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('admin.maintenance.destroy', $cost) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this maintenance cost?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        No maintenance costs found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($costs->hasPages())
    <div class="mt-6">
        {{ $costs->links() }}
    </div>
@endif
@endsection

