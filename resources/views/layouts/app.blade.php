<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gracimor Properties') }} - @yield('title', 'Premium Apartments in Lusaka')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo -->
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="text-2xl font-bold text-brand">
                               <img src="{{ asset('img/logo/logo-dark.png') }}" style="width: 150px;" />
                            </a>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-8">
                        <a href="{{ route('apartments.index') }}" class="text-gray-700 hover:text-brand px-3 py-2 text-sm font-medium">
                            Browse Apartments
                        </a>

                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-brand px-3 py-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('bookings.index') }}" class="text-gray-700 hover:text-brand px-3 py-2 text-sm font-medium">
                                My Bookings
                            </a>
                            <a href="{{ route('favorites') }}" class="text-gray-700 hover:text-brand px-3 py-2 text-sm font-medium">
                                Favorites
                            </a>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-700 hover:text-brand">
                                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="h-8 w-8 rounded-full mr-2">
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Admin Dashboard
                                        </a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-brand px-3 py-2 text-sm font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-brand text-white hover:bg-brand-dark px-4 py-2 rounded-md text-sm font-medium">
                                Sign Up
                            </a>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center sm:hidden">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition class="sm:hidden absolute left-0 right-0 bg-white shadow-lg z-50">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('apartments.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Browse Apartments
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Dashboard
                        </a>
                        <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            My Bookings
                        </a>
                        <a href="{{ route('favorites') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Favorites
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ config('app.name') }}</h3>
                        <p class="text-gray-600 text-sm">Premium rental apartments at Hyndland Estate, Meanwood Ibex, Lusaka.</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">Contact Us</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="tel:+260973580350" class="hover:text-brand">0973 580 350</a></li>
                            <li><a href="mailto:info@gracimorproperties.com" class="hover:text-brand">info@gracimorproperties.com</a></li>
                            <li><a href="{{ url('/') }}#about" class="hover:text-brand">About Us</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">Location</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li>Gracimor Hyndland Estate</li>
                            <li>Sub 4 of Sub B3 Farm 382A</li>
                            <li>Meanwood Ibex, Lusaka, Zambia</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-4">Connect</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li><a href="https://www.facebook.com/FASHIONJRNEWS" class="hover:text-brand">Facebook</a></li>
                            <li><a href="https://api.whatsapp.com/send?phone=+260973580350" class="hover:text-brand">WhatsApp</a></li>
                            <li><a href="https://www.instagram.com/gracimorproperties/" class="hover:text-brand">Instagram</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-200 mt-8 pt-8 text-center text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>

