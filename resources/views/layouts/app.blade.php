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
        <!-- Mobile Top Header -->
        <div class="md:hidden px-4 py-3 flex items-center justify-between">
            <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-8 h-auto">

            <!-- Mobile Category Navigation -->
            <div class="flex items-center space-x-4 text-xs">
                @auth
                    @php
                        $user = auth()->user();
                    @endphp
                    @if($user->role === 'customer' && $user->hasCompletedProfile())
                        <a href="{{ route('customer.products') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('customer.products') ? 'font-semibold' : '' }}">
                            Products
                        </a>
                        <a href="{{ route('customer.hotels') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('customer.hotels') ? 'font-semibold' : '' }}">
                            Hotels
                        </a>
                        <a href="{{ route('customer.resorts') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('customer.resorts') ? 'font-semibold' : '' }}">
                            Resorts
                        </a>
                        <a href="{{ route('customer.attractions') }}" class="text-white hover:text-blue-200 transition-colors {{ request()->routeIs('customer.attractions') ? 'font-semibold' : '' }}">
                            Attractions
                        </a>
                    @elseif($user->role === 'customer' && !$user->hasCompletedProfile())
                        <span class="text-white text-xs opacity-75">Complete your profile to access all features</span>
                    @endif
                @endauth
            </div>
        </div>
        
        <!-- Mobile Bottom Navigation -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div class="flex justify-around items-center py-2">
            @auth
                @php
                    $user = auth()->user();
                    $unreadMessages = $user->unreadMessages()->count() ?? 0;
                    $cartCount = $user->cart ? $user->cart->count() : 0;
                @endphp
                
                @if($user->role === 'customer')
                    @if($user->hasCompletedProfile())
                        <!-- Mobile Bottom Navigation - Full customer features -->
                        <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 transition-colors {{ request()->routeIs('customer.dashboard') ? 'text-blue-500' : '' }}">
                            <i class="fas fa-home text-lg mb-1"></i>
                            <span>Home</span>
                        </a>

                        <a href="{{ route('customer.orders') }}" class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 transition-colors relative {{ request()->routeIs('customer.orders') ? 'text-blue-500' : '' }}">
                            <i class="fas fa-shopping-bag text-lg mb-1"></i>
                            <span>Orders</span>
                        </a>

                        <a href="{{ route('customer.messages') }}" class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 transition-colors relative {{ request()->routeIs('customer.messages') ? 'text-blue-500' : '' }}">
                            <div class="relative">
                                <i class="fas fa-envelope text-lg mb-1"></i>
                                @if($unreadMessages)
                                    <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                                        {{ $unreadMessages }}
                                    </span>
                                @endif
                            </div>
                            <span>Messages</span>
                        </a>

                        <a href="{{ route('customer.cart') }}" class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 transition-colors relative {{ request()->routeIs('customer.cart') ? 'text-blue-500' : '' }}">
                            <div class="relative">
                                <i class="fas fa-shopping-cart text-lg mb-1"></i>
                                @if($cartCount)
                                    <span class="absolute -top-1 -right-2 bg-orange-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </div>
                            <span>Cart</span>
                        </a>

                        <button onclick="toggleMobileProfileSidebar()" class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 focus:outline-none">
                            @if($user->profile && $user->profile->profile_picture)
                                <img src="{{ Storage::url($user->profile->profile_picture) }}"
                                     alt="Profile"
                                     class="w-6 h-6 rounded-full object-cover border border-blue-400 mb-1">
                            @else
                                <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs mb-1">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span>Profile</span>
                        </button>
                    @else
                        <!-- Mobile Bottom Navigation - Simple during profile setup -->
                        <a href="{{ route('profile.setup') }}" class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 transition-colors {{ request()->routeIs('profile.setup') ? 'text-blue-500' : '' }}">
                            <i class="fas fa-user-edit text-lg mb-1"></i>
                            <span>Setup</span>
                        </a>

                        <button onclick="toggleMobileProfileSidebar()" class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 focus:outline-none">
                            @if($user->profile && $user->profile->profile_picture)
                                <img src="{{ Storage::url($user->profile->profile_picture) }}"
                                     alt="Profile"
                                     class="w-6 h-6 rounded-full object-cover border border-blue-400 mb-1">
                            @else
                                <div class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs mb-1">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <span>Profile</span>
                        </button>
                    @endif
                @endif
            @endauth
        </div>
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
                    <!-- Customer Nav - Desktop -->
                    <div class="hidden md:flex items-center space-x-6">
                        @if($user->hasCompletedProfile())
                            <!-- Full customer navigation - after profile completion -->
                            <a href="{{ route('customer.dashboard') }}"
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.dashboard') ? 'font-semibold' : '' }}">
                                <i class="fas fa-home mr-1"></i> Home
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.dashboard') ? 'w-full' : '' }}"></span>
                            </a>
                            <a href="{{ route('customer.products') }}"
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.products') ? 'font-semibold' : '' }}">
                                <i class="fas fa-shopping-basket mr-1"></i> Products & Shops
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.products') ? 'w-full' : '' }}"></span>
                            </a>
                            <a href="{{ route('customer.hotels') }}"
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.hotels') ? 'font-semibold' : '' }}">
                                <i class="fas fa-hotel mr-1"></i> Hotels
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.hotels') ? 'w-full' : '' }}"></span>
                            </a>
                            <a href="{{ route('customer.resorts') }}"
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.resorts') ? 'font-semibold' : '' }}">
                                <i class="fas fa-umbrella-beach mr-1"></i> Resorts
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.resorts') ? 'w-full' : '' }}"></span>
                            </a>
                            <a href="{{ route('customer.attractions') }}"
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.attractions') ? 'font-semibold' : '' }}">
                                <i class="fas fa-map-marked-alt mr-1"></i> Attractions
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.attractions') ? 'w-full' : '' }}"></span>
                            </a>
                            <a href="{{ route('customer.cart') }}" class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('customer.cart') ? 'font-semibold' : '' }}">
                                <i class="fas fa-shopping-cart"></i> My Cart
                                @if($cartCount > 0)
                                    <span class="absolute -top-1 -right-2 bg-orange-500 text-white text-xs rounded-full px-1 min-w-[16px] text-center text-[10px]">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('customer.cart') ? 'w-full' : '' }}"></span>
                            </a>
                        @else
                            <!-- Simple navigation - during profile setup -->
                            <a href="{{ route('profile.setup') }}"
                               class="text-white hover:text-blue-100 transition-all duration-200 relative group {{ request()->routeIs('profile.setup') ? 'font-semibold' : '' }}">
                                <i class="fas fa-user-edit mr-1"></i> Complete Profile
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-400 group-hover:w-full transition-all duration-300 {{ request()->routeIs('profile.setup') ? 'w-full' : '' }}"></span>
                            </a>
                        @endif
                    </div>
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

    <!-- Main content wrapper with fixed navigation and three-column layout -->
    <div class="pt-20 md:pt-16 pb-16 md:pb-0 min-h-screen">
        <div class="flex min-h-[calc(100vh-5rem)] max-h-[calc(100vh-5rem)]">
            <!-- Left Sidebar - Orders Panel (Desktop Only) -->
            @auth
                @if(auth()->user()->role === 'customer' && auth()->user()->hasCompletedProfile())
                    <div class="hidden lg:block w-80 bg-white border-r border-gray-200 overflow-y-auto flex-shrink-0">
                        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-shopping-bag mr-2 text-blue-600"></i>
                                    My Orders
                                </h3>
                                <a href="{{ route('customer.orders') }}" class="text-xs text-blue-600 hover:underline">
                                    View All
                                </a>
                            </div>
                        </div>
                        <div class="p-4">
                            @php
                                $orders = auth()->user()->orders()->latest()->take(5)->get();
                            @endphp
                            
                            @if($orders->count() > 0)
                                <div class="space-y-3">
                                    @foreach($orders as $order)
                                        <div class="border rounded-lg p-3 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ route('customer.orders.show', $order) }}'">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Order #{{ $order->id }}</p>
                                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                                </div>
                                                <span class="px-2 py-1 text-xs rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                Total: ₱{{ number_format($order->total_amount ?? $order->total ?? $order->orderItems->sum(function($item) { return $item->quantity * $item->price; }), 2) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-3">No orders yet</p>
                                    <a href="{{ route('customer.products') }}" class="inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-shopping-basket mr-2"></i>
                                        Start Shopping
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endauth
            
            <!-- Main Content - Feed -->
            <main id="main-content" class="flex-1 overflow-y-auto bg-gray-50 pt-16 md:pt-0 pb-16 md:pb-0 min-w-0">
                @yield('content')
            </main>
            
            <!-- Right Sidebar - Messages Panel -->
            @auth
                @if((auth()->user()->role === 'customer' && auth()->user()->hasCompletedProfile()) || (auth()->user()->role === 'business_owner' && auth()->user()->businessProfile && !request()->routeIs(['business.my-hotel', 'business.my-resort'])))
                    <div id="messagesPanel" class="hidden lg:block w-80 bg-white border-l border-gray-200 overflow-y-auto flex-shrink-0 relative z-10">
                        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-20">
                            <div class="flex items-center justify-between">
                                <h3 class="font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                    Messages
                                    @php
                                        $unreadCount = auth()->user()->unreadMessages()->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="ml-2 bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $unreadCount }}</span>
                                    @endif
                                </h3>
                                <a href="{{ route('customer.messages') }}" class="text-xs text-blue-600 hover:underline">
                                    View All
                                </a>
                            </div>
                        </div>
                        <div class="p-0">
                            @php
                                $user = auth()->user();
                                $threads = $user->threads()->take(10);
                            @endphp
                            
                            @if($threads->count() > 0)
                                <div class="divide-y divide-gray-200">
                                    @foreach($threads as $otherUser)
                                        @php
                                            $lastMessage = $otherUser->last_message ?? null;
                                            $isUnread = $lastMessage && $lastMessage->receiver_id == $user->id && !$lastMessage->read_at;
                                        @endphp
                                        
                                        <a href="{{ route('messages.thread', $otherUser) }}" class="block hover:bg-gray-50 transition-colors">
                                            <div class="px-4 py-3">
                                                <div class="flex items-center space-x-3">
                                                    <!-- Profile Picture -->
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden">
                                                        @if($otherUser->profile && $otherUser->profile->profile_picture)
                                                            <img src="{{ Storage::url($otherUser->profile->profile_picture) }}"
                                                                alt="{{ $otherUser->name }}"
                                                                class="h-full w-full object-cover">
                                                        @else
                                                            <div class="h-full w-full bg-blue-500 flex items-center justify-center">
                                                                <span class="text-white font-medium text-sm">
                                                                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Message Preview -->
                                                    <div class="flex-1 min-w-0 {{ $isUnread ? 'font-medium' : '' }}">
                                                        <div class="flex items-center justify-between">
                                                            <p class="text-sm text-gray-900 truncate">
                                                                {{ $otherUser->name }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}
                                                            </p>
                                                        </div>
                                                        <p class="text-xs text-gray-500 truncate">
                                                            @if($lastMessage)
                                                                {{ $lastMessage->sender_id == $user->id ? 'You: ' : '' }}
                                                                {{ \Illuminate\Support\Str::limit(strip_tags($lastMessage->content), 30) }}
                                                            @else
                                                                No messages yet
                                                            @endif
                                                        </p>
                                                        
                                                        @if($otherUser->businessProfile)
                                                            <p class="text-xs text-blue-600 font-medium mt-1">
                                                                {{ $otherUser->businessProfile->business_name }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    
                                                    @if($isUnread)
                                                        <span class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full"></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="p-4 text-center">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-envelope text-gray-400 text-xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 mb-3">No messages yet</p>
                                    <a href="{{ route('customer.products') }}" class="text-xs text-blue-600 hover:underline">
                                        Browse products to get started
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

<!-- Divider -->
<div class="border-t border-gray-200 my-10 hidden lg:block"></div>

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
                let formId;
                if (type === 'mobile') {
                    formId = 'logout-form-mobile';
                } else if (type === 'mobile-sidebar') {
                    formId = 'logout-form-mobile-sidebar';
                } else {
                    formId = 'logout-form';
                }
                document.getElementById(formId).submit();
            }
        }
    </script>

    @stack('scripts')
    
    @auth
        @if((auth()->user()->role === 'customer' && auth()->user()->hasCompletedProfile()) || (auth()->user()->role === 'business_owner' && auth()->user()->businessProfile && !request()->routeIs(['business.my-hotel', 'business.my-resort'])))
            <style>
                /* Smooth transitions for the messages panel */
                #messagesPanel {
                    box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
                }
                
                /* Adjust main content when messages panel is open */
                @media (min-width: 768px) {
                    #messagesPanel.translate-x-0 + main {
                        margin-right: 20rem;
                        transition: margin-right 0.3s ease-in-out;
                    }
                }
                
                /* Message list styles */
                .message-item {
                    transition: background-color 0.2s ease;
                }
                
                .message-item:hover {
                    background-color: #f9fafb;
                }
                
                /* Custom scrollbar for messages */
                .messages-scroll {
                    scrollbar-width: thin;
                    scrollbar-color: #cbd5e0 #f7fafc;
                }
                
                .messages-scroll::-webkit-scrollbar {
                    width: 6px;
                }
                
                .messages-scroll::-webkit-scrollbar-track {
                    background: #f7fafc;
                }
                
                .messages-scroll::-webkit-scrollbar-thumb {
                    background-color: #cbd5e0;
                    border-radius: 3px;
                }
            </style>
        @endif
    @endauth
    
    <!-- Mobile Profile Sidebar -->
    @auth
        @if((auth()->user()->role === 'customer' && auth()->user()->hasCompletedProfile()) || (auth()->user()->role === 'business_owner' && auth()->user()->businessProfile && !request()->routeIs(['business.my-hotel', 'business.my-resort'])))
            <!-- Overlay -->
            <div id="mobileProfileOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="closeMobileProfileSidebar()"></div>
            
            <!-- Sidebar -->
            <div id="mobileProfileSidebar" class="fixed top-0 right-0 h-full w-80 bg-white shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden">
                <div class="flex flex-col h-full">
                    <!-- Header -->
                    <div class="bg-blue-600 text-white p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                @if(auth()->user()->profile && auth()->user()->profile->profile_picture)
                                    <img src="{{ Storage::url(auth()->user()->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-white">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-white text-lg font-semibold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-lg">{{ auth()->user()->name }}</h3>
                                    <p class="text-blue-100 text-sm">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <button onclick="closeMobileProfileSidebar()" class="text-white hover:text-blue-200 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Menu Items -->
                    <div class="flex-1 py-4">
                        @php
                            $user = auth()->user();
                        @endphp

                        @if($user->role === 'customer')
                            <a href="{{ route('profile.show') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-user mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">My Profile</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-edit mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">Edit Profile</span>
                            </a>
                            <a href="{{ route('customer.orders') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-shopping-bag mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">My Orders</span>
                            </a>
                            <a href="{{ route('customer.messages') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-envelope mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">Messages</span>
                            </a>
                        @else
                            <!-- Business Owner Menu Items -->
                            <a href="{{ route('profile.show') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-user mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">My Profile</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-edit mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">Edit Profile</span>
                            </a>

                            @if($user->businessProfile && $user->businessProfile->business_type === 'resort')
                                <a href="{{ route('business.my-resort') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-umbrella-beach mr-4 text-blue-600 w-5"></i>
                                    <span class="font-medium">My Resort</span>
                                </a>
                            @elseif($user->businessProfile && $user->businessProfile->business_type === 'hotel')
                                <a href="{{ route('business.my-hotel') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-hotel mr-4 text-blue-600 w-5"></i>
                                    <span class="font-medium">My Hotel</span>
                                </a>
                            @else
                                <a href="{{ route('business.my-shop') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-store mr-4 text-blue-600 w-5"></i>
                                    <span class="font-medium">My Shop</span>
                                </a>
                            @endif

                            <a href="{{ route('business.orders') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-shopping-bag mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">Orders</span>
                            </a>
                            <a href="{{ route('messages.index') }}" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-envelope mr-4 text-blue-600 w-5"></i>
                                <span class="font-medium">Messages</span>
                            </a>
                        @endif

                        <div class="border-t border-gray-200 my-2"></div>
                        <a href="#" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-cog mr-4 text-blue-600 w-5"></i>
                            <span class="font-medium">Settings</span>
                        </a>
                        <a href="#" class="flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-question-circle mr-4 text-blue-600 w-5"></i>
                            <span class="font-medium">Help & Support</span>
                        </a>
                    </div>
                    
                    <!-- Logout Button -->
                    <div class="border-t border-gray-200 p-4">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile-sidebar" class="hidden">
                            @csrf
                        </form>
                        <button type="button" onclick="confirmLogout('mobile-sidebar')" 
                                class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt mr-4 w-5"></i>
                            <span class="font-medium">Logout</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endauth
    
    <!-- Mobile Profile Sidebar JavaScript -->
    <script>
        function toggleMobileProfileSidebar() {
            const sidebar = document.getElementById('mobileProfileSidebar');
            const overlay = document.getElementById('mobileProfileOverlay');
            
            if (sidebar && overlay) {
                const isOpen = !sidebar.classList.contains('translate-x-full');
                
                if (isOpen) {
                    closeMobileProfileSidebar();
                } else {
                    openMobileProfileSidebar();
                }
            }
        }
        
        function openMobileProfileSidebar() {
            const sidebar = document.getElementById('mobileProfileSidebar');
            const overlay = document.getElementById('mobileProfileOverlay');
            
            if (sidebar && overlay) {
                overlay.classList.remove('hidden');
                sidebar.classList.remove('translate-x-full');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        }
        
        function closeMobileProfileSidebar() {
            const sidebar = document.getElementById('mobileProfileSidebar');
            const overlay = document.getElementById('mobileProfileOverlay');
            
            if (sidebar && overlay) {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = ''; // Restore scrolling
            }
        }
        
        // Close sidebar when clicking on links (except logout)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('#mobileProfileSidebar a[href]:not([href="#"])');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileProfileSidebar();
                });
            });
        });
    </script>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-8 h-auto mr-3">
                        <div class="font-playfair text-xl font-bold">Pagsurong Lagonoy</div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">
                        Your gateway to authentic Camarines Sur tourism experiences. Connect with local businesses and discover the beauty of Pagsurong Lagonoy.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://facebook.com" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="https://instagram.com" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="https://twitter.com" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold text-white mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                                Contact
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms') }}" class="text-gray-300 hover:text-white transition-colors text-sm">
                                Terms & Conditions
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="font-semibold text-white mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                                Help Center
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                                Privacy Policy
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                                FAQ
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-300 hover:text-white transition-colors text-sm">
                                Report Issue
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-400 text-sm">
                    © {{ date('Y') }} Pagsurong Lagonoy Tourism Platform. All rights reserved.
                </div>
                <div class="text-gray-400 text-sm mt-4 md:mt-0">
                    Made with ❤️ for the people of Camarines Sur
                </div>
            </div>
        </div>
    </footer>

    <!-- Rating System -->
    <script src="{{ asset('js/ratings.js') }}"></script>
</body>
</html>