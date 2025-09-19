@extends('layouts.app')

@section('title', 'Resorts - Pagsurong Lagonoy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Discover Amazing Resorts</h1>
        <p class="text-gray-600">Find the perfect resort for your next getaway in Lagonoy</p>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <div class="max-w-md mx-auto">
            <form action="{{ route('customer.resorts') }}" method="GET" class="flex">
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search resorts..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>
    @if($resorts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($resorts as $resort)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <!-- Header with Profile Avatar and Name -->
                    <div class="flex items-center p-4 pb-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                            @if($resort->profile_avatar)
                                <img src="{{ asset('storage/' . $resort->profile_avatar) }}" 
                                     alt="{{ $resort->business_name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr($resort->business_name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $resort->business_name }}</h3>
                            @if($resort->address)
                                <p class="text-sm text-gray-600">{{ $resort->address }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div class="relative h-64 bg-gray-200">
                        @if($resort->cover_image)
                            <img src="{{ Storage::url($resort->cover_image) }}" 
                                 alt="{{ $resort->business_name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-umbrella-beach text-white text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Action Icons -->
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center space-x-4">
                            <!-- Heart Icon for Likes -->
                            @auth
                                <button onclick="toggleResortLike({{ $resort->id }})" 
                                        id="likeBtn-{{ $resort->id }}"
                                        class="flex items-center space-x-1 {{ $resort->isLikedBy(auth()->user()) ? 'text-red-600' : 'text-gray-600 hover:text-red-500' }} transition-colors">
                                    <svg class="w-6 h-6" fill="{{ $resort->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="text-sm" id="likeCount-{{ $resort->id }}">{{ $resort->likes->count() }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="text-sm">{{ $resort->likes->count() }}</span>
                                </a>
                            @endauth
                            
                            <!-- Star Icon for Ratings -->
                            @auth
                                @php
                                    $userResortRating = $resort->resortRatings()->where('user_id', auth()->id())->first();
                                @endphp
                                <button onclick="openResortRatingModal({{ $resort->id }})" 
                                        class="flex items-center space-x-1 {{ $userResortRating ? 'text-yellow-500' : 'text-gray-600 hover:text-yellow-500' }} transition-colors">
                                    <svg class="w-6 h-6" fill="{{ $userResortRating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="text-sm">{{ number_format($resort->average_rating, 1) }} ({{ $resort->total_ratings }})</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-yellow-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="text-sm">{{ number_format($resort->average_rating, 1) }} ({{ $resort->total_ratings }})</span>
                                </a>
                            @endauth
                            
                            <!-- Comment Icon -->
                            @auth
                                <button onclick="openResortCommentModal({{ $resort->id }})" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span class="text-sm">{{ $resort->comments()->count() }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span class="text-sm">{{ $resort->comments()->count() }}</span>
                                </a>
                            @endauth
                        </div>
                        
                        <!-- View Details Link -->
                        <a href="{{ route('customer.resorts.show', $resort->id) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                            View Resort →
                        </a>
                    </div>

                    <!-- Description -->
                    @if($resort->description)
                        <div class="px-4 pb-4">
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">{{ $resort->business_name }}</span>
                                {{ Str::limit($resort->description, 100) }}
                            </p>
                        </div>
                    @endif

                    <!-- Resort Info -->
                    <div class="px-4 pb-4">
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                <i class="fas fa-door-open mr-1"></i>{{ $resort->rooms_count }} Rooms
                            </span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                <i class="fas fa-home mr-1"></i>{{ $resort->cottages_count }} Cottages
                            </span>
                            @if($resort->min_price)
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                                    From ₱{{ number_format($resort->min_price) }}/night
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-umbrella-beach text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Resorts Yet</h3>
            <p class="text-gray-500">Check back later for amazing resort getaways!</p>
        </div>
    @endif

    <!-- Pagination -->
    @if($resorts->hasPages())
        <div class="mt-8">
            {{ $resorts->links() }}
        </div>
    @endif
</div>

<!-- Resort Rating Modal -->
<div id="resortRatingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rate This Resort</h3>
                <button onclick="closeResortRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="resortRatingForm">
                @csrf
                <input type="hidden" id="ratingResortId" name="resort_id">
                <input type="hidden" id="resortRatingValue" name="rating">
                
                <!-- Star Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setResortRating({{ $i }})" class="resort-rating-star text-gray-300 hover:text-yellow-400 transition-colors">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeResortRatingModal()" 
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

<!-- Resort Comment Modal -->
<div id="resortCommentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Comments & Reviews</h3>
                <button onclick="closeResortCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Add Comment Form -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Add Your Comment</h4>
                <form id="resortCommentForm">
                    @csrf
                    <input type="hidden" id="commentResortId" name="resort_id">
                    
                    <div class="mb-4">
                        <textarea name="comment" id="resortCommentText" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your thoughts about this resort..."
                                  required></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeResortCommentModal()" 
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
                <div id="resortCommentsContainer" class="space-y-4 max-h-96 overflow-y-auto">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-comments text-4xl mb-2"></i>
                        <p>Comments will be loaded when you select a resort.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentResortId = null;
    let selectedRating = 0;

    // Like functionality
    window.toggleResortLike = function(resortId) {
        fetch(`/resorts/${resortId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const likeBtn = document.getElementById(`likeBtn-${resortId}`);
                const likeCount = document.getElementById(`likeCount-${resortId}`);
                const heartSvg = likeBtn.querySelector('svg');
                
                if (data.liked) {
                    likeBtn.classList.remove('text-gray-600', 'hover:text-red-500');
                    likeBtn.classList.add('text-red-600');
                    heartSvg.setAttribute('fill', 'currentColor');
                } else {
                    likeBtn.classList.remove('text-red-600');
                    likeBtn.classList.add('text-gray-600', 'hover:text-red-500');
                    heartSvg.setAttribute('fill', 'none');
                }
                
                likeCount.textContent = data.likes_count;
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    // Rating Modal Functions
    window.openResortRatingModal = function(resortId) {
        document.getElementById('resortRatingModal').classList.remove('hidden');
        document.getElementById('ratingResortId').value = resortId;
        
        // Reset form
        document.getElementById('resortRatingForm').reset();
        resetResortStars();
        
        // Ensure comment modal is closed
        document.getElementById('resortCommentModal').classList.add('hidden');
    };

    window.closeResortRatingModal = function() {
        document.getElementById('resortRatingModal').classList.add('hidden');
    };

    window.setResortRating = function(rating) {
        document.getElementById('resortRatingValue').value = rating;
        
        // Update star display
        const stars = document.querySelectorAll('.resort-rating-star');
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

    function resetResortStars() {
        const stars = document.querySelectorAll('.resort-rating-star');
        stars.forEach(star => {
            star.classList.add('text-gray-300');
            star.classList.remove('text-yellow-400');
        });
        document.getElementById('resortRatingValue').value = '';
    }

    // Submit rating
    document.getElementById('resortRatingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const resortId = document.getElementById('ratingResortId').value;
        
        fetch(`/resorts/${resortId}/rate`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeResortRatingModal();
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

    // Comment Modal Functions
    window.openResortCommentModal = function(resortId) {
        document.getElementById('resortCommentModal').classList.remove('hidden');
        document.getElementById('commentResortId').value = resortId;
        
        // Reset form
        document.getElementById('resortCommentForm').reset();
        
        // Ensure rating modal is closed
        document.getElementById('resortRatingModal').classList.add('hidden');
        
        // Load existing comments for this resort
        loadResortComments(resortId);
    };

    window.closeResortCommentModal = function() {
        document.getElementById('resortCommentModal').classList.add('hidden');
    };

    // Load comments function
    function loadResortComments(resortId) {
        fetch(`/resorts/${resortId}/comments`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('resortCommentsContainer');
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
                                                `<button onclick="deleteResortComment(${comment.id})" class="text-red-500 hover:text-red-700 text-sm">
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
                document.getElementById('resortCommentsContainer').innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <p>Error loading comments. Please try again.</p>
                    </div>
                `;
            });
    }

    // Submit comment
    document.getElementById('resortCommentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const resortId = document.getElementById('commentResortId').value;
        
        fetch(`/resorts/${resortId}/comment`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeResortCommentModal();
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

    // Delete resort comment function
    window.deleteResortComment = function(commentId) {
        if (confirm('Are you sure you want to delete this comment?')) {
            fetch(`/resort-comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload comments for the current resort
                    const resortId = document.getElementById('commentResortId').value;
                    if (resortId) {
                        loadResortComments(resortId);
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

@endsection
