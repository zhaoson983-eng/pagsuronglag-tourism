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
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-check-circle mr-1"></i>
                    Available Now
                </span>
            </div>

            <!-- Business Info -->
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $business->name }}</h1>
                
                <!-- Contact Info -->
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-4">
                    @if($business->businessProfile && $business->businessProfile->location)
                        <div class="flex items-center justify-center lg:justify-start text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $business->businessProfile->location }}</span>
                        </div>
                    @elseif($business->address)
                        <div class="flex items-center justify-center lg:justify-start text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $business->address }}</span>
                        </div>
                    @endif
                    @if($business->contact_number)
                        <div class="flex items-center justify-center lg:justify-start text-gray-600">
                            <i class="fas fa-phone mr-2"></i>
                            <span>{{ $business->contact_number }}</span>
                        </div>
                    @endif
                </div>

                <!-- Rating Section -->
                <div class="flex items-center justify-center lg:justify-start gap-2 mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= ($business->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600">{{ number_format($business->average_rating ?? 0, 1) }} ({{ $business->total_ratings ?? 0 }} ratings)</span>
                </div>

                <!-- Description -->
                @if($business->businessProfile && $business->businessProfile->description)
                    <div class="text-gray-600 text-sm leading-relaxed">
                        <h3 class="font-semibold text-gray-900 mb-2">About This Business</h3>
                        <p>{{ $business->businessProfile->description }}</p>
                    </div>
                @elseif($business->description)
                    <div class="text-gray-600 text-sm leading-relaxed">
                        <h3 class="font-semibold text-gray-900 mb-2">About This Business</h3>
                        <p>{{ $business->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-box mr-3 text-blue-600"></i>
            Products
        </h2>
        
        @if($products && $products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                        @if($product->image)
                            <div class="aspect-w-16 aspect-h-9 mb-4">
                                <img src="{{ Storage::url($product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover rounded-lg">
                            </div>
                        @else
                            <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                                <i class="fas fa-box text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                        
                        <h3 class="font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                        @if($product->description)
                            <p class="text-gray-600 text-sm mb-3">{{ $product->description }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between mb-3">
                            <div class="text-blue-600 font-bold">
                                ₱{{ number_format($product->price, 2) }}
                            </div>
                            @if(($product->current_stock ?? 0) > 0)
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                    In Stock ({{ $product->current_stock ?? 0 }})
                                </span>
                            @else
                                <span class="text-xs px-2 py-1 bg-red-100 text-red-800 rounded-full">
                                    Out of Stock
                                </span>
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            @if(($product->current_stock ?? 0) > 0)
                                @auth
                                    <form action="{{ route('customer.cart.add') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                            <i class="fas fa-shopping-cart mr-2"></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm flex items-center justify-center">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        Add to Cart
                                    </a>
                                @endauth
                            @else
                                <button disabled class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-lg text-sm cursor-not-allowed">
                                    <i class="fas fa-times mr-2"></i>
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-box text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500 text-lg">No products available yet</p>
                <p class="text-gray-400 text-sm">Please check back later for new products</p>
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

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-95 hidden flex items-center justify-center p-4" style="z-index: 2147483647; width: 100vw; height: 100vh;">
    <div class="relative w-full h-full flex items-center justify-center">
        <button onclick="closeImageModal()" class="absolute top-16 right-8 text-white text-4xl hover:text-gray-300 bg-black bg-opacity-70 rounded-full w-16 h-16 flex items-center justify-center z-20 shadow-lg">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Gallery Image" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');

    // Hide ALL possible Messages panels
    const messagesPanels = document.querySelectorAll('#messagesPanel, .messages-panel, [class*="messages"]');
    messagesPanels.forEach(panel => {
        panel.style.display = 'none';
    });

    // Hide any other sidebars or panels
    const sidebars = document.querySelectorAll('[class*="sidebar"], [class*="panel"], .lg\\:block.w-80');
    sidebars.forEach(sidebar => {
        if (!sidebar.classList.contains('hidden')) {
            sidebar.dataset.wasVisible = 'true';
            sidebar.style.display = 'none';
        }
    });

    // Prevent body scrolling
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');

    // Show ALL Messages panels
    const messagesPanels = document.querySelectorAll('#messagesPanel, .messages-panel, [class*="messages"]');
    messagesPanels.forEach(panel => {
        panel.style.display = 'block';
    });

    // Restore any hidden sidebars/panels
    const sidebars = document.querySelectorAll('[class*="sidebar"], [class*="panel"], .lg\\:block.w-80');
    sidebars.forEach(sidebar => {
        if (sidebar.dataset.wasVisible === 'true') {
            sidebar.style.display = 'block';
            delete sidebar.dataset.wasVisible;
        }
    });

    // Restore body scroll
    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
}

// Close modal when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
        closeImageModal();
    }
});

// Business Like Toggle
function toggleBusinessLike(businessId) {
    fetch(`/businesses/${businessId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.getElementById(`businessLikeBtn-${businessId}`);
            const likeCount = document.getElementById(`businessLikeCount-${businessId}`);
            
            if (data.liked) {
                likeBtn.innerHTML = `<i class="fas fa-heart text-red-500"></i> <span class="text-sm">${data.like_count}</span>`;
            } else {
                likeBtn.innerHTML = `<i class="far fa-heart text-gray-400 hover:text-red-500"></i> <span class="text-sm">${data.like_count}</span>`;
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Show Business Comments (could be expanded to show a modal or expand section)
function showBusinessComments(businessId) {
    // This could be enhanced to show a modal or scroll to comments section
    const commentsSection = document.querySelector('.bg-white.rounded-2xl.shadow-lg.p-6.mb-6:last-child');
    if (commentsSection) {
        commentsSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Comment functionality
document.addEventListener('DOMContentLoaded', function() {
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
                    document.getElementById('commentText').value = '';
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
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
                // Update comment count
                const commentCountElement = document.querySelector('.text-sm.text-gray-600.mb-2');
                if (commentCountElement) {
                    const currentCount = parseInt(commentCountElement.textContent.match(/\d+/)[0]);
                    const newCount = currentCount - 1;
                    commentCountElement.textContent = `${newCount} comments`;
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
</script>

@endsection