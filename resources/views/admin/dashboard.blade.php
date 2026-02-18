@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Apartments</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_apartments'] ?? 0 }}</p>
            </div>
            <div class="bg-indigo-100 rounded-full p-3">
                <span class="text-2xl">🏠</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] ?? 0 }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <span class="text-2xl">👥</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Bookings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_bookings'] ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <span class="text-2xl">📅</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['total_revenue'] ?? 0) }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <span class="text-2xl">💰</span>
            </div>
        </div>
    </div>
</div>

<!-- Property Management KPIs -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Tenants</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_tenants'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">of {{ $stats['total_tenants'] ?? 0 }} total</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <span class="text-2xl">🏷️</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Rent Collected ({{ now()->format('M') }})</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['monthly_rent_collected'] ?? 0) }}</p>
                @if(($stats['pending_rent'] ?? 0) > 0)
                    <p class="text-xs text-red-500 mt-1">K{{ number_format($stats['pending_rent']) }} pending</p>
                @else
                    <p class="text-xs text-green-500 mt-1">All collected</p>
                @endif
            </div>
            <div class="bg-emerald-100 rounded-full p-3">
                <span class="text-2xl">💵</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Maintenance ({{ now()->format('M') }})</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['maintenance_costs_month'] ?? 0) }}</p>
            </div>
            <div class="bg-orange-100 rounded-full p-3">
                <span class="text-2xl">🔧</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Open Complaints</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['open_complaints'] ?? 0 }}</p>
            </div>
            <div class="bg-red-100 rounded-full p-3">
                <span class="text-2xl">📝</span>
            </div>
        </div>
    </div>
</div>

<!-- Operations KPIs -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Employees</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['employee_count'] ?? 0 }}</p>
            </div>
            <div class="bg-teal-100 rounded-full p-3">
                <span class="text-2xl">👨‍💼</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Leave Requests</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_leaves'] ?? 0 }}</p>
            </div>
            <div class="bg-amber-100 rounded-full p-3">
                <span class="text-2xl">📅</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Bills This Month</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">K{{ number_format($stats['total_bills_month'] ?? 0) }}</p>
                @if(($stats['overdue_bills'] ?? 0) > 0)
                    <p class="text-xs text-red-500 mt-1">{{ $stats['overdue_bills'] }} overdue</p>
                @endif
            </div>
            <div class="bg-cyan-100 rounded-full p-3">
                <span class="text-2xl">🧾</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Pending Reviews</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_reviews'] ?? 0 }}</p>
            </div>
            <div class="bg-pink-100 rounded-full p-3">
                <span class="text-2xl">⭐</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Bookings by Status</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Confirmed</span>
                <span class="font-semibold">{{ $stats['confirmed_bookings'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Pending</span>
                <span class="font-semibold">{{ $stats['pending_bookings'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Completed</span>
                <span class="font-semibold">{{ $stats['completed_bookings'] ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">Cancelled</span>
                <span class="font-semibold">{{ $stats['cancelled_bookings'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
        <div class="space-y-3">
            <div class="flex items-center space-x-3 text-sm">
                <span class="text-green-500">✓</span>
                <span class="text-gray-600">New booking received</span>
                <span class="text-gray-400 ml-auto">2h ago</span>
            </div>
            <div class="flex items-center space-x-3 text-sm">
                <span class="text-blue-500">★</span>
                <span class="text-gray-600">New review submitted</span>
                <span class="text-gray-400 ml-auto">5h ago</span>
            </div>
            <div class="flex items-center space-x-3 text-sm">
                <span class="text-indigo-500">👤</span>
                <span class="text-gray-600">New user registered</span>
                <span class="text-gray-400 ml-auto">1d ago</span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">Recent Bookings</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-brand hover:text-indigo-700 text-sm font-medium">
                View All →
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Apartment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentBookings ?? [] as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $booking->booking_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $booking->user->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ Str::limit($booking->apartment->title, 30) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $booking->check_in->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            K{{ number_format($booking->total_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No bookings yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
