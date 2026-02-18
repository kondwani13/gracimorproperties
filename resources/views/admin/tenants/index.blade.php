@extends('layouts.admin')

@section('title', 'Manage Tenants')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Tenants</h1>
    <a href="{{ route('admin.tenants.create') }}" class="px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
        + Add Tenant
    </a>
</div>

<!-- Search & Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email or phone..."
               class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-brand focus:border-brand">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="evicted" {{ request('status') == 'evicted' ? 'selected' : '' }}>Evicted</option>
        </select>
        <button type="submit" class="px-6 py-2 bg-brand text-white rounded-md hover:bg-brand-dark text-sm font-medium">
            Search
        </button>
        @if(request('search') || request('status'))
            <a href="{{ route('admin.tenants.index') }}" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 text-sm font-medium text-gray-700">
                Clear
            </a>
        @endif
    </form>
</div>

<!-- Tenants Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apartment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lease Period</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rent Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($tenants as $tenant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                        @if($tenant->email)
                            <div class="text-sm text-gray-500">{{ $tenant->email }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $tenant->phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($tenant->apartment)
                            {{ Str::limit($tenant->apartment->title, 30) }}
                        @else
                            <span class="text-gray-400">Not assigned</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        @if($tenant->lease_start && $tenant->lease_end)
                            {{ $tenant->lease_start->format('M d, Y') }} - {{ $tenant->lease_end->format('M d, Y') }}
                        @elseif($tenant->lease_start)
                            {{ $tenant->lease_start->format('M d, Y') }} - Present
                        @else
                            <span class="text-gray-400">Not set</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        @if($tenant->rent_amount)
                            K{{ number_format($tenant->rent_amount, 2) }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'inactive' => 'bg-gray-100 text-gray-800',
                                'evicted' => 'bg-red-100 text-red-800',
                            ];
                            $colorClass = $statusColors[$tenant->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $colorClass }}">
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="text-brand hover:text-indigo-900 mr-3">View</a>
                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="text-brand hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this tenant?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="text-gray-500">
                            <p class="text-lg font-medium">No tenants found</p>
                            <p class="mt-1 text-sm">Get started by adding a new tenant.</p>
                            <a href="{{ route('admin.tenants.create') }}" class="mt-4 inline-block px-4 py-2 bg-brand hover:bg-brand-dark text-white rounded-md text-sm font-medium">
                                + Add Tenant
                            </a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($tenants->hasPages())
    <div class="mt-6">
        {{ $tenants->links() }}
    </div>
@endif
@endsection

