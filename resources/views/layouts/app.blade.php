<?php $currentYear = date("Y"); ?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Pagsurong Lagonoy')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'playfair': ['"Playfair Display"', 'serif'],
                        'roboto': ['"Roboto"', 'sans-serif']
                    },
                    colors: {
                        'primary': '#1d4ed8', // blue-700
                        'secondary': '#f97316' // orange-500
                    }
                }
            },
            plugins: []
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @stack('styles')
</head>
<body class="font-roboto text-gray-800 leading-relaxed bg-gray-50 antialiased">

    <!-- Skip to Content (Accessibility) -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 bg-blue-600 text-white px-4 py-2 rounded-md z-50">
        Skip to main content
    </a>

    <!-- Header -->
    <header style="background-color: #012844ff;" class="text-white shadow-md fixed w-full top-0 left-0 right-0 z-50">
        <!-- Mobile Header -->
        <div class="md:hidden px-4 py-3 flex items-center justify-between">
            <div class="flex items-center">
                <a href="javascript:history.back()" class="flex items-center hover:opacity-80 transition-opacity">
                    <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-8 h-auto">
                </a>
            </div>
            
            @auth
                @php
                    $user = auth()->user();
                    $unreadMessages = $user->unreadMessages()->count() ?? 0;
                    $cartCount = $user->cart ? $user->cart->count() : 0;
                @endphp
                
                @if($user->role === 'customer')
                    <!-- Mobile Customer Navigation -->
                    <div class="flex items-center space-x-4 text-sm">
                        <a href="{{ route('customer.dashboard') }}" class="hover:text-blue-200 transition-colors {{ request()->routeIs('customer.dashboard') ? 'text-blue-200' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('customer.orders') }}" class="hover:text-blue-200 transition-colors {{ request()->routeIs('customer.orders') ? 'text-blue-200' : '' }}">
                            My Orders
                        </a>
                        <a href="{{ route('customer.messages') }}" class="hover:text-blue-200 transition-colors relative {{ request()->routeIs('customer.messages') ? 'text-blue-200' : '' }}">
                            Messages
                            @if($unreadMessages)
                                <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1 min-w-[16px] text-center text-[10px]">
                                    {{ $unreadMessages }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('customer.cart') }}" class="hover:text-blue-200 transition-colors relative {{ request()->routeIs('customer.cart') ? 'text-blue-200' : '' }}">
                            <i class="fas fa-shopping-cart"></i> My Cart
                            @if($cartCount)
                                <span class="absolute -top-1 -right-2 bg-orange-500 text-white text-xs rounded-full px-1 min-w-[16px] text-center text-[10px]">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                        
                        <!-- Profile Avatar -->
                        <div class="relative group">
                            <button class="flex items-center focus:outline-none">
                                @if($user->profile && $user->profile->profile_picture)
                                    <img src="{{ Storage::url($user->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-8 h-8 rounded-full object-cover border border-blue-400">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </button>
                            
                            <!-- Mobile Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> My Profile
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-edit mr-2"></i> Edit Profile
                                </a>
                                <hr class="my-1 border-gray-100">
                                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile" class="hidden">
                                    @csrf
                                </form>
                                <button type="button" onclick="confirmLogout('mobile')" 
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
        
        <!-- Desktop Header -->
        <div class="hidden md:flex py-3 px-4 md:px-10 flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <a href="javascript:history.back()" class="flex items-center hover:opacity-80 transition-opacity">
                    <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-12 h-auto mr-3 drop-shadow-sm">
                    <div class="font-playfair text-2xl font-bold">Pagsurong Lagonoy</div>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex items-center space-x-6">
            @auth
                @php
                    $user = auth()->user();
                    $unreadMessages = $user->unreadMessages()->count() ?? 0;
                    $cartCount = $user->cart ? $user->cart->count() : 0;
                @endphp

                @if($user->role === 'business_owner')
                    @php
                        // Prefer BusinessProfile status for approval; fall back to publish flag
                        $bizProfile = $user->businessProfile;
                        $isApproved = $bizProfile && ($bizProfile->status === 'approved');
                        $isPublished = $bizProfile && ($bizProfile->is_published ?? false);
                    @endphp
                    @if($isApproved || $isPublished)
                        <!-- Business Owner Nav - When Business is Approved/Published -->
                        @if($bizProfile && $bizProfile->business_type === 'resort')
                            <a href="{{ route('business.my-resort') }}" 
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.my-resort') ? 'font-semibold' : '' }}">
                                <i class="fas fa-umbrella-beach mr-1"></i> My Resort
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.my-resort') ? 'w-full' : '' }}"></span>
                            </a>
                        @elseif($bizProfile && $bizProfile->business_type === 'hotel')
                            <a href="{{ route('business.my-hotel') }}" 
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.my-hotel') ? 'font-semibold' : '' }}">
                                <i class="fas fa-hotel mr-1"></i> My Hotel
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.my-hotel') ? 'w-full' : '' }}"></span>
                            </a>
                        @else
                            <a href="{{ route('business.my-shop') }}" 
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.my-shop') ? 'font-semibold' : '' }}">
                                <i class="fas fa-store mr-1"></i> My Shop
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.my-shop') ? 'w-full' : '' }}"></span>
                            </a>
                        @endif
                        <a href="{{ route('business.messages') }}" 
                           class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.messages') ? 'font-semibold' : '' }}">
                            <i class="fas fa-envelope mr-1"></i> Messages
                            @if($unreadMessages)
                                <span class="absolute -top-2 -right-4 bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">
                                    {{ $unreadMessages }}
                                </span>
                            @endif
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.messages') ? 'w-full' : '' }}"></span>
                        </a>
                        <a href="{{ route('business.orders') }}" 
                           class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.orders') ? 'font-semibold' : '' }}">
                            <i class="fas fa-shopping-bag mr-1"></i> Orders
                            @if($pendingOrdersCount ?? 0 > 0)
                                <span class="absolute -top-2 -right-4 bg-yellow-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">
                                    {{ $pendingOrdersCount }}
                                </span>
                            @endif
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.orders') ? 'w-full' : '' }}"></span>
                        </a>
                        
                    @else
                        <!-- Business Owner Nav - When Business Needs Setup/Approval -->
                        @php
                            $setupLabel = 'Shop';
                            if ($bizProfile && $bizProfile->business_type === 'hotel') {
                                $setupLabel = 'Hotel';
                            } elseif ($bizProfile && $bizProfile->business_type === 'resort') {
                                $setupLabel = 'Resort';
                            } elseif (session('business_type') === 'hotel') {
                                $setupLabel = 'Hotel';
                            } elseif (session('business_type') === 'resort') {
                                $setupLabel = 'Resort';
                            }
                        @endphp
                        @if($setupLabel === 'Hotel')
                            <a href="{{ route('business.my-hotel') }}" 
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.my-hotel') ? 'font-semibold' : '' }}">
                                <i class="fas fa-hotel mr-1"></i> Set Up Your {{ $setupLabel }}
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.my-hotel') ? 'w-full' : '' }}"></span>
                            </a>
                        @elseif($setupLabel === 'Resort')
                            <a href="{{ route('business.my-resort') }}" 
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.my-resort') ? 'font-semibold' : '' }}">
                                <i class="fas fa-umbrella-beach mr-1"></i> Set Up Your {{ $setupLabel }}
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.my-resort') ? 'w-full' : '' }}"></span>
                            </a>
                        @else
                            <a href="{{ route('business.setup') }}" 
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('business.setup') ? 'font-semibold' : '' }}">
                                <i class="fas fa-store mr-1"></i> Set Up Your {{ $setupLabel }}
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('business.setup') ? 'w-full' : '' }}"></span>
                            </a>
                        @endif
                        @if($bizProfile && $bizProfile->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                Pending Approval
                            </span>
                        @endif
                    @endif
                    @elseif($user->role === 'admin')
                        <!-- Admin Nav -->
                        <a href="{{ route('dashboard') }}" 
                           class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('home') ? 'font-semibold' : '' }}">
                            Home
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('home') ? 'w-full' : '' }}"></span>
                        </a>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('admin.dashboard') ? 'font-semibold' : '' }}">
                            Dashboard
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('admin.dashboard') ? 'w-full' : '' }}"></span>
                        </a>
                        <a href="{{ route('admin.business-approvals.index') }}" 
                           class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('admin.business-approvals.*') ? 'font-semibold' : '' }}">
                            Approval
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('admin.business-approvals.*') ? 'w-full' : '' }}"></span>
                        </a>
                        <a href="{{ route('admin.users') }}" 
                           class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('admin.users') ? 'font-semibold' : '' }}">
                            Users
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('admin.users') ? 'w-full' : '' }}"></span>
                        </a>
                        <a href="{{ route('admin.uploadpromotion') }}" 
                           class="text-white hover:text-blue-100 transition-colors duration-200 {{ request()->routeIs('admin.uploadpromotion') ? 'font-semibold' : '' }}">
                            Promotions
                        </a>

                @else
                    <!-- Customer Nav -->
                    <a href="{{ route('customer.dashboard') }}" 
                       class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.dashboard') ? 'font-semibold' : '' }}">
                        Home
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.dashboard') ? 'w-full' : '' }}"></span>
                    </a>
                    <a href="{{ route('customer.orders') }}" 
                       class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.orders') ? 'font-semibold' : '' }}">
                        My Orders
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.orders') ? 'w-full' : '' }}"></span>
                    </a>
                    <a href="{{ route('customer.messages') }}" 
                       class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.messages') ? 'font-semibold' : '' }}">
                        Messages
                        @if($unreadMessages)
                            <span class="absolute -top-2 -right-4 bg-red-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">
                                {{ $unreadMessages }}
                            </span>
                        @endif
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.messages') ? 'w-full' : '' }}"></span>
                    </a>
                    <a href="{{ route('customer.cart') }}" 
                       class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.cart') ? 'font-semibold' : '' }}">
                        <i class="fas fa-shopping-cart mr-1"></i> My Cart
                        @if($cartCount)
                            <span class="absolute -top-2 -right-4 bg-orange-500 text-white text-xs rounded-full px-2 py-1 min-w-[20px] text-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.cart') ? 'w-full' : '' }}"></span>
                    </a>
                @endif

                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="flex items-center focus:outline-none focus:ring-2 focus:ring-blue-300 rounded-full">
                        @if($user->profile && $user->profile->profile_picture)
                            <img src="{{ Storage::url($user->profile->profile_picture) }}" 
                                 alt="Profile" 
                                 class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> My Profile
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-edit mr-2"></i> Edit Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-question-circle mr-2"></i> Help & Support
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-history mr-2"></i> History
                        </a>
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                            @csrf
                        </form>
                        <button type="button" onclick="confirmLogout()" 
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </div>
                </div>
            @else
                <!-- Guest Navigation -->
                <a href="{{ route('login') }}" class="text-white hover:text-blue-100 transition-all duration-200 relative group">
                    Login
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300"></span>
                </a>
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-all duration-200">Register</a>
            @endauth
        </nav>
        </div>
    </header>

    <!-- Main content wrapper with padding for fixed header -->
    <div class="pt-20 md:pt-16">
        <!-- Main Content -->
        <main id="main-content">
            @yield('content')
        </main>
    </div>

<!-- Divider -->
<div class="border-t border-gray-200 my-10"></div>

<!-- Footer -->
<footer style="background-color: #012844ff;" class="text-white py-10">
    <div class="max-w-6xl mx-auto px-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-playfair font-bold mb-4">Pagsurong Lagonoy</h3>
                <p class="text-gray-300">
                    Showcasing the best of Lagonoy's local products, accommodations, and tourist destinations.
                </p>
            </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors duration-200">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Contact</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Connect With Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    <p class="mt-4 text-gray-300">
                        Email: info@pagsuronglagonoy.com
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; {{ $currentYear }} Pagsurong Lagonoy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function confirmLogout(type = 'desktop') {
            if (confirm('Are you sure you want to logout?')) {
                const formId = type === 'mobile' ? 'logout-form-mobile' : 'logout-form';
                document.getElementById(formId).submit();
            }
        }
    </script>

    @stack('scripts')
    
    <!-- Rating System -->
    <script src="{{ asset('js/ratings.js') }}"></script>
</body>
</html>