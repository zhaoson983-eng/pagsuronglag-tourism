@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        {{ session('error') }}
    </div>
@endif

<div class="w-full">
    <!-- Hero Banner Section with Dynamic Background -->
    <div class="w-full h-64 relative bg-gray-200"
         id="cover-banner"
         @if($business && $business->cover_image)
             style="background-image: url('{{ Storage::url($business->cover_image) }}'); background-size: cover; background-position: center;"
         @endif>
         
        <!-- Cover Photo Upload Form -->
        <div class="absolute top-4 right-4">
            <form action="{{ route('business.updateCover') }}" method="POST" enctype="multipart/form-data" class="inline">
                @csrf
                <label class="bg-white bg-opacity-90 text-gray-700 px-4 py-2 rounded-lg hover:bg-opacity-100 transition-all duration-200 flex items-center text-sm font-medium cursor-pointer">
                    <i class="fas fa-camera mr-2"></i> Edit Cover Image
                    <input type="file" name="cover_image" accept="image/*" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="w-full max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side - Profile and Business Info -->
            <div class="lg:col-span-1">
                <!-- Profile Photo Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 relative">
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto">
                            <div class="w-full h-full border-4 border-white rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-all duration-200 shadow-lg overflow-hidden"
                                 onclick="document.getElementById('profile-photo').click()">
                                @if($business && $business->profile_avatar)
                                    <img src="{{ Storage::url($business->profile_avatar) }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-store text-gray-400 text-3xl"></i>
                                @endif
                            </div>
                            <div class="absolute bottom-0 right-0 bg-white rounded-full p-1 shadow-md">
                                <i class="fas fa-camera text-gray-600 text-sm"></i>
                            </div>
                        </div>
                        <input type="file" id="profile-photo" class="hidden" accept="image/*" onchange="uploadBusinessProfilePhoto(this)">

                        <!-- Business Name -->
                        <h1 class="text-3xl font-bold text-gray-800 mt-4 mb-3">
                            {{ $business->business_name ?? 'Business Name' }}
                        </h1>

                        <!-- Availability Status -->
                        <div class="mb-4">
                            <div class="inline-flex items-center px-3 py-1 rounded-full 
                                {{ $business && $business->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-2 h-2 rounded-full mr-2 
                                    {{ $business && $business->is_published ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                <span class="text-sm font-medium">
                                    {{ $business && $business->is_published ? 'Available Now' : 'Not Available' }}
                                </span>
                            </div>
                        </div>

                        <!-- Business Info -->
                        <div class="space-y-3 text-left">
                            @if($business && $business->address)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-map-marker-alt w-5 text-gray-400 mr-3"></i>
                                    <span class="text-sm">{{ $business->address }}</span>
                                </div>
                            @endif
                            @if($business && $business->contact_number)
                                <div class="flex items-center text-gray-600 mb-1">
                                    <i class="fas fa-phone w-5 text-gray-400 mr-3"></i>
                                    <span class="text-sm">{{ $business->contact_number }}</span>
                                </div>
                                <!-- Star Rating -->
                                <div class="flex items-center text-yellow-400 mb-2 ml-8">
                                    @php
                                        $rating = $business->average_rating ?? 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = $rating - $fullStars >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    @endphp
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @if($hasHalfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                    <span class="text-gray-600 text-xs ml-2">
                                        ({{ number_format($rating, 1) }} from {{ $business->total_ratings ?? 0 }} reviews)
                                    </span>
                                </div>
                            @endif
                            @if($business && $business->delivery_available)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-truck w-5 text-gray-400 mr-3"></i>
                                    <span class="text-sm">Delivery Available</span>
                                </div>
                            @endif
                        </div>

                        <!-- Publish/Unpublish Buttons -->
                        <div class="mt-6 flex flex-wrap gap-2 justify-center">
                            @if($business && $business->is_published)
                                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">Published</span>
                                <form action="{{ route('business.unpublish') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600 transition-colors">
                                        Unpublish
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('business.publish') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                        Publish Now
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Products</span>
                            <span class="font-semibold">{{ $productCount ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Pending Orders</span>
                            <span class="font-semibold">{{ $pendingOrdersCount ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Total Sales</span>
                            <span class="font-semibold">₱{{ number_format($totalSales ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Unread Messages</span>
                            <span class="font-semibold">{{ $unreadMessagesCount ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('business.products.create') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus-circle mr-2"></i> Add New Product
                        </a>
                        <a href="{{ route('business.products') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-boxes mr-2"></i> Manage Products
                        </a>
                        <a href="{{ route('business.orders') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-shopping-bag mr-2"></i> View Orders
                        </a>
                        <a href="{{ route('business.messages') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-envelope mr-2"></i> Messages
                            @if(($unreadMessagesCount ?? 0) > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                    {{ $unreadMessagesCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side - Dashboard Content -->
            <div class="lg:col-span-2">

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Products Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Total Products</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $productCount ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <i class="fas fa-box text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('business.products') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                            View Products <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <!-- Orders Card -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $pendingOrdersCount ?? 0 }}</h3>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <a href="{{ route('business.orders') }}" class="mt-4 inline-flex items-center text-sm font-medium text-green-600 hover:text-green-800">
                            View Orders <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                        <a href="{{ route('business.orders') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                    </div>
                    @if(isset($recentOrders) && $recentOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentOrders as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->customer->name ?? 'Unknown' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₱{{ number_format($order->total, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent orders found.</p>
                    @endif
                </div>

                <!-- Products Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Products</h3>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="openModal('uploadModal')" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                <i class="fas fa-plus-circle mr-2"></i> Upload New Product
                            </button>
                            <a href="{{ route('business.products') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                    </div>
                    @if(isset($topProducts) && $topProducts->count() > 0)
                        <div class="space-y-4">
                            @foreach($topProducts as $product)
                                <div class="flex items-center p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                                    <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-md overflow-hidden">
                                        @if(!empty($product->image))
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $product->name }}</h4>
                                        <p class="text-sm text-gray-500">₱{{ number_format($product->price, 2) }}</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs px-2 py-1 rounded-full {{ $product->stock_color ?? 'text-gray-600 bg-gray-100' }}">
                                                {{ $product->stock_status ?? 'No Stock Info' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Stock: {{ $product->current_stock ?? 0 }}/{{ $product->stock_limit ?? 0 }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $product->order_items_count }} {{ Str::plural('sale', $product->order_items_count) }}
                                        </div>
                                        <div class="flex flex-col gap-1 mt-1">
                                            <button onclick="editProductStock({{ $product->id }}, {{ $product->current_stock ?? 0 }}, {{ $product->stock_limit ?? 0 }})" 
                                                    class="text-xs text-blue-600 hover:text-blue-800">
                                                Edit Stock
                                            </button>
                                            <button onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')" 
                                                    class="text-xs text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No products found.</p>
                    @endif
                </div>

                <!-- Gallery Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Gallery</h2>
                        <button onclick="document.getElementById('galleryModal').classList.remove('hidden')" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            <i class="fas fa-plus mr-2"></i>Add Images
                        </button>
                    </div>
                    
                    @if(isset($galleries) && $galleries->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($galleries as $image)
                                <div class="relative group">
                                    <img src="{{ Storage::url($image->image_path) }}" 
                                         alt="Gallery Image" 
                                         class="w-full h-24 object-cover rounded-lg">
                                    <button type="button" onclick="deleteGalleryImage({{ $image->id }})" 
                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-images text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No gallery images yet</p>
                            <p class="text-sm text-gray-400">Upload images to showcase your business</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Upload Modal -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
            <h3 class="text-lg font-semibold text-gray-800">Upload New Product</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeModal('uploadModal')">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <form id="productForm" action="{{ route('business.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="price" id="price" step="0.01" min="0" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" required>
                        </div>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="stock_limit" class="block text-sm font-medium text-gray-700">Stock Limit</label>
                            <input type="number" name="stock_limit" id="stock_limit" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="100" required>
                            <p class="text-xs text-gray-500 mt-1">Maximum stock available</p>
                        </div>
                        <div>
                            <label for="current_stock" class="block text-sm font-medium text-gray-700">Current Stock</label>
                            <input type="number" name="current_stock" id="current_stock" min="0" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="50" required>
                            <p class="text-xs text-gray-500 mt-1">Current available stock</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md" id="image-upload-container">
                            <div class="space-y-3 text-center" id="upload-prompt">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload file</span>
                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewProductImage(this)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                            <div id="image-preview-container" class="hidden w-full">
                                <div class="relative">
                                    <img id="product-image-preview" class="mx-auto max-h-48 rounded-md" src="#" alt="Preview" />
                                    <button type="button" onclick="removeImagePreview()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 text-center">Click the X to change the image</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="p-6 border-t border-gray-200 flex-shrink-0">
            <div class="flex space-x-3">
                <button type="button" onclick="closeModal('uploadModal')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" form="productForm" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Product
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewProductImage(input) {
        const container = document.getElementById('image-upload-container');
        const uploadPrompt = document.getElementById('upload-prompt');
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('product-image-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                uploadPrompt.classList.add('hidden');
                previewContainer.classList.remove('hidden');
                container.classList.remove('border-dashed', 'border-2');
                container.classList.add('p-2');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeImagePreview() {
        const container = document.getElementById('image-upload-container');
        const uploadPrompt = document.getElementById('upload-prompt');
        const previewContainer = document.getElementById('image-preview-container');
        const fileInput = document.getElementById('image');
        
        // Reset file input
        fileInput.value = '';
        
        // Show upload prompt and hide preview
        uploadPrompt.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        container.classList.add('border-dashed', 'border-2', 'px-6', 'pt-5', 'pb-6');
        container.classList.remove('p-2');
    }
    
    // Handle drag and drop
    const dropZone = document.getElementById('image-upload-container');
    const fileInput = document.getElementById('image');
    
    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }
        
        function unhighlight() {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }
        
        dropZone.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length) {
                fileInput.files = files;
                previewProductImage(fileInput);
            }
        }
    }
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('bg-black')) {
            closeModal('uploadModal');
        }
    }

    // Allow normal form submission to controller

    function uploadBusinessProfilePhoto(input) {
        if (!input.files || !input.files[0]) {
            console.error('No file selected');
            return;
        }
        
        const file = input.files[0];
        console.log('File selected:', file.name, file.size, file.type);
        
        // Validate file type
        if (!file.type.match(/^image\/(jpeg|png|jpg|gif)$/)) {
            showToast('Please select a valid image file (JPEG, PNG, GIF)', 'error');
            return;
        }
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            showToast('File size must be less than 5MB', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('profile_avatar', file);
        
        console.log('Sending request to:', '{{ route("business.updateAvatar") }}');
        console.log('CSRF Token:', '{{ csrf_token() }}');
        
        // Show loading toast
        showToast('Uploading profile picture...', 'info');
        
        fetch('{{ route("business.updateAvatar") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Response error text:', text);
                    let errorMessage = `HTTP ${response.status}`;
                    try {
                        const errorData = JSON.parse(text);
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        // If not JSON, use the text as is
                        errorMessage = text || errorMessage;
                    }
                    throw new Error(errorMessage);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response data:', data);
            if (data.success) {
                showToast('Profile avatar updated successfully!', 'success');
                // Update the image immediately
                const profileImg = document.querySelector('img[alt="Profile"]');
                if (profileImg && data.url) {
                    profileImg.src = data.url + '?t=' + Date.now();
                }
                // Also reload after a short delay to ensure everything is updated
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(data.message || 'Failed to update profile avatar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating profile avatar: ' + error.message, 'error');
        });
    }
    
    // Delete gallery image function
    function deleteGalleryImage(imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`/business/gallery/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting image: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting image');
            });
        }
    }

    // Gallery preview function
    function previewGalleryImages(input) {
        const previewContainer = document.getElementById('galleryPreviews');
        const fileNames = document.getElementById('fileNames');
        
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            fileNames.textContent = `${input.files.length} file(s) selected`;
            
            Array.from(input.files).forEach((file, index) => {
                if (index < 10) { // Limit to 10 previews
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-20 object-cover rounded-lg">`;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            fileNames.textContent = 'No files selected';
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>

<!-- Gallery Upload Modal -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Upload Gallery Images</h3>
            <button onclick="document.getElementById('galleryModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('business.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Images</label>
                <input type="file" 
                       name="images[]" 
                       multiple 
                       accept="image/*"
                       onchange="previewGalleryImages(this)"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       required>
                <p class="text-xs text-gray-500 mt-1" id="fileNames">No files selected</p>
            </div>
            
            <div id="galleryPreviews" class="grid grid-cols-3 gap-2 mb-4"></div>
            
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="document.getElementById('galleryModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Upload Images
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Stock Edit Modal -->
<div id="stockEditModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Edit Product Stock</h3>
            <button onclick="closeStockModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="stockEditForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="edit_stock_limit" class="block text-sm font-medium text-gray-700">Stock Limit</label>
                    <input type="number" name="stock_limit" id="edit_stock_limit" min="0" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Maximum stock capacity</p>
                </div>
                <div>
                    <label for="edit_current_stock" class="block text-sm font-medium text-gray-700">Current Stock</label>
                    <input type="number" name="current_stock" id="edit_current_stock" min="0" 
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Available stock now</p>
                </div>
            </div>
            
            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="closeStockModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update Stock
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editProductStock(productId, currentStock, stockLimit) {
    document.getElementById('edit_current_stock').value = currentStock;
    document.getElementById('edit_stock_limit').value = stockLimit;
    document.getElementById('stockEditForm').action = `{{ url('/business/products') }}/${productId}/stock`;
    document.getElementById('stockEditModal').classList.remove('hidden');
}

function closeStockModal() {
    document.getElementById('stockEditModal').classList.add('hidden');
}

function deleteProduct(productId, productName) {
    if (confirm(`Are you sure you want to delete "${productName}"? This action cannot be undone.`)) {
        fetch(`{{ url('/business/products') }}/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('Product deleted successfully!', 'success');
                // Reload the page to update the product list
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Error deleting product', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting product', 'error');
        });
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    // Auto-remove notification after 3 seconds
    setTimeout(() => {
        notification.style.transition = 'opacity 0.3s ease-out';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>

@endpush
@endsection