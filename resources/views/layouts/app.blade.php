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
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
        <div class="flex justify-around items-center py-2">
            @auth
                @php
                    $user = auth()->user();
                    $unreadMessages = $user->unreadMessages()->count() ?? 0;
                    $cartCount = $user->cart ? $user->cart->count() : 0;
                @endphp
                
                @if($user->role === 'customer')
                    <!-- Mobile Bottom Navigation -->
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
                    
                    <div class="relative group">
                        <button class="flex flex-col items-center px-3 py-2 text-xs text-gray-600 hover:text-blue-500 focus:outline-none">
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
                        
                        <!-- Mobile Profile Dropdown -->
                        <div class="absolute bottom-full right-0 mb-2 w-48 bg-white rounded-md shadow-lg py-1 opacity-0 invisible group-focus:opacity-100 group-focus:visible transition-all duration-300 z-50">
                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2 text-gray-400"></i> My Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-edit mr-2 text-gray-400"></i> Edit Profile
                            </a>
                            <hr class="my-1 border-gray-100">
                            <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile" class="hidden">
                                @csrf
                            </form>
                            <button type="button" onclick="confirmLogout('mobile')" 
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                <i class="fas fa-sign-out-alt mr-2 text-gray-400"></i> Logout
                            </button>
                        </div>
                    </div>
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
                    <!-- Customer Nav - Desktop -->
                    <div class="hidden md:flex items-center space-x-6">
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
        <div class="flex min-h-[calc(100vh-5rem)]">
            <!-- Left Sidebar - Orders Panel (Desktop Only) -->
            @auth
                @if(auth()->user()->role === 'customer')
                    <div class="hidden lg:block w-80 bg-white border-r border-gray-200 overflow-y-auto">
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
                                                Total: ₱{{ number_format($order->total_amount, 2) }}
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
            <main id="main-content" class="flex-1 overflow-y-auto bg-gray-50">
                @yield('content')
            </main>
            
            <!-- Right Sidebar - Messages Panel -->
            @auth
                @if(auth()->user()->role === 'customer' || auth()->user()->role === 'business_owner')
                    <div class="hidden lg:block w-80 bg-white border-l border-gray-200 overflow-y-auto">
                        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white z-10">
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
                const formId = type === 'mobile' ? 'logout-form-mobile' : 'logout-form';
                document.getElementById(formId).submit();
            }
        }
    </script>

    @stack('scripts')
    
    @auth
        @if(auth()->user()->role === 'customer' || auth()->user()->role === 'business_owner')
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
    
    <!-- Rating System -->
    <script src="{{ asset('js/ratings.js') }}"></script>
</body>
</html>