@extends('layouts.app')

@section('title', $business->name . ' - Pagsurong Lagonoy')

@section('content')
<!-- Success Notification Toast -->
@if(session('success'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up" id="success-message">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>

    <script>
        // Auto-remove success message after 3 seconds
        setTimeout(() => {
            const msg = document.getElementById('success-message');
            if (msg) {
                msg.style.transition = 'opacity 0.3s ease-out';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            }
        }, 3000);
    </script>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        {{ session('error') }}
    </div>
@endif

<!-- Hero Banner Section -->
<div class="w-full h-80 relative bg-cover bg-center overflow-hidden"
     style="background-image: url('{!! ($business->businessProfile && $business->businessProfile->cover_image) ? Storage::url($business->businessProfile->cover_image) : asset('images/placeholder-cover.jpg') !!}')">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
</div>

<!-- Main Content -->
<div class="px-6 -mt-20 relative z-10 pb-6">
    <!-- Business Profile Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6">
            <!-- Business Avatar -->
            <div class="flex-shrink-0 text-center lg:text-left">
                <div class="w-32 h-32 mx-auto lg:mx-0 rounded-full bg-gray-100 flex items-center justify-center mb-4 relative">
                    @if($business->businessProfile && $business->businessProfile->profile_avatar)
                        <img src="{{ Storage::url($business->businessProfile->profile_avatar) }}" alt="{{ $business->name }}" class="w-full h-full rounded-full object-cover">
                    @elseif($business->businessProfile && $business->businessProfile->logo)
                        <img src="{{ Storage::url($business->businessProfile->logo) }}" alt="{{ $business->name }}" class="w-full h-full rounded-full object-cover">
                    @else
                        <i class="fas fa-store text-4xl text-blue-500"></i>
                    @endif
                    <div class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                </div>
            </div>
            
            <!-- Business Info -->
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $business->name }}</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-4">
                    <i class="fas fa-check-circle mr-1"></i>
                    Available Now
                </span>
                
                <!-- Contact Info -->
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-4">
                    @if($business->businessProfile && $business->businessProfile->location)
                        <div class="flex items-center justify-center lg:justify-start text-gray-600">
                            <i class="fas fa-map-marker-alt w-5 mr-2"></i>
                            <span class="text-sm">{{ $business->businessProfile->location }}</span>
                        </div>
                    @elseif($business->address)
                        <div class="flex items-center justify-center lg:justify-start text-gray-600">
                            <i class="fas fa-map-marker-alt w-5 mr-2"></i>
                            <span class="text-sm">{{ $business->address }}</span>
                        </div>
                    @endif
                    @if($business->contact_number)
                        <div class="flex items-center justify-center lg:justify-start text-gray-600">
                            <i class="fas fa-phone w-5 mr-2"></i>
                            <span class="text-sm">{{ $business->contact_number }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Rating Section -->
                <div class="flex items-center justify-center lg:justify-start gap-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= ($business->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-sm font-medium text-gray-700">{{ number_format($business->average_rating ?? 0, 1) }}</span>
                    </div>
                    <span class="text-sm text-gray-500">({{ $business->total_ratings ?? 0 }} ratings)</span>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        @if($business->businessProfile && $business->businessProfile->description)
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">About This Business</h3>
                <p class="text-blue-700 text-sm">{{ $business->businessProfile->description }}</p>
            </div>
        @elseif($business->description)
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">About This Business</h3>
                <p class="text-blue-700 text-sm">{{ $business->description }}</p>
            </div>
        @endif
    </div>
    <!-- Products Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">Products</h2>
        </div>

        @if($products && $products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                                <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-shadow duration-200 bg-white">
                                    <!-- Product Image -->
                                    <div class="h-32 sm:h-40 bg-gray-100 flex items-center justify-center relative">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                                                 class="w-full h-full object-cover {{ ($product->current_stock ?? 0) <= 0 ? 'opacity-50' : '' }}">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-3xl {{ ($product->current_stock ?? 0) <= 0 ? 'opacity-50' : '' }}"></i>
                                        @endif
                                        
                                        <!-- Out of Stock Overlay -->
                                        @if(($product->current_stock ?? 0) <= 0)
                                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                                <div class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                                    OUT OF STOCK
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-3">
                                        <h3 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-1">{{ $product->name }}</h3>
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-orange-500 font-bold text-base">₱{{ number_format($product->price, 2) }}</p>
                                            @if(($product->current_stock ?? 0) > 0)
                                                <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                                    {{ $product->current_stock }} left
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Like, Rating, Comment Icons -->
                                        <div class="flex items-center justify-between mb-3 text-gray-500 text-sm">
                                            @php
                                                $userProductLike = auth()->check() ? $product->likes()->where('user_id', auth()->id())->first() : null;
                                                $userProductRating = auth()->check() ? $product->ratings()->where('user_id', auth()->id())->first() : null;
                                            @endphp
                                            
                                            <!-- Like -->
                                            @auth
                                                <button onclick="toggleProductLike({{ $product->id }})" 
                                                        id="productLikeBtn-{{ $product->id }}"
                                                        class="flex items-center space-x-1 {{ $userProductLike ? 'text-red-500' : 'text-gray-500 hover:text-red-500' }} transition-colors">
                                                    <svg class="w-4 h-4" fill="{{ $userProductLike ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                    <span id="productLikeCount-{{ $product->id }}">{{ $product->likes()->count() }}</span>
                                                </button>
                                            @else
                                                <div class="flex items-center space-x-1 text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                    <span>{{ $product->likes()->count() }}</span>
                                                </div>
                                            @endauth

                                            <!-- Rating -->
                                            @auth
                                                <button onclick="openProductRatingModal({{ $product->id }}, {{ $userProductRating ? $userProductRating->rating : 0 }})" 
                                                        class="flex items-center space-x-1 {{ $userProductRating ? 'text-yellow-500' : 'text-gray-500 hover:text-yellow-500' }} transition-colors">
                                                    <svg class="w-4 h-4" fill="{{ $userProductRating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                    </svg>
                                                    <span>{{ number_format($product->average_rating, 1) }}</span>
                                                </button>
                                            @else
                                                <div class="flex items-center space-x-1 text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                    </svg>
                                                    <span>{{ number_format($product->average_rating, 1) }}</span>
                                                </div>
                                            @endauth

                                            <!-- Comments -->
                                            @auth
                                                <button onclick="openProductCommentModal({{ $product->id }})" class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    <span>{{ $product->comments()->count() }}</span>
                                                </button>
                                            @else
                                                <div class="flex items-center space-x-1 text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                    </svg>
                                                    <span>{{ $product->comments()->count() }}</span>
                                                </div>
                                            @endauth
                                        </div>

                                        <!-- Add to Cart Form -->
                                        @if(($product->current_stock ?? 0) > 0)
                                            @auth
                                                <form id="add-to-cart-form-{{ $product->id }}" action="{{ route('customer.cart.add') }}" method="POST" class="mt-2">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit"
                                                            class="w-full bg-blue-500 text-white text-xs px-2 py-1 rounded-md hover:bg-blue-600 transition-colors flex items-center justify-center">
                                                        <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}" 
                                                   class="w-full bg-blue-500 text-white text-xs px-2 py-1 rounded-md hover:bg-blue-600 transition-colors flex items-center justify-center">
                                                    <i class="fas fa-shopping-cart mr-1"></i> Add to Cart
                                                </a>
                                            @endauth
                                        @else
                                            <button disabled 
                                                    class="w-full bg-gray-400 text-white text-xs px-2 py-1 rounded-md cursor-not-allowed flex items-center justify-center">
                                                <i class="fas fa-times mr-1"></i> Out of Stock
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">No products available yet</h3>
                            <p class="text-gray-500">This business hasn't uploaded any products yet.</p>
                        </div>
                    @endif
                </div>

    <!-- Gallery Section -->
    @if($business->businessProfile && $business->businessProfile->galleries && $business->businessProfile->galleries->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Gallery</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($business->businessProfile->galleries as $gallery)
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                         onclick="openImageModal('{{ Storage::url($gallery->image_path) }}')">
                        <img src="{{ Storage::url($gallery->image_path) }}" 
                             alt="Gallery Image" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Reviews & Comments Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Reviews & Comments</h2>
                    
                    <!-- Rating Summary -->
                    <div class="flex items-center mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="text-center mr-6">
                            <div class="text-3xl font-bold text-gray-900">{{ number_format($business->average_rating ?? 0, 1) }}</div>
                            <div class="flex items-center justify-center mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= ($business->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                            </div>
                            <div class="text-sm text-gray-600">{{ $business->total_ratings ?? 0 }} ratings</div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm text-gray-600 mb-2">{{ $business->comments()->whereHas('user')->count() ?? 0 }} comments</div>
                        </div>
                    </div>

                    <!-- Add Comment Form -->
                    @auth
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Add a Comment</h3>
                            <form id="commentForm">
                                @csrf
                                <input type="hidden" name="business_id" value="{{ $business->id }}">
                                <div class="mb-4">
                                    <textarea name="comment" id="commentText" rows="3" 
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                              placeholder="Share your experience about this business..."></textarea>
                                </div>
                                <button type="submit" 
                                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-comment mr-2"></i>
                                    Post Comment
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg text-center">
                            <p class="text-gray-600">
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Login</a> 
                                to add a comment
                            </p>
                        </div>
                    @endauth

                    <!-- Comments List -->
                    @if($business->comments && $business->comments()->whereHas('user')->count() > 0)
                        <div class="space-y-4" id="commentsList">
                            @foreach($business->comments->take(5) as $comment)
                                @if($comment->user)
                                    <div class="border-b border-gray-200 pb-4" id="comment-{{ $comment->id }}">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                @if($comment->user && $comment->user->profile && $comment->user->profile->profile_picture)
                                                    <img src="{{ asset('storage/' . $comment->user->profile->profile_picture) }}" 
                                                         alt="{{ $comment->user->name }}" 
                                                         class="w-full h-full rounded-full object-cover">
                                                @elseif($comment->user && $comment->user->profile_avatar)
                                                    <img src="{{ asset('storage/' . $comment->user->profile_avatar) }}" 
                                                         alt="{{ $comment->user->name }}" 
                                                         class="w-full h-full rounded-full object-cover">
                                                @elseif($comment->user && $comment->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" 
                                                         alt="{{ $comment->user->name }}" 
                                                         class="w-full h-full rounded-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center">
                                                        <span class="text-white text-xs font-bold">
                                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                                        <span class="text-sm text-gray-500">{{ $comment->created_at->format('M d, Y') }}</span>
                                                    </div>
                                                @auth
                                                    @if($comment->user_id === auth()->id())
                                                        <button onclick="deleteComment({{ $comment->id }})" 
                                                                class="text-red-500 hover:text-red-700 text-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endauth
                                            </div>
                                            <p class="text-gray-700">{{ $comment->comment }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500" id="noCommentsMessage">
                            <i class="fas fa-comments text-4xl mb-2"></i>
                            <p>No comments yet. Be the first to share your experience!</p>
                        </div>
                    @endif
                </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submit comment
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const businessId = formData.get('business_id');
            
            fetch(`/businesses/${businessId}/comment`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear the form
                    document.getElementById('commentText').value = '';
                    // Reload to show new comment
                    location.reload();
                } else {
                    alert(data.error || 'Error submitting comment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting comment');
            });
        });
    }
});

// Delete comment function
window.deleteComment = function(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the comment from the DOM
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
                
                // Update comment count on business-show page
                const commentCountElement = document.querySelector('.text-sm.text-gray-600');
                if (commentCountElement && commentCountElement.textContent.includes('comments')) {
                    const currentCount = parseInt(commentCountElement.textContent.match(/\d+/)[0]);
                    const newCount = currentCount - 1;
                    commentCountElement.textContent = `${newCount} comments`;
                    
                    // Show "no comments" message if no comments left
                    if (newCount === 0) {
                        const commentsList = document.getElementById('commentsList');
                        const noCommentsMessage = document.getElementById('noCommentsMessage');
                        if (commentsList) commentsList.style.display = 'none';
                        if (noCommentsMessage) noCommentsMessage.style.display = 'block';
                    }
                }
                
                // Comment deleted successfully - the count will be updated when user returns to products page
            } else {
                alert(data.error || 'Error deleting comment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting comment');
        });
    }
};

// Comment functionality is handled above
</script>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain">
    </div>
</div>

<!-- Product Rating Modal -->
<div id="productRatingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Rate This Product</h3>
                <button onclick="closeProductRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="productRatingForm">
                @csrf
                <input type="hidden" id="ratingProductId" name="product_id">
                
                <!-- Star Rating -->
                <div class="flex justify-center space-x-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                class="product-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors"
                                data-rating="{{ $i }}">
                            <i class="fas fa-star"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="productRatingValue" required>
                
                <!-- Comment -->
                <div class="mb-4">
                    <textarea name="comment" 
                              placeholder="Share your experience with this product..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="4"></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeProductRatingModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Comment Modal -->
<div id="productCommentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Product Comments</h3>
                <button onclick="closeProductCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto max-h-96 p-6">
            <!-- Comments will be loaded here -->
            <div id="productCommentsList">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
                    <p class="text-gray-500 mt-2">Loading comments...</p>
                </div>
            </div>
        </div>
        
        <div class="border-t p-6">
            <!-- Add Comment Form -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Add Your Comment</h4>
                <form id="productCommentForm">
                    @csrf
                    <input type="hidden" id="commentProductId" name="product_id">
                    
                    <div class="mb-4">
                        <textarea name="comment" id="productCommentText" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your thoughts about this product..."
                                  required></textarea>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Product Like Toggle
function toggleProductLike(productId) {
    fetch(`/products/${productId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.getElementById(`productLikeBtn-${productId}`);
            const likeCount = document.getElementById(`productLikeCount-${productId}`);
            
            if (data.liked) {
                likeBtn.classList.remove('text-gray-500', 'hover:text-red-500');
                likeBtn.classList.add('text-red-500');
                likeBtn.querySelector('svg').setAttribute('fill', 'currentColor');
            } else {
                likeBtn.classList.remove('text-red-500');
                likeBtn.classList.add('text-gray-500', 'hover:text-red-500');
                likeBtn.querySelector('svg').setAttribute('fill', 'none');
            }
            likeCount.textContent = data.like_count;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Product Rating Modal
function openProductRatingModal(productId, currentRating = 0) {
    document.getElementById('productRatingModal').classList.remove('hidden');
    document.getElementById('ratingProductId').value = productId;
    document.getElementById('productRatingValue').value = currentRating;
    
    // Set existing rating stars
    const stars = document.querySelectorAll('.product-star');
    stars.forEach((star, index) => {
        if (index < currentRating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

function closeProductRatingModal() {
    document.getElementById('productRatingModal').classList.add('hidden');
}

// Product Comment Modal
function openProductCommentModal(productId) {
    document.getElementById('productCommentModal').classList.remove('hidden');
    document.getElementById('commentProductId').value = productId;
    document.getElementById('productCommentForm').reset();
    loadProductComments(productId);
}

function closeProductCommentModal() {
    document.getElementById('productCommentModal').classList.add('hidden');
}

function loadProductComments(productId) {
    fetch(`/products/${productId}/comments`)
        .then(response => response.json())
        .then(data => {
            const commentsList = document.getElementById('productCommentsList');
            if (data.comments && data.comments.length > 0) {
                commentsList.innerHTML = data.comments.map(comment => `
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg" id="product-comment-${comment.id}">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0 mr-3">
                                    ${comment.user && comment.user.profile_picture ? 
                                        `<img src="/storage/${comment.user.profile_picture}" alt="${comment.user.name}" class="w-full h-full rounded-full object-cover">` :
                                        `<div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            ${comment.user.name.charAt(0).toUpperCase()}
                                        </div>`
                                    }
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">${comment.user.name}</p>
                                    <p class="text-xs text-gray-500">${new Date(comment.created_at).toLocaleDateString()}</p>
                                </div>
                            </div>
                            ${comment.user.id == {{ auth()->id() ?? 'null' }} ? 
                                `<button onclick="deleteProductCommentInModal(${comment.id}, ${productId})" class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>` : 
                                ''
                            }
                        </div>
                        <p class="text-gray-700">${comment.comment}</p>
                    </div>
                `).join('');
            } else {
                commentsList.innerHTML = '<p class="text-gray-500 text-center py-4">No comments yet. Be the first to comment!</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('productCommentsList').innerHTML = '<p class="text-red-500 text-center py-4">Error loading comments</p>';
        });
}

// Delete product comment function
window.deleteProductCommentInModal = function(commentId, productId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        fetch(`/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the comment from the DOM
                const commentElement = document.getElementById(`product-comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
                
                // Check if no comments left
                const commentsList = document.getElementById('productCommentsList');
                if (commentsList && commentsList.children.length === 0) {
                    commentsList.innerHTML = '<p class="text-gray-500 text-center py-4">No comments yet. Be the first to comment!</p>';
                }
            } else {
                alert(data.error || 'Error deleting comment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting comment');
        });
    }
};

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Product Star Rating
    const productStars = document.querySelectorAll('.product-star');
    productStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            document.getElementById('productRatingValue').value = rating;
            
            productStars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.remove('text-gray-300');
                    s.classList.add('text-yellow-400');
                } else {
                    s.classList.remove('text-yellow-400');
                    s.classList.add('text-gray-300');
                }
            });
        });
    });

    // Product Rating Form
    const productRatingForm = document.getElementById('productRatingForm');
    if (productRatingForm) {
        productRatingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productId = document.getElementById('ratingProductId').value;
            
            fetch(`/products/${productId}/rate`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeProductRatingModal();
                    location.reload();
                } else {
                    alert(data.error || 'Error submitting rating');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting rating');
            });
        });
    }

    // Product Comment Form
    const productCommentForm = document.getElementById('productCommentForm');
    if (productCommentForm) {
        productCommentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productId = document.getElementById('commentProductId').value;
            
            fetch(`/products/${productId}/comment`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('productCommentText').value = '';
                    loadProductComments(productId);
                    location.reload(); // Reload to update comment counts
                } else {
                    alert('Error submitting comment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error submitting comment');
            });
        });
    }
});
</script>

<script>
/**
 * Add product to cart by submitting the corresponding form
 * Allows for future enhancements like loading spinners, analytics, etc.
 */
function addToCart(productId) {
    const form = document.getElementById(`add-to-cart-form-${productId}`);
    
    if (!form) {
        console.error(`Form for product ${productId} not found`);
        return;
    }

    // Optional: Add loading state
    const button = form.querySelector('button');
    const originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Adding...';

    // Submit form
    form.submit();
}
</script>
</div>

@endsection