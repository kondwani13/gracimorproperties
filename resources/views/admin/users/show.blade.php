@extends('layouts.admin')

@section('title', 'User Profile: ' . $user->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">User Profile: {{ $user->name }}</h1>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
        &larr; Back to Users
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- User Details Card -->
    <div class="bg-white rounded-lg shadow p-6 col-span-1 md:col-span-2">
        <h2 class="text-xl font-semibold mb-4">User Information</h2>
        <div class="flex items-center space-x-4 mb-4">
            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                 alt="{{ $user->name }}" 
                 class="h-20 w-20 rounded-full object-cover">
            <div>
                <p class="text-xl font-medium">{{ $user->name }}</p>
                <p class="text-gray-600">{{ $user->email }}</p>
            </div>
        </div>
        <div class="space-y-2 text-gray-700">
            <p><strong>Role:</strong> 
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </p>
            <p><strong>Status:</strong> 
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </p>
            @if ($user->phone)
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
            @endif
            @if ($user->address)
                <p><strong>Address:</strong> {{ $user->address }}, {{ $user->city }}, {{ $user->state }}, {{ $user->postal_code }}, {{ $user->country }}</p>
            @endif
            <p><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>
        </div>
    </div>

    <!-- User Statistics Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Statistics</h2>
        <div class="space-y-2">
            <p><strong>Total Bookings:</strong> {{ $stats['total_bookings'] ?? 0 }}</p>
            <p><strong>Completed Bookings:</strong> {{ $stats['completed_bookings'] ?? 0 }}</p>
            <p><strong>Cancelled Bookings:</strong> {{ $stats['cancelled_bookings'] ?? 0 }}</p>
            <p class="text-lg font-bold"><strong>Total Spent:</strong> K{{ number_format($stats['total_spent'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Actions Card -->
    <div class="bg-white rounded-lg shadow p-6 col-span-1 md:col-span-3 lg:col-span-1">
        <h2 class="text-xl font-semibold mb-4">Actions</h2>
        <div class="space-y-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand text-base font-medium text-white hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand sm:text-sm">
                Edit Profile
            </a>

            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand sm:text-sm">
                    {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                </button>
            </form>

            @if($user->id != auth()->id()) {{-- Prevent user from deleting their own account --}}
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                        Delete User
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection

