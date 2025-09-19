@extends('layouts.app')

@section('title', 'My Shop Dashboard')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
                    <p class="mt-2 text-blue-100">Manage your shop and track your business performance</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('business.products.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-blue-700 bg-white hover:bg-blue-50">
                        <i class="fas fa-plus mr-2"></i> Add New Product
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Products Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                        <i class="fas fa-box text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $productCount ?? 0 }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('business.products') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Manage Products <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-50 text-yellow-600">
                        <i class="fas fa-shopping-bag text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Pending Orders</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingOrdersCount ?? 0 }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('business.orders') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View Orders <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sales Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-50 text-green-600">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Total Sales</h3>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalSales ?? 0, 2) }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('business.orders') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View Sales <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-gray-500 text-sm font-medium">Unread Messages</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $unreadMessagesCount ?? 0 }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('business.messages') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        View Messages <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mb-8">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Links</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Add Product -->
            <a href="{{ route('business.products.create') }}" class="bg-white p-6 rounded-lg shadow border border-gray-100 hover:shadow-md transition-shadow flex items-start">
                <div class="bg-blue-50 p-3 rounded-lg mr-4">
                    <i class="fas fa-plus text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Add New Product</h3>
                    <p class="mt-1 text-sm text-gray-500">List a new item in your shop</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 ml-auto self-center"></i>
            </a>

            <!-- Manage Orders -->
            <a href="{{ route('business.orders') }}" class="bg-white p-6 rounded-lg shadow border border-gray-100 hover:shadow-md transition-shadow flex items-start">
                <div class="bg-green-50 p-3 rounded-lg mr-4">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Manage Orders</h3>
                    <p class="mt-1 text-sm text-gray-500">Process and track customer orders</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 ml-auto self-center"></i>
            </a>

            <!-- Business Profile -->
            <a href="{{ route('business.profile.edit') }}" class="bg-white p-6 rounded-lg shadow border border-gray-100 hover:shadow-md transition-shadow flex items-start">
                <div class="bg-purple-50 p-3 rounded-lg mr-4">
                    <i class="fas fa-store text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">Business Profile</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your shop information</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 ml-auto self-center"></i>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Recent Activity</h2>
            <p class="mt-1 text-sm text-gray-500">Latest updates from your shop</p>
        </div>
        <div class="divide-y divide-gray-200">
            @if(isset($recentActivities) && $recentActivities->count() > 0)
                @foreach($recentActivities as $activity)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-{{ $activity->color }}-100 flex items-center justify-center">
                                <i class="fas fa-{{ $activity->icon }} text-{{ $activity->color }}-600"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $activity->title }}</p>
                                    <p class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">{{ $activity->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No recent activity to show</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection