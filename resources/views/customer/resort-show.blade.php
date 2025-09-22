@extends('layouts.app')

@section('title', $business->businessProfile->business_name . ' - Resort - Pagsurong Lagonoy')

@section('content')
<!-- Hero Banner Section -->
<div class="w-full h-80 relative bg-cover bg-center overflow-hidden"
     style="background-image: url('{!! ($business->businessProfile->cover_image) ? Storage::url($business->businessProfile->cover_image) : asset('images/placeholder-cover.jpg') !!}')">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
</div>

<!-- Main Content -->
<div class="px-6 -mt-20 relative z-10 pb-6">
    <!-- Resort Profile Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6">
            <!-- Resort Avatar -->
            <div class="flex-shrink-0 text-center lg:text-left">
                <div class="w-32 h-32 mx-auto lg:mx-0 rounded-full bg-gray-100 flex items-center justify-center mb-4 relative">
                    @if($business->businessProfile->profile_avatar)
                        <img src="{{ Storage::url($business->businessProfile->profile_avatar) }}" alt="{{ $business->businessProfile->business_name }}" class="w-full h-full rounded-full object-cover">
                    @else
                        <i class="fas fa-umbrella-beach text-4xl text-blue-500"></i>
                    @endif
                    <div class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i>
                    Available Now
                </span>
            </div>

            <!-- Resort Info -->
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $business->businessProfile->business_name }}</h1>
                
                <!-- Contact Info -->
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-4">
                    @if($business->businessProfile->address)
                        <div class="flex items-center justify-center lg:justify-start text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $business->businessProfile->address }}</span>
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
                <div class="flex items-center justify-center lg:justify-start gap-4 mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= ($business->businessProfile->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ number_format($business->businessProfile->average_rating ?? 0, 1) }}</span>
                    <span class="text-gray-600">({{ $business->businessProfile->total_ratings ?? 0 }} ratings)</span>
                </div>

                <!-- Description -->
                @if($business->businessProfile->description)
                    <div class="text-gray-600 text-sm leading-relaxed">
                        <h3 class="font-semibold text-gray-900 mb-2">About This Resort</h3>
                        <p>{{ $business->businessProfile->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Rooms & Cottages Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-bed mr-3 text-blue-600"></i>
            Accommodations
        </h2>
        
        @if(($rooms && $rooms->count() > 0) || ($cottages && $cottages->count() > 0))
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if($rooms && $rooms->count() > 0)
                    @foreach($rooms as $room)
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                            @if($room->images && $room->images->count() > 0)
                                <div class="aspect-w-16 aspect-h-9 mb-4">
                                    <img src="{{ Storage::url($room->images->first()->image_path) }}" 
                                         alt="{{ $room->room_type }}" 
                                         class="w-full h-48 object-cover rounded-lg">
                                </div>
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-bed text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                            
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $room->room_type ?? $room->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $room->description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="text-blue-600 font-bold">
                                    {{ number_format($room->price_per_night, 2) }}/night
                                </div>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                    {{ $room->capacity ?? 2 }} guests
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
                
                @if($cottages && $cottages->count() > 0)
                    @foreach($cottages as $cottage)
                        <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow">
                            @if($cottage->galleries && $cottage->galleries->count() > 0)
                                <div class="aspect-w-16 aspect-h-9 mb-4">
                                    <img src="{{ Storage::url($cottage->galleries->first()->image_path) }}" 
                                         alt="{{ $cottage->name }}" 
                                         class="w-full h-48 object-cover rounded-lg">
                                </div>
                            @else
                                <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                                    <i class="fas fa-home text-gray-400 text-3xl"></i>
                                </div>
                            @endif
                            
                            <h3 class="font-semibold text-gray-900 mb-2">{{ $cottage->name }} (Cottage)</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $cottage->description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="text-blue-600 font-bold">
                                    {{ number_format($cottage->price_per_night, 2) }}/night
                                </div>
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">
                                    {{ $cottage->capacity ?? 4 }} guests
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-bed text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500 text-lg">No accommodations available at the moment</p>
                <p class="text-gray-400 text-sm">Please check back later for availability</p>
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
                <div class="text-3xl font-bold text-gray-900">{{ number_format($business->businessProfile->average_rating ?? 0, 1) }}</div>
                <div class="flex items-center justify-center mb-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= ($business->businessProfile->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                    @endfor
                </div>
                <div class="text-sm text-gray-600">{{ $business->businessProfile->total_ratings ?? 0 }} ratings</div>
            </div>
            <div class="flex-1">
                <div class="text-sm text-gray-600 mb-2">{{ $business->businessProfile->resortComments()->whereHas('user')->count() ?? 0 }} comments</div>
            </div>
        </div>

        <!-- Add Comment Form -->
        @auth
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Add a Comment</h3>
                <form id="commentForm">
                    @csrf
                    <input type="hidden" name="resort_id" value="{{ $business->businessProfile->id }}">
                    <div class="mb-4">
                        <textarea name="comment" id="commentText" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your experience about this resort..."></textarea>
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
        @if($business->businessProfile->resortComments && $business->businessProfile->resortComments()->whereHas('user')->count() > 0)
            <div class="space-y-4" id="commentsList">
                @foreach($business->businessProfile->resortComments->take(5) as $comment)
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

// Comment functionality
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const resortId = formData.get('resort_id');

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
