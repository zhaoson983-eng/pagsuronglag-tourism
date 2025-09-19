<!-- resources/views/attractions-show.blade.php -->
@extends('layouts.app')

@section('title', $attraction->name . ' - Tourist Attraction')

@section('content')
<!-- Hero Banner Section -->
<div class="w-full h-80 relative bg-cover bg-center overflow-hidden"
     style="background-image: url('{!! $attraction->cover_photo ? Storage::url($attraction->cover_photo) : asset('images/placeholder-cover.jpg') !!}')">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 -mt-20 relative z-10 pb-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Sidebar - Attraction Profile -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <!-- Attraction Avatar -->
                <div class="text-center mb-6">
                    <div class="w-32 h-32 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4 relative">
                        @if($attraction->profile_avatar)
                            <img src="{{ Storage::url($attraction->profile_avatar) }}" alt="{{ $attraction->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <i class="fas fa-map-marked-alt text-4xl text-blue-500"></i>
                        @endif
                        <div class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $attraction->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-4">
                        <i class="fas fa-check-circle mr-1"></i>
                        Open to Visitors
                    </span>
                </div>

                <!-- Contact Info -->
                <div class="space-y-3 mb-6">
                    @if($attraction->location)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                            <span class="text-sm">{{ $attraction->location }}</span>
                        </div>
                    @endif
                    @if($attraction->has_entrance_fee)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-ticket-alt w-5 mr-3"></i>
                            <span class="text-sm">₱{{ number_format($attraction->entrance_fee, 2) }} entrance fee</span>
                        </div>
                    @else
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-gift w-5 mr-3"></i>
                            <span class="text-sm">Free entrance</span>
                        </div>
                    @endif
                </div>

                <!-- Rating Section -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center justify-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= ($attraction->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <div class="text-center text-xs text-gray-600">
                        {{ number_format($attraction->average_rating ?? 0, 1) }}/5 ({{ $attraction->total_ratings ?? 0 }} ratings)
                    </div>
                </div>

                <!-- Interaction Buttons -->
                <div class="mt-4 flex items-center justify-center space-x-4">
                    <button class="flex items-center space-x-1 transition-colors like-btn" 
                            onclick="toggleLike('attraction', {{ $attraction->id }})" 
                            data-liked="{{ auth()->check() && $attraction->likes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}">
                        <i class="{{ auth()->check() && $attraction->likes()->where('user_id', auth()->id())->exists() ? 'fas' : 'far' }} fa-heart {{ auth()->check() && $attraction->likes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"></i>
                        <span class="text-sm like-count">{{ $attraction->likes()->count() }}</span>
                    </button>
                    <button class="flex items-center space-x-1 transition-colors" onclick="showRating('attraction', {{ $attraction->id }})">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="text-sm text-gray-600">{{ number_format($attraction->average_rating ?? 0, 1) }} ({{ $attraction->total_ratings ?? 0 }})</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-600 transition-colors" onclick="showComments('attraction', {{ $attraction->id }})">
                        <i class="fas fa-comment"></i>
                        <span class="text-sm">{{ $attraction->comments()->count() }}</span>
                    </button>
                </div>

                <!-- Description Card -->
                @if($attraction->description)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">About This Place</h3>
                        <p class="text-blue-700 text-sm">{{ $attraction->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="lg:col-span-3">
            <!-- Attraction Details -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Attraction Details</h2>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700">{{ $attraction->full_info }}</p>
                    
                    @if($attraction->additional_info)
                        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                            <h3 class="font-semibold text-yellow-800">Additional Information</h3>
                            <p class="mt-2 text-yellow-700">{{ $attraction->additional_info }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Gallery Section -->
            @if(!empty($attraction->gallery_images) && is_array($attraction->gallery_images) && count($attraction->gallery_images) > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($attraction->gallery_images as $img)
                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                                 onclick="openImageModal('{{ Storage::url($img) }}')">
                                <img src="{{ Storage::url($img) }}" 
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
                        <div class="text-3xl font-bold text-gray-900">{{ number_format($attraction->average_rating ?? 0, 1) }}</div>
                        <div class="flex items-center justify-center mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= ($attraction->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                            @endfor
                        </div>
                        <div class="text-sm text-gray-600">{{ $attraction->total_ratings ?? 0 }} ratings</div>
                    </div>
                    <div class="flex-1">
                        @php
                            $commentCount = DB::table('tourist_spot_comments')->where('tourist_spot_id', $attraction->id)->count();
                        @endphp
                        <div class="text-sm text-gray-600 mb-2">{{ $commentCount }} comments</div>
                    </div>
                </div>

                <!-- Add Comment Form -->
                @auth
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Add a Comment</h3>
                        <form id="attractionCommentForm">
                            @csrf
                            <input type="hidden" name="tourist_spot_id" value="{{ $attraction->id }}">
                            <div class="mb-4">
                                <textarea name="comment" id="attractionCommentText" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Share your experience about this attraction..."></textarea>
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
                @php
                    $comments = DB::table('tourist_spot_comments')
                        ->join('users', 'tourist_spot_comments.user_id', '=', 'users.id')
                        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
                        ->where('tourist_spot_comments.tourist_spot_id', $attraction->id)
                        ->select(
                            'tourist_spot_comments.*',
                            'users.name as user_name',
                            'profiles.profile_picture'
                        )
                        ->orderBy('tourist_spot_comments.created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @if($comments->count() > 0)
                    <div class="space-y-4" id="attractionCommentsList">
                        @foreach($comments as $comment)
                            <div class="border-b border-gray-200 pb-4" id="attraction-comment-{{ $comment->id }}">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        @if($comment->profile_picture)
                                            <img src="{{ asset('storage/' . $comment->profile_picture) }}" 
                                                 alt="{{ $comment->user_name }}" 
                                                 class="w-full h-full rounded-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">
                                                    {{ strtoupper(substr($comment->user_name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center space-x-2">
                                                <span class="font-medium text-gray-900">{{ $comment->user_name }}</span>
                                                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($comment->created_at)->format('M d, Y') }}</span>
                                            </div>
                                            @auth
                                                @if($comment->user_id === auth()->id())
                                                    <button onclick="deleteAttractionComment({{ $comment->id }})" 
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
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500" id="noAttractionCommentsMessage">
                        <i class="fas fa-comments text-4xl mb-2"></i>
                        <p>No comments yet. Be the first to share your experience!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="modalImage" src="" alt="Attraction Image" class="max-w-full max-h-full object-contain">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Submit comment
    const commentForm = document.getElementById('attractionCommentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const attractionId = formData.get('tourist_spot_id');
            
            fetch(`/tourist-spots/${attractionId}/comment`, {
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
                    document.getElementById('attractionCommentText').value = '';
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
window.deleteAttractionComment = function(commentId) {
    if (confirm('Are you sure you want to delete this comment?')) {
        fetch(`/tourist-spots/comments/${commentId}`, {
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
                const commentElement = document.getElementById(`attraction-comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
                
                // Check if no comments left
                const commentsList = document.getElementById('attractionCommentsList');
                if (commentsList && commentsList.children.length === 0) {
                    const noCommentsMessage = document.getElementById('noAttractionCommentsMessage');
                    if (noCommentsMessage) noCommentsMessage.style.display = 'block';
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

// Image modal functionality
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
</script>
@endsection