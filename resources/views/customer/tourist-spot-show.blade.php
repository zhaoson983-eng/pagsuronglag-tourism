@extends('layouts.app')

@section('title', $touristSpot->name . ' - Tourist Spot - Pagsurong Lagonoy')

@section('content')
<!-- Hero Banner Section -->
<div class="w-full h-80 relative bg-cover bg-center overflow-hidden"
     style="background-image: url('{{ $touristSpot->cover_image ? asset('storage/' . $touristSpot->cover_image) : asset('images/placeholder-cover.jpg') }}')">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 -mt-20 relative z-10 pb-6">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Left - Tourist Spot Profile -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-2xl shadow-lg p-4">
                <!-- Tourist Spot Avatar -->
                <div class="text-center mb-4">
                    <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-3 relative">
                        @if($touristSpot->profile_avatar)
                            <img src="{{ asset('storage/' . $touristSpot->profile_avatar) }}" alt="{{ $touristSpot->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <i class="fas fa-map-marked-alt text-3xl text-blue-500"></i>
                        @endif
                        <div class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <i class="fas fa-check text-xs"></i>
                        </div>
                    </div>
                    <h1 class="text-lg font-bold text-gray-800 mb-2">{{ $touristSpot->name }}</h1>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-3">
                        <i class="fas fa-map-marked-alt mr-1"></i>
                        Tourist Spot
                    </span>
                </div>

                <!-- Location Info -->
                <div class="space-y-2 mb-4">
                    @if($touristSpot->location)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                            <span class="text-xs">{{ $touristSpot->location }}</span>
                        </div>
                    @endif
                    @if($touristSpot->map_link)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-external-link-alt w-4 mr-2"></i>
                            <a href="{{ $touristSpot->map_link }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800">View on Map</a>
                        </div>
                    @endif
                </div>

                <!-- Contact Info -->
                <div class="space-y-2 mb-4">
                    @if($touristSpot->uploader)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-user w-4 mr-2"></i>
                            <span class="text-xs">Added by {{ $touristSpot->uploader->name }}</span>
                        </div>
                    @endif
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-calendar w-4 mr-2"></i>
                        <span class="text-xs">{{ $touristSpot->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <!-- Rating Section -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center justify-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $touristSpot->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <div class="text-center text-xs text-gray-600">
                        {{ number_format($touristSpot->average_rating, 1) }}/5 ({{ $touristSpot->total_ratings }} ratings)
                    </div>
                </div>

            </div>
        </div>

        <!-- Right - Description and Gallery -->
        <div class="lg:w-3/4">
            <!-- Description & Additional Info -->
            @if($touristSpot->description || $touristSpot->additional_info)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">About This Place</h2>
                    
                    @if($touristSpot->description)
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Description</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $touristSpot->description }}</p>
                        </div>
                    @endif

                    @if($touristSpot->additional_info)
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Additional Information</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $touristSpot->additional_info }}</p>
                        </div>
                    @endif

                    <!-- Gallery Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Gallery</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @if($touristSpot->cover_image)
                                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                    <img src="{{ asset('storage/' . $touristSpot->cover_image) }}" 
                                         alt="{{ $touristSpot->name }}" 
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-200 cursor-pointer"
                                         onclick="openImagePreview('{{ asset('storage/' . $touristSpot->cover_image) }}')">
                                </div>
                            @endif
                            
                            @if($touristSpot->gallery_images)
                                @php
                                    $galleryImages = json_decode($touristSpot->gallery_images, true);
                                @endphp
                                @if(is_array($galleryImages))
                                    @foreach($galleryImages as $image)
                                        <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                                            <img src="{{ asset('storage/' . $image) }}" 
                                                 alt="{{ $touristSpot->name }} Gallery" 
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-200 cursor-pointer"
                                                 onclick="openImagePreview('{{ asset('storage/' . $image) }}')">
                                        </div>
                                    @endforeach
                                @endif
                            @endif
                            
                            @if(!$touristSpot->cover_image && (!$touristSpot->gallery_images || empty(json_decode($touristSpot->gallery_images, true))))
                                <div class="col-span-full text-center py-8 text-gray-500">
                                    <i class="fas fa-images text-4xl mb-2"></i>
                                    <p>No gallery images available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($touristSpot->map_link)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ $touristSpot->map_link }}" 
                               target="_blank"
                               class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-map-marked-alt mr-2"></i>
                                View on Map
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Reviews & Comments Section -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Reviews & Comments</h2>
                
                <!-- Rating Summary -->
                <div class="flex items-center mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="text-center mr-6">
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($touristSpot->average_rating, 1) }}</div>
                        <div class="flex items-center justify-center mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $touristSpot->average_rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                            @endfor
                        </div>
                        <div class="text-sm text-gray-600">{{ $touristSpot->total_ratings }} ratings</div>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-600 mb-2">{{ $touristSpot->comments()->whereHas('user')->count() ?? 0 }} comments</div>
                    </div>
                </div>

                <!-- Add Comment Form -->
                @auth
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Add a Comment</h3>
                        <form id="commentForm">
                            @csrf
                            <input type="hidden" name="tourist_spot_id" value="{{ $touristSpot->id }}">
                            <div class="mb-4">
                                <textarea name="comment" id="commentText" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Share your experience about this place..."></textarea>
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
                @if($touristSpot->comments && $touristSpot->comments()->whereHas('user')->count() > 0)
                    <div class="space-y-4" id="commentsList">
                        @foreach($touristSpot->comments->take(5) as $comment)
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
                                                    {{ $comment->user ? strtoupper(substr($comment->user->name, 0, 1)) : 'U' }}
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
    </div>

<!-- Rating Modal -->
<div id="ratingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Rate This Place</h3>
                <button onclick="closeRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="ratingForm">
                @csrf
                <input type="hidden" id="ratingTouristSpotId" name="tourist_spot_id">
                <input type="hidden" id="ratingValue" name="rating">
                
                <!-- Star Rating -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="rating-star text-gray-300 hover:text-yellow-400 transition-colors">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRatingModal()" 
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

<!-- Comment Modal -->
<div id="commentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Comments & Reviews</h3>
                <button onclick="closeCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Add Comment Form -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Add Your Comment</h4>
                <form id="commentForm">
                    @csrf
                    <input type="hidden" id="commentTouristSpotId" name="tourist_spot_id">
                    
                    <div class="mb-4">
                        <textarea name="comment" id="commentText" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your thoughts about this place..."
                                  required></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeCommentModal()" 
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
                <h4 class="text-md font-medium text-gray-900 mb-4">All Comments ({{ $touristSpot->comments()->whereHas('user')->count() ?? 0 }})</h4>
                <div id="commentsContainer" class="space-y-4 max-h-96 overflow-y-auto">
                    @if($touristSpot->comments && $touristSpot->comments()->whereHas('user')->count() > 0)
                        @foreach($touristSpot->comments->sortByDesc('created_at') as $comment)
                            @if($comment->user)
                            <div class="border-b border-gray-200 pb-4 last:border-b-0">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
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
                                                <span class="text-white text-sm font-bold">
                                                    {{ $comment->user ? strtoupper(substr($comment->user->name, 0, 1)) : 'U' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 text-sm leading-relaxed">{{ $comment->comment }}</p>
                                        @if(auth()->check() && auth()->id() === $comment->user_id)
                                            <button onclick="deleteComment({{ $comment->id }})" 
                                                    class="text-red-500 hover:text-red-700 text-xs mt-2">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-comments text-4xl mb-2"></i>
                            <p>No comments yet. Be the first to share your experience!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like functionality
    window.toggleLike = function(touristSpotId) {
        fetch(`/tourist-spots/${touristSpotId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const likeBtn = document.getElementById(`likeBtn-${touristSpotId}`);
                const likeCount = document.getElementById(`likeCount-${touristSpotId}`);
                
                if (likeBtn && likeCount) {
                    likeCount.textContent = data.likes_count + ' Likes';
                    
                    if (data.liked) {
                        likeBtn.className = 'w-full flex items-center justify-center space-x-2 px-4 py-2 rounded-lg transition-colors bg-red-100 text-red-600';
                    } else {
                        likeBtn.className = 'w-full flex items-center justify-center space-x-2 px-4 py-2 rounded-lg transition-colors bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-600';
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

    // Rating Modal Functions
    window.openRatingModal = function(touristSpotId) {
        document.getElementById('ratingModal').classList.remove('hidden');
        document.getElementById('ratingTouristSpotId').value = touristSpotId;
        document.getElementById('ratingForm').reset();
        resetStars();
        // Ensure comment modal is closed
        document.getElementById('commentModal').classList.add('hidden');
    };

    window.closeRatingModal = function() {
        document.getElementById('ratingModal').classList.add('hidden');
    };

    // Comment Modal Functions
    window.openCommentModal = function(touristSpotId) {
        document.getElementById('commentModal').classList.remove('hidden');
        document.getElementById('commentTouristSpotId').value = touristSpotId;
        document.getElementById('commentForm').reset();
        // Ensure rating modal is closed
        document.getElementById('ratingModal').classList.add('hidden');
    };

    window.closeCommentModal = function() {
        document.getElementById('commentModal').classList.add('hidden');
    };

    // Delete comment function
    window.deleteComment = function(commentId) {
        if (confirm('Are you sure you want to delete this comment?')) {
            fetch(`/tourist-spots/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
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

    window.setRating = function(rating) {
        document.getElementById('ratingValue').value = rating;
        
        const stars = document.querySelectorAll('.rating-star');
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

    function resetStars() {
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(star => {
            star.classList.add('text-gray-300');
            star.classList.remove('text-yellow-400');
        });
        document.getElementById('ratingValue').value = '';
    }

    // Submit rating
    document.getElementById('ratingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const touristSpotId = document.getElementById('ratingTouristSpotId').value;
        
        fetch(`/tourist-spots/${touristSpotId}/rate`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeRatingModal();
                // Update the rating display without full page reload
                updateRatingDisplay(data.average_rating, data.total_ratings);
            } else {
                alert(data.error || 'Error submitting rating');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error submitting rating');
        });
    });

    // Update rating display function
    function updateRatingDisplay(averageRating, totalRatings) {
        // Update sidebar rating
        const sidebarStars = document.querySelectorAll('.bg-gray-50 svg');
        sidebarStars.forEach((star, index) => {
            if (index < Math.floor(averageRating)) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
        
        // Update sidebar rating text
        const sidebarRatingText = document.querySelector('.bg-gray-50 .text-center .text-sm');
        if (sidebarRatingText) {
            sidebarRatingText.textContent = `${averageRating}/5 (${totalRatings} ratings)`;
        }
        
        // Update main content rating
        const mainRatingValue = document.querySelector('.text-3xl.font-bold');
        if (mainRatingValue) {
            mainRatingValue.textContent = averageRating;
        }
        
        const mainStars = document.querySelectorAll('.bg-gray-50 + .flex-1 .flex i');
        mainStars.forEach((star, index) => {
            if (index < Math.floor(averageRating)) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
        
        const mainRatingText = document.querySelector('.text-sm.text-gray-600');
        if (mainRatingText && mainRatingText.textContent.includes('ratings')) {
            mainRatingText.textContent = `${totalRatings} ratings`;
        }
    }

    // Submit comment
    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const touristSpotId = formData.get('tourist_spot_id');
        
        fetch(`/tourist-spots/${touristSpotId}/comment`, {
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
                
                // Update comment count
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

// Image Preview Modal Functions
window.openImagePreview = function(imageSrc) {
    const modal = document.getElementById('imagePreviewModal');
    const modalImage = document.getElementById('previewImage');
    modalImage.src = imageSrc;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
};

window.closeImagePreview = function() {
    const modal = document.getElementById('imagePreviewModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
};

// Close modal when clicking outside the image
document.addEventListener('click', function(e) {
    const modal = document.getElementById('imagePreviewModal');
    if (e.target === modal) {
        closeImagePreview();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImagePreview();
    }
});
</script>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full p-4">
        <button onclick="closeImagePreview()" 
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="previewImage" src="" alt="Preview" class="max-w-full max-h-full object-contain rounded-lg">
    </div>
</div>

@endsection
