@extends('layouts.app')

@section('title', 'Tourist Attractions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Tourist Attractions</h1>
        <p class="text-gray-600">Discover amazing places to visit in Lagonoy</p>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <div class="max-w-md mx-auto">
            <form action="{{ route('customer.attractions') }}" method="GET" class="flex">
                <input type="text" 
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search tourist attractions..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    @if($touristSpots->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($touristSpots as $spot)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200" data-spot-id="{{ $spot->id }}">
                    <!-- Header with Profile Avatar and Name -->
                    <div class="flex items-center p-4 pb-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                            @if($spot->profile_avatar)
                                <img src="{{ asset('storage/' . $spot->profile_avatar) }}" 
                                     alt="{{ $spot->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white text-sm font-bold">
                                        {{ strtoupper(substr($spot->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $spot->name }}</h3>
                            @if($spot->location)
                                <p class="text-sm text-gray-600">{{ $spot->location }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div class="relative h-64 bg-gray-200">
                        @if($spot->cover_image)
                            <img src="{{ asset('storage/' . $spot->cover_image) }}" 
                                 alt="{{ $spot->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <i class="fas fa-map-marked-alt text-white text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Action Icons -->
                    <div class="flex items-center justify-between p-4">
                        <div class="flex items-center space-x-4">
                            <!-- Heart Icon for Likes -->
                            @auth
                                <button onclick="toggleTouristSpotLike({{ $spot->id }})" 
                                        id="likeBtn-{{ $spot->id }}"
                                        class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="like-icon-{{ $spot->id }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="text-sm" id="like-count-{{ $spot->id }}">{{ $spot->total_likes ?? 0 }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="text-sm">{{ $spot->total_likes ?? 0 }}</span>
                                </a>
                            @endauth
                            
                            <!-- Star Icon for Ratings -->
                            @auth
                                @php
                                    $userTouristSpotRating = DB::table('tourist_spot_ratings')->where('user_id', auth()->id())->where('tourist_spot_id', $spot->id)->first();
                                @endphp
                                <button onclick="openTouristSpotRatingModal({{ $spot->id }})" 
                                        class="flex items-center space-x-1 {{ $userTouristSpotRating ? 'text-yellow-500' : 'text-gray-600 hover:text-yellow-500' }} transition-colors">
                                    <svg class="w-6 h-6" fill="{{ $userTouristSpotRating ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="text-sm">{{ number_format($spot->average_rating, 1) }} ({{ $spot->total_ratings }})</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-yellow-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    <span class="text-sm">{{ number_format($spot->average_rating, 1) }} ({{ $spot->total_ratings }})</span>
                                </a>
                            @endauth
                            
                            <!-- Comment Icon -->
                            @auth
                                <button onclick="openTouristSpotCommentModal({{ $spot->id }})" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span class="text-sm">{{ $spot->comments()->count() }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span class="text-sm">{{ $spot->comments()->count() }}</span>
                                </a>
                            @endauth
                        </div>
                        
                        <!-- View Details Link -->
                        <a href="{{ route('customer.attractions.show', $spot->id) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                            View Details →
                        </a>
                    </div>


                    <!-- Description -->
                    @if($spot->description)
                        <div class="px-4 pb-4">
                            <p class="text-sm text-gray-700">
                                <span class="font-semibold">{{ $spot->name }}</span>
                                {{ Str::limit($spot->description, 100) }}
                            </p>
                        </div>
                    @endif

                    <!-- Uploader Info -->
                    <div class="px-4 pb-4">
                        <p class="text-xs text-gray-500">Uploaded by {{ $spot->uploader->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-map-marked-alt text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Tourist Spots Yet</h3>
            <p class="text-gray-500">Check back later for amazing places to visit!</p>
        </div>
    @endif
</div>



@endsection

<!-- Tourist Spot Rating Modal -->
<div id="touristSpotRatingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rate This Tourist Spot</h3>
                <button onclick="closeTouristSpotRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="touristSpotRatingForm">
                @csrf
                <input type="hidden" id="ratingTouristSpotId" name="tourist_spot_id">
                <input type="hidden" id="touristSpotRatingValue" name="rating">
                
                <!-- Star Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setTouristSpotRating({{ $i }})" class="tourist-spot-rating-star text-gray-300 hover:text-yellow-400 transition-colors">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTouristSpotRatingModal()" 
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

<!-- Tourist Spot Comment Modal -->
<div id="touristSpotCommentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Comments & Reviews</h3>
                <button onclick="closeTouristSpotCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Add Comment Form -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Add Your Comment</h4>
                <form id="touristSpotCommentForm">
                    @csrf
                    <input type="hidden" id="commentTouristSpotId" name="tourist_spot_id">
                    
                    <div class="mb-4">
                        <textarea name="comment" id="touristSpotCommentText" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your thoughts about this tourist spot..."
                                  required></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeTouristSpotCommentModal()" 
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
                <div id="touristSpotCommentsContainer" class="space-y-4 max-h-96 overflow-y-auto">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-comments text-4xl mb-2"></i>
                        <p>Comments will be loaded when you select a tourist spot.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Tourist Spot Like functionality
function toggleTouristSpotLike(spotId) {
    fetch(`/tourist-spots/${spotId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeButton = document.querySelector(`button[onclick="toggleTouristSpotLike(${spotId})"]`);
            const likeIcon = likeButton.querySelector('svg');
            const likeCount = likeButton.querySelector('span');
            
            if (data.liked) {
                likeIcon.setAttribute('fill', 'currentColor');
                likeButton.classList.remove('text-gray-600', 'hover:text-red-500');
                likeButton.classList.add('text-red-500');
            } else {
                likeIcon.setAttribute('fill', 'none');
                likeButton.classList.remove('text-red-500');
                likeButton.classList.add('text-gray-600', 'hover:text-red-500');
            }
            
            likeCount.textContent = data.total_likes;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request.');
    });
}

// Load initial like states when page loads
document.addEventListener('DOMContentLoaded', function() {
    @auth
    const spotIds = [
        @foreach($touristSpots as $spot)
            {{ $spot->id }},
        @endforeach
    ];
    
    spotIds.forEach(spotId => {
        fetch(`/tourist-spots/${spotId}/like-status`)
            .then(response => response.json())
            .then(data => {
                if (data.liked) {
                    const likeButton = document.querySelector(`button[onclick="toggleTouristSpotLike(${spotId})"]`);
                    if (likeButton) {
                        const likeIcon = likeButton.querySelector('svg');
                        likeIcon.setAttribute('fill', 'currentColor');
                        likeButton.classList.remove('text-gray-600', 'hover:text-red-500');
                        likeButton.classList.add('text-red-500');
                    }
                }
            })
            .catch(error => console.error('Error loading like status:', error));
    });
    @endauth
});

// Tourist Spot Rating Modal Functions
function openTouristSpotRatingModal(spotId) {
    document.getElementById('ratingTouristSpotId').value = spotId;
    document.getElementById('touristSpotRatingModal').classList.remove('hidden');
    resetTouristSpotRatingStars();
}

function closeTouristSpotRatingModal() {
    document.getElementById('touristSpotRatingModal').classList.add('hidden');
    resetTouristSpotRatingStars();
    document.getElementById('touristSpotRatingValue').value = '';
}

function setTouristSpotRating(rating) {
    document.getElementById('touristSpotRatingValue').value = rating;
    const stars = document.querySelectorAll('.tourist-spot-rating-star');
    
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

function resetTouristSpotRatingStars() {
    const stars = document.querySelectorAll('.tourist-spot-rating-star');
    stars.forEach(star => {
        star.classList.remove('text-yellow-400');
        star.classList.add('text-gray-300');
    });
}

// Tourist Spot Rating Form Submission
document.getElementById('touristSpotRatingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const spotId = document.getElementById('ratingTouristSpotId').value;
    const rating = document.getElementById('touristSpotRatingValue').value;
    
    if (!rating) {
        alert('Please select a rating before submitting.');
        return;
    }
    
    fetch(`/tourist-spots/${spotId}/rate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            rating: rating
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeTouristSpotRatingModal();
            location.reload(); // Refresh to show updated rating
        } else {
            alert(data.message || 'An error occurred while submitting your rating.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting your rating.');
    });
});

// Tourist Spot Comment Modal Functions
function openTouristSpotCommentModal(spotId) {
    document.getElementById('commentTouristSpotId').value = spotId;
    document.getElementById('touristSpotCommentModal').classList.remove('hidden');
    loadTouristSpotCommentsInModal(spotId);
}

function closeTouristSpotCommentModal() {
    document.getElementById('touristSpotCommentModal').classList.add('hidden');
    document.getElementById('touristSpotCommentText').value = '';
}

function loadTouristSpotCommentsInModal(spotId) {
    fetch(`/tourist-spots/${spotId}/comments`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('touristSpotCommentsContainer');
            
            if (data.comments.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-comments text-4xl mb-2"></i>
                        <p>No comments yet. Be the first to share your thoughts!</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = data.comments.map(comment => `
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            ${comment.user.profile_picture ? 
                                `<img src="/storage/${comment.user.profile_picture}" alt="${comment.user.name}" class="w-10 h-10 rounded-full object-cover">` :
                                `<div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">${comment.user.name.charAt(0).toUpperCase()}</div>`
                            }
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <h4 class="font-medium text-gray-900">${comment.user.name}</h4>
                                    <span class="text-sm text-gray-500">${new Date(comment.created_at).toLocaleDateString()}</span>
                                </div>
                                ${comment.user_id == {{ auth()->id() ?? 'null' }} ? 
                                    `<button onclick="deleteTouristSpotCommentFromModal(${comment.id}, ${spotId})" class="text-red-500 hover:text-red-700 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>` : 
                                    ''
                                }
                            </div>
                            <p class="text-gray-700 mt-2">${comment.comment}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading comments:', error);
            document.getElementById('touristSpotCommentsContainer').innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <p>Error loading comments. Please try again.</p>
                </div>
            `;
        });
}

// Tourist Spot Comment Form Submission
document.getElementById('touristSpotCommentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const spotId = document.getElementById('commentTouristSpotId').value;
    const comment = document.getElementById('touristSpotCommentText').value.trim();
    
    if (!comment) {
        alert('Please enter a comment before submitting.');
        return;
    }
    
    fetch(`/tourist-spots/${spotId}/comment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            comment: comment
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('touristSpotCommentText').value = '';
            loadTouristSpotCommentsInModal(spotId);
            // Update comment count on the page
            location.reload();
        } else {
            alert(data.message || 'An error occurred while posting your comment.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while posting your comment.');
    });
});

function deleteTouristSpotCommentFromModal(commentId, spotId) {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }
    
    fetch(`/tourist-spots/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadTouristSpotCommentsInModal(spotId);
            // Update comment count on the page
            location.reload();
        } else {
            alert(data.message || 'An error occurred while deleting the comment.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the comment.');
    });
}
</script>
@endpush
