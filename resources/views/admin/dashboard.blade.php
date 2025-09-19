@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8 -mt-20">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Admin Dashboard</h1>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" placeholder="Search..." class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> New User
            </a>
        </div>
    </div>

    <p class="text-gray-600 mb-8">Welcome, Admin. Manage users, businesses, products, and orders.</p>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 transition-transform duration-300 hover:translate-y-[-5px]">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-500 text-sm">Total Users</h2>
                    <p class="text-2xl font-bold">{{ $counts['customers'] + $counts['businessOwners'] + $counts['admins'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-green-500 text-sm"><i class="fas fa-arrow-up mr-1"></i> 100% active</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transition-transform duration-300 hover:translate-y-[-5px]">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-store text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-500 text-sm">Business Owners</h2>
                    <p class="text-2xl font-bold">{{ $counts['businessOwners'] }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-green-500 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i> 
                    {{ round(($counts['businessOwners'] / max($counts['customers'] + $counts['businessOwners'] + $counts['admins'], 1)) * 100) }}% of users
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transition-transform duration-300 hover:translate-y-[-5px]">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-purple-100 text-purple-600">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-500 text-sm">Total Products</h2>
                    <p class="text-2xl font-bold">{{ $productCount ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-green-500 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i> {{ $productCount > 0 ? 'Active' : 'No products' }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transition-transform duration-300 hover:translate-y-[-5px]">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-orange-100 text-orange-600">
                    <i class="fas fa-hotel text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-500 text-sm">Hotels</h2>
                    <p class="text-2xl font-bold">{{ $hotelCount ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-green-500 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i> {{ $hotelCount > 0 ? 'Active' : 'No hotels' }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transition-transform duration-300 hover:translate-y-[-5px]">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-teal-100 text-teal-600">
                    <i class="fas fa-umbrella-beach text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-500 text-sm">Resorts</h2>
                    <p class="text-2xl font-bold">{{ $resortCount ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-green-500 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i> {{ $resortCount > 0 ? 'Active' : 'No resorts' }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 transition-transform duration-300 hover:translate-y-[-5px]">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600">
                    <i class="fas fa-map-marked-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-500 text-sm">Tourist Spots</h2>
                    <p class="text-2xl font-bold">{{ $touristSpotCount ?? 0 }}</p>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-green-500 text-sm">
                    <i class="fas fa-arrow-up mr-1"></i> {{ $touristSpotCount > 0 ? 'Active' : 'No spots' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Analytics Section - 2nd Row -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 mb-8">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top Selling Products</h2>
            <div class="space-y-3">
                @forelse($topProducts as $item)
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">{{ Str::limit($item->product->name ?? 'Unknown', 18) }}</span>
                        <span class="text-gray-600">{{ $item->total_qty }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No sales yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Most Visited Shops -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Most Visited Shops</h2>
            <div class="space-y-3">
                @forelse($mostVisitedShops ?? [] as $shop)
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">{{ Str::limit($shop->business_name, 18) }}</span>
                        <span class="text-gray-600">{{ $shop->visitor_count }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No visits recorded.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Hotels -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top Rated Hotels</h2>
            <div class="space-y-3">
                @forelse($topHotels ?? [] as $hotel)
                    <div class="flex justify-between text-sm">
                        <div>
                            <p class="font-medium">{{ Str::limit($hotel->business_name, 15) }}</p>
                            <p class="text-xs text-yellow-500">⭐ {{ number_format($hotel->average_rating, 1) }}</p>
                        </div>
                        <span class="text-gray-600">{{ $hotel->total_ratings }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No hotels yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Resorts -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top Rated Resorts</h2>
            <div class="space-y-3">
                @forelse($topResorts ?? [] as $resort)
                    <div class="flex justify-between text-sm">
                        <div>
                            <p class="font-medium">{{ Str::limit($resort->business_name, 15) }}</p>
                            <p class="text-xs text-yellow-500">⭐ {{ number_format($resort->average_rating, 1) }}</p>
                        </div>
                        <span class="text-gray-600">{{ $resort->total_ratings }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No resorts yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Tourist Spots -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top Tourist Spots</h2>
            <div class="space-y-3">
                @forelse($topTouristSpots ?? [] as $spot)
                    <div class="flex justify-between text-sm">
                        <div>
                            <p class="font-medium">{{ Str::limit($spot->name, 15) }}</p>
                            <p class="text-xs text-yellow-500">⭐ {{ number_format($spot->average_rating, 1) }}</p>
                        </div>
                        <span class="text-gray-600">{{ $spot->total_ratings }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No tourist spots yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Products per Business</h2>
            <div class="h-64">
                <canvas id="businessStatusChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Top Locations</h2>
            <div class="h-64">
                <canvas id="locationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- User Demographics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Gender Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Gender Distribution</h2>
            <div class="h-48">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        <!-- Age Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Age Groups</h2>
            <div class="h-48">
                <canvas id="ageChart"></canvas>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">User Distribution</h2>
            <div class="h-48">
                <canvas id="userDistributionChart"></canvas>
            </div>
        </div>
    </div>


    <!-- Recent Activity & Businesses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Recent Users</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentUsers as $user)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->profile && $user->profile->profile_picture)
                                            <img class="h-10 w-10 rounded-full" src="{{ Storage::url($user->profile->profile_picture) }}" alt="{{ $user->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->role === 'admin') bg-purple-100 text-purple-800
                                    @elseif($user->role === 'business_owner') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Registered Businesses</h2>
            <div class="space-y-4">
                @foreach($businesses as $business)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-md bg-blue-100 flex items-center justify-center mr-3 text-blue-600">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ $business->name }}</p>
                            <p class="text-xs text-gray-500">By {{ $business->owner->name }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ $business->products_count }} products</p>
                        <p class="text-xs text-green-500">{{ $business->is_published ? 'Published' : 'Unpublished' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- ✅ Fixed Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Pass PHP data to JS
    const customerCount = {{ $counts['customers'] }};
    const businessOwnerCount = {{ $counts['businessOwners'] }};
    const adminCount = {{ $counts['admins'] }};

    const businessLabels = [
        @foreach($businesses as $business)
            "{{ addslashes($business->name) }}",
        @endforeach
    ];

    const productCounts = [
        @foreach($businesses as $business)
            {{ $business->products_count }},
        @endforeach
    ];

    // ===== 1. User Distribution Chart =====
    const userCtx = document.getElementById('userDistributionChart').getContext('2d');
    new Chart(userCtx, {
        type: 'doughnut',
        data: {
            labels: ['Customers', 'Business Owners', 'Admins'],
            datasets: [{
                data: [customerCount, businessOwnerCount, adminCount],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 } } }
            }
        }
    });

    // ===== 2. Products per Business =====
    const businessCtx = document.getElementById('businessStatusChart').getContext('2d');
    new Chart(businessCtx, {
        type: 'bar',
        data: {
            labels: businessLabels,
            datasets: [{
                label: 'Products',
                data: productCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // ===== 3. Gender Distribution Chart =====
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($genderDistribution as $gender)
                    "{{ ucfirst($gender->sex) }}",
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($genderDistribution as $gender)
                        {{ $gender->count }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)', // Male
                    'rgba(255, 99, 132, 0.8)', // Female
                    'rgba(75, 192, 192, 0.8)'  // Other
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 } } }
            }
        }
    });

    // ===== 4. Age Distribution Chart =====
    const ageCtx = document.getElementById('ageChart').getContext('2d');
    new Chart(ageCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($ageDistribution as $age)
                    "{{ $age->age_group }}",
                @endforeach
            ],
            datasets: [{
                label: 'Users',
                data: [
                    @foreach($ageDistribution as $age)
                        {{ $age->count }},
                    @endforeach
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.8)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } } },
                x: { ticks: { font: { size: 10 } } }
            }
        }
    });

    // ===== 5. Location Distribution Chart =====
    const locationCtx = document.getElementById('locationChart').getContext('2d');
    new Chart(locationCtx, {
        type: 'pie',
        data: {
            labels: [
                @foreach($locationDistribution as $location)
                    "{{ $location->location }}",
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($locationDistribution as $location)
                        {{ $location->count }},
                    @endforeach
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 10 } } }
            }
        }
    });
</script>
@endsection