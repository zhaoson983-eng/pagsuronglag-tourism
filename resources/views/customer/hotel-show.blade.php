@extends('layouts.app')

@section('title', $business->name . ' - Hotel - Pagsurong Lagonoy')

@section('content')
<!-- Hero Banner Section -->
<div class="w-full h-80 relative bg-cover bg-center overflow-hidden"
     style="background-image: url('{!! ($business->businessProfile && $business->businessProfile->cover_image) ? Storage::url($business->businessProfile->cover_image) : asset('images/placeholder-cover.jpg') !!}')">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 -mt-20 relative z-10 pb-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Sidebar - Hotel Profile -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <!-- Hotel Avatar -->
                <div class="text-center mb-6">
                    <div class="w-32 h-32 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4 relative">
                        @if($business->businessProfile && $business->businessProfile->profile_avatar)
                            <img src="{{ Storage::url($business->businessProfile->profile_avatar) }}" alt="{{ $business->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <i class="fas fa-hotel text-4xl text-blue-500"></i>
                        @endif
                        <div class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $business->name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-4">
                        <i class="fas fa-check-circle mr-1"></i>
                        Available Now
                    </span>
                </div>

                <!-- Contact Info -->
                <div class="space-y-3 mb-6">
                    @if($business->address)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                            <span class="text-sm">{{ $business->address }}</span>
                        </div>
                    @endif
                    @if($business->contact_number)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-5 mr-3"></i>
                            <span class="text-sm">{{ $business->contact_number }}</span>
                        </div>
                    @endif
                </div>

                <!-- Rating Section -->
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center justify-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= ($business->businessProfile->average_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <div class="text-center text-xs text-gray-600">
                        {{ number_format($business->businessProfile->average_rating ?? 0, 1) }}/5 ({{ $business->businessProfile->total_ratings ?? 0 }} ratings)
                    </div>
                </div>

                <!-- Interaction Buttons -->
                <div class="mt-4 flex items-center justify-center space-x-4">
                    <button class="flex items-center space-x-1 transition-colors like-btn" 
                            onclick="toggleLike('hotel', {{ $business->businessProfile->id }})" 
                            data-liked="{{ auth()->check() && $business->businessProfile->hotelLikes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}">
                        <i class="{{ auth()->check() && $business->businessProfile->hotelLikes()->where('user_id', auth()->id())->exists() ? 'fas' : 'far' }} fa-heart {{ auth()->check() && $business->businessProfile->hotelLikes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"></i>
                        <span class="text-sm like-count">{{ $business->businessProfile->hotelLikes()->count() }}</span>
                    </button>
                    <button class="flex items-center space-x-1 transition-colors" onclick="showRating('hotel', {{ $business->businessProfile->id }})">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="text-sm text-gray-600">{{ number_format($business->businessProfile->average_rating ?? 0, 1) }} ({{ $business->businessProfile->total_ratings ?? 0 }})</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-600 transition-colors" onclick="showComments('hotel', {{ $business->businessProfile->id }})">
                        <i class="fas fa-comment"></i>
                        <span class="text-sm">{{ $business->businessProfile->hotelComments()->whereHas('user')->count() }}</span>
                    </button>
                </div>

                <!-- Description Card -->
                @if($business->businessProfile && $business->businessProfile->description)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">About This Hotel</h3>
                        <p class="text-blue-700 text-sm">{{ $business->businessProfile->description }}</p>
                    </div>
                @elseif($business->description)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">About This Hotel</h3>
                        <p class="text-blue-700 text-sm">{{ $business->description }}</p>
                    </div>
                @endif

            </div>
        </div>

        <!-- Right Content Area -->
        <div class="lg:col-span-3">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
                <!-- Total Rooms Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Rooms</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $rooms->count() }}</p>
                            <p class="text-blue-600 text-sm">{{ $rooms->where('is_available', true)->count() }} available</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-bed text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hotel Rooms Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Hotel Rooms</h2>
                </div>

                @if($rooms && $rooms->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">ROOM</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">TYPE</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">PRICE</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">AVAILABILITY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rooms as $room)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                    @if($room->images && $room->images->count() > 0)
                                                        <img src="{{ Storage::url($room->images->first()->image_path) }}" 
                                                             alt="{{ $room->name }}" 
                                                             class="w-full h-full object-cover rounded-lg">
                                                    @else
                                                        <i class="fas fa-bed text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $room->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $room->capacity ?? 2 }} guests</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-sm text-gray-600">N/A</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-gray-800">₱{{ number_format($room->price_per_night, 2) }}</span>
                                            <span class="text-sm text-gray-500">/night</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $room->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $room->is_available ? 'Available' : 'Not Available' }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-bed text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">No rooms available yet</p>
                    </div>
                @endif
            </div>

            <!-- Gallery Section -->
            @if($business->businessProfile && $business->businessProfile->galleries && $business->businessProfile->galleries->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6">
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
                        <div class="text-sm text-gray-600 mb-2">{{ $business->businessProfile->hotelComments()->whereHas('user')->count() ?? 0 }} comments</div>
                    </div>
                </div>

                <!-- Add Comment Form -->
                @auth
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Add a Comment</h3>
                        <form id="hotelCommentFormInline">
                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ $business->businessProfile->id }}">
                            <div class="mb-4">
                                <textarea name="comment" id="hotelCommentTextInline" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Share your experience about this hotel..."></textarea>
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
                @if($business->businessProfile->hotelComments && $business->businessProfile->hotelComments()->whereHas('user')->count() > 0)
                    <div class="space-y-4" id="hotelCommentsListInline">
                        @foreach($business->businessProfile->hotelComments->take(5) as $comment)
                            @if($comment->user)
                            <div class="border-b border-gray-200 pb-4" id="hotel-comment-inline-{{ $comment->id }}">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                                        @if($comment->user && $comment->user->profile && $comment->user->profile->profile_picture)
                                            <img src="{{ Storage::url($comment->user->profile->profile_picture) }}" 
                                                 alt="{{ $comment->user->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-blue-500 flex items-center justify-center">
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
                                                    <button onclick="deleteHotelCommentInline({{ $comment->id }})" 
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
                    <div class="text-center py-8 text-gray-500" id="noHotelCommentsMessageInline">
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
        <img id="modalImage" src="" alt="Hotel Image" class="max-w-full max-h-full object-contain">
    </div>
</div>

<!-- Hotel Rating Modal -->
<div id="hotelRatingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Rate This Hotel</h3>
                <button onclick="closeHotelRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="hotelRatingForm">
                @csrf
                <input type="hidden" id="ratingHotelId" name="hotel_id">
                
                <!-- Star Rating -->
                <div class="flex justify-center space-x-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                class="hotel-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors"
                                data-rating="{{ $i }}">
                            <i class="fas fa-star"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="hotelRatingValue" required>
                
                <!-- Comment -->
                <div class="mb-4">
                    <textarea name="comment" 
                              placeholder="Share your experience with this hotel..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="4"></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeHotelRatingModal()"
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

<!-- Hotel Comment Modal -->
<div id="hotelCommentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Hotel Comments</h3>
                <button onclick="closeHotelCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto max-h-96 p-6">
            <!-- Comments will be loaded here -->
            <div id="hotelCommentsList">
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
                <form id="hotelCommentForm">
                    @csrf
                    <input type="hidden" id="commentHotelId" name="hotel_id">
                    
                    <div class="mb-4">
                        <textarea name="comment" id="hotelCommentText" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your thoughts about this hotel..."
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
// Hotel Like Toggle
function toggleHotelLike(hotelId) {
    fetch(`/hotels/${hotelId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.getElementById(`hotelLikeBtn-${hotelId}`);
            const likeCount = document.getElementById(`hotelLikeCount-${hotelId}`);
            
            if (data.liked) {
                likeBtn.classList.remove('text-gray-600', 'hover:text-red-500');
                likeBtn.classList.add('text-red-600');
                likeBtn.querySelector('svg').setAttribute('fill', 'currentColor');
            } else {
                likeBtn.classList.remove('text-red-600');
                likeBtn.classList.add('text-gray-600', 'hover:text-red-500');
                likeBtn.querySelector('svg').setAttribute('fill', 'none');
            }
            likeCount.textContent = data.like_count;
        }
    })
    .catch(error => console.error('Error:', error));
}

// Hotel Rating Modal
function openHotelRatingModal(hotelId, currentRating = 0) {
    document.getElementById('hotelRatingModal').classList.remove('hidden');
    document.getElementById('ratingHotelId').value = hotelId;
    document.getElementById('hotelRatingValue').value = currentRating;
    
    // Set existing rating stars
    const stars = document.querySelectorAll('.hotel-star');
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

function closeHotelRatingModal() {
    document.getElementById('hotelRatingModal').classList.add('hidden');
}

// Hotel Comment Modal
function openHotelCommentModal(hotelId) {
    document.getElementById('hotelCommentModal').classList.remove('hidden');
    document.getElementById('commentHotelId').value = hotelId;
    document.getElementById('hotelCommentForm').reset();
    loadHotelComments(hotelId);
}

function closeHotelCommentModal() {
    document.getElementById('hotelCommentModal').classList.add('hidden');
}

function loadHotelComments(hotelId) {
    fetch(`/hotels/${hotelId}/comments`)
        .then(response => response.json())
        .then(data => {
            const commentsList = document.getElementById('hotelCommentsList');
            if (data.comments && data.comments.length > 0) {
                commentsList.innerHTML = data.comments.map(comment => `
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            ${comment.user.profile_picture || comment.user.profile_avatar ? 
                                `<img src="/storage/${comment.user.profile_picture || comment.user.profile_avatar}" alt="${comment.user.name}" class="w-8 h-8 rounded-full object-cover mr-3">` :
                                `<div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                    ${comment.user.name.charAt(0).toUpperCase()}
                                </div>`
                            }
                            <div>
                                <p class="font-medium text-gray-900">${comment.user.name}</p>
                                <p class="text-xs text-gray-500">${new Date(comment.created_at).toLocaleDateString()}</p>
                            </div>
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
            document.getElementById('hotelCommentsList').innerHTML = '<p class="text-red-500 text-center py-4">Error loading comments</p>';
        });
}

// Image modal functionality
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Hotel Star Rating
    const hotelStars = document.querySelectorAll('.hotel-star');
    hotelStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            document.getElementById('hotelRatingValue').value = rating;
            
            hotelStars.forEach((s, index) => {
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

    // Hotel Rating Form
    const hotelRatingForm = document.getElementById('hotelRatingForm');
    if (hotelRatingForm) {
        hotelRatingForm.addEventListener('submit', function(e) {
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
                    // Update the rating display without full page reload
                    updateHotelRatingDisplay(data.average_rating, data.total_ratings);
                    // Update rating button to show yellow stars
                    updateHotelRatingButton(data.user_rating);
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

    // Hotel Comment Form
    const hotelCommentForm = document.getElementById('hotelCommentForm');
    if (hotelCommentForm) {
        hotelCommentForm.addEventListener('submit', function(e) {
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
                    document.getElementById('hotelCommentText').value = '';
                    loadHotelComments(hotelId);
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

    // Update hotel rating display function
    function updateHotelRatingDisplay(averageRating, totalRatings) {
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
    }

    // Update hotel rating button to show yellow stars after rating
    function updateHotelRatingButton(userRating) {
        const ratingButton = document.querySelector('[onclick*="openHotelRatingModal"]');
        if (ratingButton) {
            const starIcon = ratingButton.querySelector('svg');
            if (starIcon && userRating > 0) {
                starIcon.setAttribute('fill', 'currentColor');
                ratingButton.classList.remove('text-gray-600', 'hover:text-yellow-500');
                ratingButton.classList.add('text-yellow-500');
            }
        }
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

    // Hotel comment functionality (inline form)
    const hotelCommentFormInline = document.getElementById('hotelCommentFormInline');
    if (hotelCommentFormInline) {
        hotelCommentFormInline.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const hotelId = formData.get('hotel_id');
            
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
                    document.getElementById('hotelCommentTextInline').value = '';
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

    // Hotel comment functionality (modal form)
    const hotelCommentModalForm = document.getElementById('hotelCommentForm');
    if (hotelCommentModalForm) {
        hotelCommentModalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const hotelId = formData.get('hotel_id');
            
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
                    document.getElementById('hotelCommentText').value = '';
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

// Standardized interaction functions
function toggleLike(type, id) {
    let route = '';
    switch(type) {
        case 'hotel':
            route = `/hotels/${id}/like`;
            break;
        default:
            console.error('Unknown type:', type);
            return;
    }

    fetch(route, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.querySelector(`[onclick*="toggleLike('${type}', ${id})"]`);
            if (button) {
                const countSpan = button.querySelector('.like-count');
                const heartIcon = button.querySelector('i');
                
                if (countSpan) {
                    countSpan.textContent = data.like_count || data.likes_count || 0;
                }
                
                if (heartIcon) {
                    if (data.liked) {
                        heartIcon.classList.remove('far', 'text-gray-400');
                        heartIcon.classList.add('fas', 'text-red-500');
                        button.setAttribute('data-liked', 'true');
                    } else {
                        heartIcon.classList.remove('fas', 'text-red-500');
                        heartIcon.classList.add('far', 'text-gray-400');
                        button.setAttribute('data-liked', 'false');
                    }
                }
            }
        }
    })
    .catch(error => {
        console.error('Error toggling like:', error);
    });
}

function showRating(type, id) {
    const modal = createRatingModal(type, id);
    document.body.appendChild(modal);
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function showComments(type, id) {
    const modal = createCommentsModal(type, id);
    document.body.appendChild(modal);
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    loadComments(type, id);
}

function createRatingModal(type, id) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.id = `rating-modal-${type}-${id}`;
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Rate this ${type}</h3>
                <button onclick="closeRatingModal('${type}', ${id})" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="rating-form-${type}-${id}" onsubmit="submitRating(event, '${type}', ${id})">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                    <div class="flex space-x-1" id="star-rating-${type}-${id}">
                        ${[1,2,3,4,5].map(star => `
                            <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400 transition-colors" 
                                    data-rating="${star}" onclick="setRating('${type}', ${id}, ${star})">
                                <i class="fas fa-star"></i>
                            </button>
                        `).join('')}
                    </div>
                    <input type="hidden" name="rating" id="rating-input-${type}-${id}" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comment (Optional)</label>
                    <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              placeholder="Share your experience..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors">
                        Submit Rating
                    </button>
                    <button type="button" onclick="closeRatingModal('${type}', ${id})" 
                            class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    `;
    
    return modal;
}

function createCommentsModal(type, id) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.id = `comments-modal-${type}-${id}`;
    
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4 max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Comments</h3>
                <button onclick="closeCommentsModal('${type}', ${id})" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="flex-1 overflow-y-auto mb-4" id="comments-list-${type}-${id}">
                <div class="text-center py-4 text-gray-500">Loading comments...</div>
            </div>
            
            <form id="comment-form-${type}-${id}" onsubmit="submitComment(event, '${type}', ${id})" class="border-t pt-4">
                <div class="flex space-x-3">
                    <textarea name="comment" rows="2" class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" 
                              placeholder="Write a comment..." required></textarea>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition-colors">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    `;
    
    return modal;
}

function setRating(type, id, rating) {
    const stars = document.querySelectorAll(`#star-rating-${type}-${id} .rating-star`);
    stars.forEach((star, index) => {
        const starIcon = star.querySelector('i');
        if (index < rating) {
            starIcon.classList.remove('text-gray-300');
            starIcon.classList.add('text-yellow-400');
        } else {
            starIcon.classList.remove('text-yellow-400');
            starIcon.classList.add('text-gray-300');
        }
    });
    document.getElementById(`rating-input-${type}-${id}`).value = rating;
}

function closeRatingModal(type, id) {
    const modal = document.getElementById(`rating-modal-${type}-${id}`);
    if (modal) {
        modal.remove();
        document.body.classList.remove('overflow-hidden');
    }
}

function closeCommentsModal(type, id) {
    const modal = document.getElementById(`comments-modal-${type}-${id}`);
    if (modal) {
        modal.remove();
        document.body.classList.remove('overflow-hidden');
    }
}

function submitRating(event, type, id) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    let route = `/hotels/${id}/rate`;
    
    fetch(route, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeRatingModal(type, id);
            location.reload();
        } else {
            alert(data.error || 'Error submitting rating');
        }
    })
    .catch(error => {
        console.error('Error submitting rating:', error);
        alert('Error submitting rating');
    });
}

function submitComment(event, type, id) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    let route = `/hotels/${id}/comment`;
    
    fetch(route, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            form.reset();
            loadComments(type, id);
            // Update comment count
            const button = document.querySelector(`[onclick*="showComments('${type}', ${id})"] span`);
            if (button) {
                const currentCount = parseInt(button.textContent) || 0;
                button.textContent = currentCount + 1;
            }
        } else {
            alert(data.error || 'Error submitting comment');
        }
    })
    .catch(error => {
        console.error('Error submitting comment:', error);
        alert('Error submitting comment');
    });
}

function loadComments(type, id) {
    let route = `/hotels/${id}/comments`;
    
    fetch(route, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const commentsList = document.getElementById(`comments-list-${type}-${id}`);
        if (data.comments && data.comments.length > 0) {
            commentsList.innerHTML = data.comments.map(comment => `
                <div class="mb-4 p-3 bg-gray-50 rounded-lg" id="comment-${comment.id}">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                            ${comment.user && comment.user.profile_picture ? 
                                `<img src="${comment.user.profile_picture}" alt="${comment.user.name}" class="w-full h-full object-cover">` :
                                `<div class="w-full h-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                    ${comment.user && comment.user.name ? comment.user.name.charAt(0).toUpperCase() : (comment.user_name ? comment.user_name.charAt(0).toUpperCase() : 'U')}
                                </div>`
                            }
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-sm">${comment.user ? comment.user.name : (comment.user_name || 'User')}</span>
                                    <span class="text-xs text-gray-500">${comment.created_at_human || comment.created_at}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    ${comment.can_delete ? `<button onclick="deleteComment(${comment.id}, '${type}', ${id})" class="text-gray-400 hover:text-red-500 text-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>` : ''}
                                </div>
                            </div>
                            <p class="text-sm text-gray-700">${comment.comment}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            commentsList.innerHTML = '<div class="text-center py-4 text-gray-500">No comments yet. Be the first to comment!</div>';
        }
    })
    .catch(error => {
        console.error('Error loading comments:', error);
    });
}

function deleteComment(commentId, type, contentId) {
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
                const button = document.querySelector(`[onclick*="showComments('${type}', ${contentId})"] span`);
                if (button) {
                    const currentCount = parseInt(button.textContent) || 0;
                    button.textContent = Math.max(0, currentCount - 1);
                }
            } else {
                alert(data.error || 'Error deleting comment');
            }
        })
        .catch(error => {
            console.error('Error deleting comment:', error);
            alert('Error deleting comment');
        });
    }
}

// Delete hotel comment function (for inline comments)
window.deleteHotelCommentInline = function(commentId) {
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
                const commentElement = document.getElementById(`hotel-comment-inline-${commentId}`);
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

// Delete hotel comment function (for modal comments)
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
                const commentElement = document.getElementById(`hotel-comment-${commentId}`);
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
