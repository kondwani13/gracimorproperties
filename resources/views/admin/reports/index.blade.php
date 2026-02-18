@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<h1 class="text-2xl font-semibold mb-6">Reports</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="{{ route('admin.reports.revenue') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition group">
        <div class="flex items-center gap-4">
            <div class="bg-green-100 rounded-full p-3 group-hover:bg-green-200 transition">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Revenue</h3>
                <p class="text-sm text-gray-500">Payment trends, revenue by apartment</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.bookings') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition group">
        <div class="flex items-center gap-4">
            <div class="bg-blue-100 rounded-full p-3 group-hover:bg-blue-200 transition">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Bookings</h3>
                <p class="text-sm text-gray-500">Booking stats, top apartments</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.users') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition group">
        <div class="flex items-center gap-4">
            <div class="bg-purple-100 rounded-full p-3 group-hover:bg-purple-200 transition">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Users</h3>
                <p class="text-sm text-gray-500">User growth, top users by bookings</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.occupancy') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition group">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-100 rounded-full p-3 group-hover:bg-indigo-200 transition">
                <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Occupancy</h3>
                <p class="text-sm text-gray-500">Occupancy rates, monthly trends</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.maintenance') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition group">
        <div class="flex items-center gap-4">
            <div class="bg-orange-100 rounded-full p-3 group-hover:bg-orange-200 transition">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Maintenance Costs</h3>
                <p class="text-sm text-gray-500">By category, apartment, monthly trend</p>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.reports.bills') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition group">
        <div class="flex items-center gap-4">
            <div class="bg-cyan-100 rounded-full p-3 group-hover:bg-cyan-200 transition">
                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Bill Deposits</h3>
                <p class="text-sm text-gray-500">By type, collection rates, monthly trend</p>
            </div>
        </div>
    </a>
</div>
@endsection

