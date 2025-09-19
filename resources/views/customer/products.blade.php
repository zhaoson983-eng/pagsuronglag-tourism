@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Category Cards Section -->
    <div class="px-4 py-6 bg-white">
        <div class="grid grid-cols-4 gap-4 md:max-w-2xl md:mx-auto">
            <!-- Products & Shops - Active -->
            <a href="{{ route('customer.products') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-100 border-2 border-blue-300 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-600 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-shopping-basket text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-blue-700 text-center leading-tight">Products & Shops</span>
            </a>

            <!-- Hotels -->
            <a href="{{ route('customer.hotels') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-hotel text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 text-center leading-tight">Hotels</span>
            </a>

            <!-- Resorts -->
            <a href="{{ route('customer.resorts') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-umbrella-beach text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 text-center leading-tight">Resorts</span>
            </a>

            <!-- Attractions -->
            <a href="{{ route('customer.attractions') }}" class="flex flex-col items-center p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition-colors">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-blue-500 rounded-full flex items-center justify-center mb-2">
                    <i class="fas fa-map-marked-alt text-white text-lg md:text-xl"></i>
                </div>
                <span class="text-xs md:text-sm font-medium text-gray-700 text-center leading-tight">Attractions</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mt-6 max-w-2xl mx-auto">
            <form action="{{ route('customer.search') }}" method="GET" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for products, businesses, or tourist spots..." class="flex-1 px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-800">
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
        </div>
    </div>

    <!-- Feed Section -->
    <div class="px-4 py-2 max-w-2xl mx-auto">
        <div id="feed-container" class="space-y-4"></div>
        
        <div id="loading" class="text-center py-8 hidden">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <p class="mt-2 text-gray-500">Loading more...</p>
        </div>
        
        <div id="no-content" class="text-center py-12 hidden">
            <i class="fas fa-shopping-basket text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-500 text-lg">No products available at the moment</p>
            <p class="text-gray-400 text-sm">Check back later for new products and shops!</p>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .feed-item { animation: fadeInUp 0.5s ease-out; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@push('scripts')
<script>
let currentPage = 1, loading = false, hasMore = true;

document.addEventListener('DOMContentLoaded', function() {
    loadFeedData();
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 1000) {
            if (!loading && hasMore) { currentPage++; loadFeedData(); }
        }
    });
});

function loadFeedData() {
    if (loading) return;
    loading = true;
    document.getElementById('loading').classList.remove('hidden');
    
    fetch(`/customer/products/feed?page=${currentPage}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('feed-container');
        if (currentPage === 1) container.innerHTML = '';
        
        if (data.items && data.items.length > 0) {
            data.items.forEach(item => container.appendChild(createFeedItem(item)));
            hasMore = data.hasMore;
        } else if (currentPage === 1) {
            document.getElementById('no-content').classList.remove('hidden');
        }
        loading = false;
        document.getElementById('loading').classList.add('hidden');
    })
    .catch(error => {
        console.error('Error:', error);
        loading = false;
        document.getElementById('loading').classList.add('hidden');
    });
}

function createFeedItem(item) {
    const feedItem = document.createElement('div');
    feedItem.className = 'feed-item bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden';
    
    if (item.type === 'business') {
        feedItem.innerHTML = `
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden">
                            ${item.profile_avatar ? `<img src="${item.profile_avatar}" alt="${item.title}" class="w-full h-full object-cover">` : 
                              `<div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center"><i class="fas fa-store text-white text-sm"></i></div>`}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">${item.title}</h3>
                            <p class="text-sm text-gray-500">${item.location}</p>
                            <p class="text-xs text-gray-400">${item.product_count} products</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">${item.status}</span>
                </div>
            </div>
            ${item.image ? `<img src="${item.image}" alt="${item.title}" class="w-full h-64 object-cover">` : 
              `<div class="w-full h-64 bg-gray-200 flex items-center justify-center"><i class="fas fa-store text-gray-400 text-4xl"></i></div>`}
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-1 transition-colors" onclick="toggleLike('business', ${item.id})">
                            <i class="${item.user_liked ? 'fas text-red-500' : 'far text-gray-400'} fa-heart"></i>
                            <span class="text-sm">${item.like_count || 0}</span>
                        </button>
                        <span class="flex items-center space-x-1"><i class="fas fa-star text-yellow-400"></i><span class="text-sm">${(item.rating || 0).toFixed(1)} (${item.rating_count || 0})</span></span>
                        <span class="flex items-center space-x-1"><i class="fas fa-comment text-gray-600"></i><span class="text-sm">${item.comment_count || 0}</span></span>
                    </div>
                    <a href="${item.url}" class="text-blue-500 hover:text-blue-600 font-medium text-sm">View Shop </a>
                </div>
            </div>
        `;
    } else {
        feedItem.innerHTML = `
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center">
                            <i class="fas fa-box text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">${item.title}</h3>
                            <p class="text-sm text-gray-500">${item.location}</p>
                            <p class="text-lg font-bold text-orange-600">${parseFloat(item.price || 0).toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 ${item.stock_color} text-xs font-medium rounded-full">${item.stock_status}</span>
                </div>
            </div>
            ${item.image ? `<img src="${item.image}" alt="${item.title}" class="w-full h-48 object-cover">` : 
              `<div class="w-full h-48 bg-gray-200 flex items-center justify-center"><i class="fas fa-box text-gray-400 text-3xl"></i></div>`}
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-1 transition-colors" onclick="toggleLike('product', ${item.id})">
                            <i class="${item.user_liked ? 'fas text-red-500' : 'far text-gray-400'} fa-heart"></i>
                            <span class="text-sm">${item.like_count || 0}</span>
                        </button>
                        <span class="flex items-center space-x-1"><i class="fas fa-star text-yellow-400"></i><span class="text-sm">${(item.rating || 0).toFixed(1)} (${item.rating_count || 0})</span></span>
                        <span class="flex items-center space-x-1"><i class="fas fa-comment text-gray-600"></i><span class="text-sm">${item.comment_count || 0}</span></span>
                    </div>
                    <a href="${item.url}" class="text-blue-500 hover:text-blue-600 font-medium text-sm">View Product </a>
                </div>
                ${item.current_stock > 0 ? 
                  `<form action="{{ route('customer.cart.add') }}" method="POST" class="mt-3">@csrf<input type="hidden" name="product_id" value="${item.id}"><input type="hidden" name="quantity" value="1"><button type="submit" class="w-full bg-blue-500 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-600 transition-colors flex items-center justify-center"><i class="fas fa-shopping-cart mr-2"></i> Add to Cart</button></form>` :
                  `<button disabled class="w-full bg-gray-400 text-white text-sm px-4 py-2 rounded-md cursor-not-allowed flex items-center justify-center mt-3"><i class="fas fa-times mr-2"></i> Out of Stock</button>`}
            </div>
        `;
    }
    return feedItem;
}

function toggleLike(type, id) {
    fetch(`/${type === 'business' ? 'businesses' : 'products'}/${id}/like`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.querySelector(`[onclick*="toggleLike('${type}', ${id})"]`);
            if (button) {
                const icon = button.querySelector('i');
                const count = button.querySelector('span');
                if (data.liked) {
                    icon.classList.remove('far', 'text-gray-400');
                    icon.classList.add('fas', 'text-red-500');
                } else {
                    icon.classList.remove('fas', 'text-red-500');
                    icon.classList.add('far', 'text-gray-400');
                }
                count.textContent = data.like_count || data.likes_count || 0;
            }
        }
    });
}
</script>
@endpush
