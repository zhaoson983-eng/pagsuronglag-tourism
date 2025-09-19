@extends('layouts.app')

@section('title', 'Hotels & Resorts - Pagsurong Lagonoy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Hotels & Resorts</h1>
        <p class="text-gray-600">Discover comfortable accommodations in Lagonoy</p>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <div class="max-w-md mx-auto">
            <form action="{{ route('customer.hotels') }}" method="GET" class="flex">
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search hotels and resorts..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    @if($hotels->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($hotels as $hotel)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Header with Profile Avatar and Name -->
                    <div class="flex items-center p-4 pb-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                            @if($hotel->businessProfile && $hotel->businessProfile->profile_avatar)
                                <img src="{{ asset('storage/' . $hotel->businessProfile->profile_avatar) }}" 
                                     alt="{{ $hotel->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr($hotel->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $hotel->name }}</h3>
                            @if($hotel->address)
                                <p class="text-sm text-gray-600">{{ $hotel->address }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div class="relative h-64 bg-gray-200">
                        @if($hotel->businessProfile && $hotel->businessProfile->cover_image)
                            <img src="{{ asset('storage/' . $hotel->businessProfile->cover_image) }}" 
                                 alt="{{ $hotel->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-hotel text-white text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Action Icons -->
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center space-x-4">
                            <!-- Heart Icon for Likes -->
                            @auth
                                <button onclick="toggleHotelLike({{ $hotel->businessProfile->id }})" 
                                        id="likeBtn-{{ $hotel->businessProfile->id }}"
                                        class="flex items-center space-x-1 {{ $hotel->businessProfile->isLikedBy(auth()->user()) ? 'text-red-600' : 'text-gray-600 hover:text-red-500' }} transition-colors">
                                    <svg class="w-6 h-6" fill="{{ $hotel->businessProfile->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="text-sm" id="likeCount-{{ $hotel->businessProfile->id }}">{{ $hotel->businessProfile->likes->count() }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="text-sm">{{ $hotel->businessProfile->likes->count() }}</span>
                                </a>
                            @endauth
                            
                            <!-- Star Icon for Ratings -->
                            @auth
                                @php
                                    $userHotelRating = $hotel->businessProfile->hotelRatings()->where('user_id', auth()->id())->first();
                                @endphp
                                <button onclick="openHotelRatingModal({{ $hotel->businessProfile->id }})" 
                                        class="flex items-center space-x-1 {{ $userHotelRating ? 'text-yellow-500' : 'text-gray-600 hover:text-yellow-500' }} transition-colors">
                                    <svg class="w-6 h-6" fill="{{ $userHotelRating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="text-sm">{{ number_format($hotel->businessProfile->average_rating, 1) }} ({{ $hotel->businessProfile->total_ratings }})</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-yellow-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="text-sm">{{ number_format($hotel->businessProfile->average_rating, 1) }} ({{ $hotel->businessProfile->total_ratings }})</span>
                                </a>
                            @endauth
                            
                            <!-- Comment Icon -->
                            @auth
                                <button onclick="openHotelCommentModal({{ $hotel->businessProfile->id }})" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span class="text-sm">{{ $hotel->businessProfile->comments()->count() }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span class="text-sm">{{ $hotel->businessProfile->comments()->count() }}</span>
                                </a>
                            @endauth
                        </div>
                        
                        <!-- View Details Link -->
                        <a href="{{ route('customer.hotels.show', $hotel) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                            View Details →
                        </a>
                    </div>

                    <!-- Description -->
                    @if($hotel->businessProfile && $hotel->businessProfile->description)
                        <div class="px-4 pb-4">
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">{{ $hotel->name }}</span>
                                {{ Str::limit($hotel->businessProfile->description, 100) }}
                            </p>
                        </div>
                    @endif

                    <!-- Hotel Info -->
                    <div class="px-4 pb-4">
                        <div class="flex items-center text-gray-600 text-sm mb-2">
                            <i class="fas fa-bed w-4 mr-2 text-gray-400"></i>
                            <span>{{ $hotel->rooms->count() }} rooms</span>
                            @if($hotel->rooms->count() > 0)
                                @php
                                    $minPrice = $hotel->rooms->min('price_per_night');
                                @endphp
                                <span class="ml-4 text-blue-600 font-semibold">From ₱{{ number_format($minPrice, 0) }}/night</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-hotel text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Hotels Yet</h3>
            <p class="text-gray-500">Check back later for amazing places to stay!</p>
        </div>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like functionality for hotels
    window.toggleHotelLike = function(hotelId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('CSRF token not found. Please refresh the page.');
            return;
        }
        
        fetch(`/hotels/${hotelId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const likeBtn = document.getElementById(`likeBtn-${hotelId}`);
                const likeCount = document.getElementById(`likeCount-${hotelId}`);
                const heartIcon = likeBtn.querySelector('svg');
                
                if (likeBtn && likeCount && heartIcon) {
                    likeCount.textContent = data.likes_count;
                    
                    if (data.liked) {
                        likeBtn.className = 'flex items-center space-x-1 text-red-600 transition-colors';
                        heartIcon.setAttribute('fill', 'currentColor');
                    } else {
                        likeBtn.className = 'flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors';
                        heartIcon.setAttribute('fill', 'none');
                    }
                }
            } else {
                alert(data.error || 'Error updating like status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (error.message.includes('401')) {
                alert('Please log in to like this hotel');
                window.location.href = '/login';
            } else {
                alert('Error updating like status: ' + error.message);
            }
        });
    };

    // Rating Modal Functions
    window.openHotelRatingModal = function(hotelId) {
        document.getElementById('hotelRatingModal').classList.remove('hidden');
        document.getElementById('ratingHotelId').value = hotelId;
        
        // Reset form
        document.getElementById('hotelRatingForm').reset();
        resetHotelStars();
        
        // Ensure comment modal is closed
        document.getElementById('hotelCommentModal').classList.add('hidden');
    };

    window.closeHotelRatingModal = function() {
        document.getElementById('hotelRatingModal').classList.add('hidden');
    };

    // Comment Modal Functions
    window.openHotelCommentModal = function(hotelId) {
        document.getElementById('hotelCommentModal').classList.remove('hidden');
        document.getElementById('commentHotelId').value = hotelId;
        
        // Reset form
        document.getElementById('hotelCommentForm').reset();
        
        // Ensure rating modal is closed
        document.getElementById('hotelRatingModal').classList.add('hidden');
        
        // Load existing comments for this hotel
        loadHotelComments(hotelId);
    };

    window.closeHotelCommentModal = function() {
        document.getElementById('hotelCommentModal').classList.add('hidden');
    };

    // Load comments function
    function loadHotelComments(hotelId) {
        fetch(`/hotels/${hotelId}/comments`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('hotelCommentsContainer');
                if (data.comments && data.comments.length > 0) {
                    container.innerHTML = data.comments.map(comment => `
                        <div class="border-b border-gray-200 pb-4 last:border-b-0">
                            <div class="flex items-start space-x-3">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                                    ${comment.user && comment.user.profile_picture ? 
                                        `<img src="/storage/${comment.user.profile_picture}" alt="${comment.user.name}" class="w-full h-full rounded-full object-cover">` :
                                        `<div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                            ${comment.user ? comment.user.name.charAt(0).toUpperCase() : 'U'}
                                        </div>`
                                    }
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium text-gray-900">${comment.user.name}</span>
                                            <span class="text-sm text-gray-500">${comment.created_at_human}</span>
                                        </div>
                                        @auth
                                            ${comment.user.id === {{ auth()->id() ?? 'null' }} ? 
                                                `<button onclick="deleteHotelComment(${comment.id})" class="text-red-500 hover:text-red-700 text-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>` : ''
                                            }
                                        @endauth
                                    </div>
                                    <p class="text-gray-700 text-sm leading-relaxed">${comment.comment}</p>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-comments text-4xl mb-2"></i>
                            <p>No comments yet. Be the first to share your experience!</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error);
                document.getElementById('hotelCommentsContainer').innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <p>Error loading comments. Please try again.</p>
                    </div>
                `;
            });
    }

    window.setHotelRating = function(rating) {
        document.getElementById('hotelRatingValue').value = rating;
        
        // Update star display
        const stars = document.querySelectorAll('.hotel-rating-star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('text-yellow-400');
                star.classList.remove('text-gray-300');
            } else {
                star.classList.add('text-gray-300');
                star.classList.remove('text-yellow-400');
            }
        });
    };

    function resetHotelStars() {
        const stars = document.querySelectorAll('.hotel-rating-star');
        stars.forEach(star => {
            star.classList.add('text-gray-300');
            star.classList.remove('text-yellow-400');
        });
        document.getElementById('hotelRatingValue').value = '';
    }

    // Submit rating
    document.getElementById('hotelRatingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const hotelId = document.getElementById('ratingHotelId').value;
        
        fetch(`/hotels/${hotelId}/rate`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeHotelRatingModal();
                location.reload(); // Refresh to show updated rating
            } else {
                alert(data.error || 'Error submitting rating');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting rating');
        });
    });

    // Submit comment
    document.getElementById('hotelCommentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const hotelId = document.getElementById('commentHotelId').value;
        
        fetch(`/hotels/${hotelId}/comment`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeHotelCommentModal();
                location.reload(); // Refresh to show updated comments
            } else {
                alert(data.error || 'Error submitting comment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting comment');
        });
    });

    // Delete hotel comment function
    window.deleteHotelComment = function(commentId) {
        if (confirm('Are you sure you want to delete this comment?')) {
            fetch(`/hotel-comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload comments for the current hotel
                    const hotelId = document.getElementById('commentHotelId').value;
                    if (hotelId) {
                        loadHotelComments(hotelId);
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
});
</script>

<!-- Hotel Rating Modal -->
<div id="hotelRatingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rate This Hotel</h3>
                <button onclick="closeHotelRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="hotelRatingForm">
                @csrf
                <input type="hidden" id="ratingHotelId" name="hotel_id">
                <input type="hidden" id="hotelRatingValue" name="rating">
                
                <!-- Star Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setHotelRating({{ $i }})" class="hotel-rating-star text-gray-300 hover:text-yellow-400 transition-colors">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeHotelRatingModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                        Submit Rating
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hotel Comment Modal -->
<div id="hotelCommentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Comments & Reviews</h3>
                <button onclick="closeHotelCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Add Comment Form -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Add Your Comment</h4>
                <form id="hotelCommentForm">
                    @csrf
                    <input type="hidden" id="commentHotelId" name="hotel_id">
                    
                    <div class="mb-4">
                        <textarea name="comment" id="hotelCommentText" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your thoughts about this hotel..."
                                  required></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeHotelCommentModal()" 
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors">
                            Submit Comment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Comments -->
            <div class="border-t pt-4">
                <h4 class="text-md font-medium text-gray-900 mb-4">All Comments</h4>
                <div id="hotelCommentsContainer" class="space-y-4 max-h-96 overflow-y-auto">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-comments text-4xl mb-2"></i>
                        <p>Comments will be loaded when you select a hotel.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap');
    
    .font-serif {
        font-family: 'Playfair Display', serif;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Blue & Purple Theme for Hotels */
    .bg-blue-100 { background-color: #dbeafe; }
    .bg-purple-100 { background-color: #ede9fe; }
    .bg-blue-500 { background-color: #3b82f6; }
    .bg-blue-600 { background-color: #2563eb; }
    .bg-blue-700 { background-color: #1d4ed8; }
    .text-blue-600 { color: #2563eb; }
</style>
@endsection
