@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Welcome back, {{ auth()->user()->name }}!</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('bookings.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="text-4xl mb-4">📅</div>
            <h2 class="text-xl font-semibold mb-2">My Bookings</h2>
            <p class="text-gray-600">View and manage your Gracimor stays</p>
        </a>

        <a href="{{ route('favorites') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="text-4xl mb-4">❤️</div>
            <h2 class="text-xl font-semibold mb-2">Favorites</h2>
            <p class="text-gray-600">Your saved Hyndland apartments</p>
        </a>

        <a href="{{ route('profile.edit') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="text-4xl mb-4">👤</div>
            <h2 class="text-xl font-semibold mb-2">Profile</h2>
            <p class="text-gray-600">Manage your account details</p>
        </a>
    </div>
</div>
@endsection
