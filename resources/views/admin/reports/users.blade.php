@extends('layouts.admin')

@section('title', 'Users Report')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Users Report</h1>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Users</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Active Users</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active_users'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">New This Month</p>
        <p class="text-3xl font-bold text-brand mt-2">{{ $stats['new_users_month'] ?? 0 }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm font-medium text-gray-600">Total Admins</p>
        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $stats['total_admins'] ?? 0 }}</p>
    </div>
</div>

<!-- User Growth Chart -->
<div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-lg font-semibold mb-4">User Registration Growth</h2>
    <div class="h-64 flex items-end space-x-2">
        @forelse($userGrowth ?? [] as $data)
            <div class="flex-1 bg-brand rounded-t hover:bg-brand-dark transition"
                 style="height: {{ ($data['count'] / max(array_column($userGrowth, 'count'))) * 100 }}%"
                 title="{{ $data['month'] }}: {{ $data['count'] }} users">
            </div>
        @empty
            <p class="text-gray-500 text-center w-full">No user growth data available</p>
        @endforelse
    </div>
</div>

<!-- Top Users -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold">Top Users by Bookings</h2>
    </div>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Bookings</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($topUsers ?? [] as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="h-8 w-8 rounded-full" src="{{ $user['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) }}" alt="">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $user['name'] }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $user['email'] }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user['bookings_count'] }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">K{{ number_format($user['total_spent'], 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ date('M d, Y', strtotime($user['joined_date'])) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No user data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

