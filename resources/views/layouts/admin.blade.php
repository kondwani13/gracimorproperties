<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name') }} - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-4">
                <h2 class="text-2xl font-bold">🏠 Gracimor Admin</h2>
            </div>

            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>📊 Dashboard</span>
                </a>

                <a href="{{ route('admin.apartments.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.apartments.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>🏘️ Apartments</span>
                </a>

                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.bookings.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>📅 Bookings</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>👥 Users</span>
                </a>

                <div class="px-6 py-3 text-gray-400 text-xs uppercase tracking-wider mt-4">Property Management</div>

                <a href="{{ route('admin.tenants.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.tenants.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>🏷️ Tenants</span>
                </a>

                <a href="{{ route('admin.rent-payments.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.rent-payments.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>💵 Rent Collection</span>
                </a>

                <a href="{{ route('admin.complaints.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.complaints.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>📝 Complaints</span>
                </a>

                <a href="{{ route('admin.consent-forms.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.consent-forms.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>📋 Consent Forms</span>
                </a>

                <div class="px-6 py-3 text-gray-400 text-xs uppercase tracking-wider mt-4">Operations</div>

                <a href="{{ route('admin.employees.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.employees.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>👨‍💼 Employees</span>
                </a>

                <a href="{{ route('admin.employee-leaves.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.employee-leaves.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>📅 Leave Requests</span>
                </a>

                <a href="{{ route('admin.maintenance.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.maintenance.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>🔧 Maintenance</span>
                </a>

                <a href="{{ route('admin.bills.index') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.bills.*') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>🧾 Bills</span>
                </a>

                <div class="px-6 py-3 text-gray-400 text-xs uppercase tracking-wider mt-4">Reports</div>

                <a href="{{ route('admin.reports.revenue') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reports.revenue') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>💰 Revenue</span>
                </a>

                <a href="{{ route('admin.reports.bookings') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reports.bookings') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>📈 Bookings</span>
                </a>

                <a href="{{ route('admin.reports.users') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reports.users') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>📊 Users</span>
                </a>

                <a href="{{ route('admin.reports.occupancy') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reports.occupancy') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>🏠 Occupancy</span>
                </a>

                <a href="{{ route('admin.reports.maintenance') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reports.maintenance') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>🔧 Maintenance Costs</span>
                </a>

                <a href="{{ route('admin.reports.bills') }}"
                   class="flex items-center px-6 py-3 {{ request()->routeIs('admin.reports.bills') ? 'bg-gray-800 border-r-4 border-brand' : 'hover:bg-gray-800' }}">
                    <span>🧾 Bill Deposits</span>
                </a>

                <div class="border-t border-gray-700 mt-6 pt-6">
                    <a href="{{ route('apartments.index') }}"
                       class="flex items-center px-6 py-3 hover:bg-gray-800">
                        <span>← Back to Site</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4 flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>

                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded mb-6">
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded mb-6">
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

