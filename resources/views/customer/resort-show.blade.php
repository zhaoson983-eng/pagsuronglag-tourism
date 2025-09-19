@extends('layouts.app')

@section('title', $business->businessProfile->business_name . ' - Resort - Pagsurong Lagonoy')

@section('content')
<!-- Hero Banner Section -->
<div class="w-full h-80 relative bg-cover bg-center overflow-hidden"
     style="background-image: url('{!! ($business->businessProfile->cover_image) ? Storage::url($business->businessProfile->cover_image) : asset('images/placeholder-cover.jpg') !!}')">
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
</div>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 -mt-20 relative z-10 pb-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Sidebar - Resort Profile -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <!-- Resort Avatar -->
                <div class="text-center mb-6">
                    <div class="w-32 h-32 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4 relative">
                        @if($business->businessProfile->profile_avatar)
                            <img src="{{ Storage::url($business->businessProfile->profile_avatar) }}" alt="{{ $business->businessProfile->business_name }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <i class="fas fa-umbrella-beach text-4xl text-blue-500"></i>
                        @endif
                        <div class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $business->businessProfile->business_name }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-4">
                        <i class="fas fa-check-circle mr-1"></i>
                        Available Now
                    </span>
                </div>

                <!-- Contact Info -->
                <div class="space-y-3 mb-6">
                    @if($business->businessProfile->location)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt w-5 mr-3"></i>
                            <span class="text-sm">{{ $business->businessProfile->location }}</span>
                        </div>
                    @endif
                    @if($business->businessProfile->contact_number)
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-phone w-5 mr-3"></i>
                            <span class="text-sm">{{ $business->businessProfile->contact_number }}</span>
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
                            onclick="toggleLike('resort', {{ $business->businessProfile->id }})" 
                            data-liked="{{ auth()->check() && $business->businessProfile->resortLikes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}">
                        <i class="{{ auth()->check() && $business->businessProfile->resortLikes()->where('user_id', auth()->id())->exists() ? 'fas' : 'far' }} fa-heart {{ auth()->check() && $business->businessProfile->resortLikes()->where('user_id', auth()->id())->exists() ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"></i>
                        <span class="text-sm like-count">{{ $business->businessProfile->resortLikes()->count() }}</span>
                    </button>
                    <button class="flex items-center space-x-1 transition-colors" onclick="showRating('resort', {{ $business->businessProfile->id }})">
                        <i class="fas fa-star text-yellow-400"></i>
                        <span class="text-sm text-gray-600">{{ number_format($business->businessProfile->average_rating ?? 0, 1) }} ({{ $business->businessProfile->total_ratings ?? 0 }})</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-600 transition-colors" onclick="showComments('resort', {{ $business->businessProfile->id }})">
                        <i class="fas fa-comment"></i>
                        <span class="text-sm">{{ $business->businessProfile->resortComments()->whereHas('user')->count() }}</span>
                    </button>
                </div>

                <!-- Description Card -->
                @if($business->businessProfile && $business->businessProfile->description)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">About This Resort</h3>
                        <p class="text-blue-700 text-sm">{{ $business->businessProfile->description }}</p>
                    </div>
                @elseif($business->description)
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">About This Resort</h3>
                        <p class="text-blue-700 text-sm">{{ $business->description }}</p>
                    </div>
                @endif

            </div>
        </div>

        <!-- Right Content Area -->
        <div class="lg:col-span-3">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
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

                <!-- Total Cottages Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-gray-500 text-sm font-medium">Total Cottages</h3>
                            <p class="text-3xl font-bold text-gray-800">{{ $cottages->count() }}</p>
                            <p class="text-green-600 text-sm">{{ $cottages->where('is_available', true)->count() }} available</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-home text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resort Rooms Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Resort Rooms</h2>
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
                                                    @if($room->description)
                                                        <p class="text-xs text-gray-400 mt-1">{{ Str::limit($room->description, 50) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-sm text-gray-600">{{ $room->room_type ?? 'Standard' }}</span>
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

            <!-- Resort Cottages Table -->
            @if($cottages && $cottages->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Resort Cottages</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">COTTAGE</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">TYPE</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">PRICE</th>
                                    <th class="text-left py-3 px-4 font-medium text-gray-600 text-sm">AVAILABILITY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cottages as $cottage)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                    @if($cottage->galleries && $cottage->galleries->count() > 0)
                                                        <img src="{{ Storage::url($cottage->galleries->first()->image_path) }}" 
                                                             alt="{{ $cottage->cottage_name }}" 
                                                             class="w-full h-full object-cover rounded-lg">
                                                    @else
                                                        <i class="fas fa-home text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">{{ $cottage->cottage_name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $cottage->capacity ?? 4 }} guests</p>
                                                    @if($cottage->description)
                                                        <p class="text-xs text-gray-400 mt-1">{{ Str::limit($cottage->description, 50) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="text-sm text-gray-600">{{ $cottage->cottage_type ?? 'Standard' }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-semibold text-gray-800">₱{{ number_format($cottage->price_per_night, 2) }}</span>
                                            <span class="text-sm text-gray-500">/night</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($cottage->is_available)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Available
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Not Available
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Gallery Section -->
            @if($business->businessProfile->gallery && $business->businessProfile->gallery->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($business->businessProfile->gallery as $image)
                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                                 onclick="openImageModal('{{ Storage::url($image->image_path) }}')">
                                <img src="{{ Storage::url($image->image_path) }}" 
                                     alt="Resort Gallery" 
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
                        <form id="resortCommentForm">
                            @csrf
                            <input type="hidden" name="resort_id" value="{{ $business->businessProfile->id }}">
                            <div class="mb-4">
                                <textarea name="comment" id="resortCommentText" rows="3" 
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
                    <div class="space-y-4" id="resortCommentsList">
                        @foreach($business->businessProfile->resortComments->take(5) as $comment)
                            @if($comment->user)
                            <div class="border-b border-gray-200 pb-4" id="resort-comment-{{ $comment->id }}">
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
                                                    <button onclick="deleteResortComment({{ $comment->id }})" 
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
                    <div class="text-center py-8 text-gray-500" id="noResortCommentsMessage">
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
        <img id="modalImage" src="" alt="Resort Image" class="max-w-full max-h-full object-contain">
    </div>
</div>

<!-- Resort Rating Modal -->
<div id="resortRatingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Rate This Resort</h3>
                <button onclick="closeResortRatingModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form id="resortRatingForm">
                @csrf
                <input type="hidden" id="ratingResortId" name="resort_id">
                
                <!-- Star Rating -->
                <div class="flex justify-center space-x-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                class="resort-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition-colors"
                                data-rating="{{ $i }}">
                            <i class="fas fa-star"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="resortRatingValue" required>
                
                <!-- Comment -->
                <div class="mb-4">
                    <textarea name="comment" 
                              placeholder="Share your experience with this resort..." 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              rows="4"></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="closeResortRatingModal()"
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

<!-- Resort Comment Modal -->
<div id="resortCommentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Resort Comments</h3>
                <button onclick="closeResortCommentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto max-h-96 p-6">
            <!-- Comments will be loaded here -->
            <div id="resortCommentsList">
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
                <form id="resortCommentForm">
                    @csrf
                    <input type="hidden" id="commentResortId" name="resort_id">
                    
                    <div class="mb-4">
                        <textarea name="comment" id="resortCommentText" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Share your thoughts about this resort..."
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
// Resort Like Toggle
function toggleResortLike(resortId) {
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
            const likeBtn = document.getElementById(`resortLikeBtn-${resortId}`);
            const likeCount = document.getElementById(`resortLikeCount-${resortId}`);
            
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

// Resort Rating Modal
function openResortRatingModal(resortId, currentRating = 0) {
    document.getElementById('resortRatingModal').classList.remove('hidden');
    document.getElementById('ratingResortId').value = resortId;
    document.getElementById('resortRatingValue').value = currentRating;
    
    // Set existing rating stars
    const stars = document.querySelectorAll('.resort-star');
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

function closeResortRatingModal() {
    document.getElementById('resortRatingModal').classList.add('hidden');
}

// Resort Comment Modal
function openResortCommentModal(resortId) {
    document.getElementById('resortCommentModal').classList.remove('hidden');
    document.getElementById('commentResortId').value = resortId;
    document.getElementById('resortCommentForm').reset();
    loadResortComments(resortId);
}

function closeResortCommentModal() {
    document.getElementById('resortCommentModal').classList.add('hidden');
}

function loadResortComments(resortId) {
    fetch(`/resorts/${resortId}/comments`)
        .then(response => response.json())
        .then(data => {
            const commentsList = document.getElementById('resortCommentsList');
            if (data.comments && data.comments.length > 0) {
                commentsList.innerHTML = data.comments.map(comment => `
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            ${comment.user && comment.user.profile_picture ? 
                                `<img src="/storage/${comment.user.profile_picture}" alt="${comment.user.name}" class="w-8 h-8 rounded-full object-cover mr-3">` :
                                `<div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium mr-3">
                                    ${comment.user ? comment.user.name.charAt(0).toUpperCase() : 'U'}
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
            document.getElementById('resortCommentsList').innerHTML = '<p class="text-red-500 text-center py-4">Error loading comments</p>';
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
    // Resort Star Rating
    const resortStars = document.querySelectorAll('.resort-star');
    resortStars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            document.getElementById('resortRatingValue').value = rating;
            
            resortStars.forEach((s, index) => {
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

    // Resort Rating Form
    const resortRatingForm = document.getElementById('resortRatingForm');
    if (resortRatingForm) {
        resortRatingForm.addEventListener('submit', function(e) {
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
                    // Update the rating display without full page reload
                    updateResortRatingDisplay(data.average_rating, data.total_ratings);
                    // Update rating button to show yellow stars
                    updateResortRatingButton(data.user_rating);
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

    // Resort Comment Form
    const resortCommentForm = document.getElementById('resortCommentForm');
    if (resortCommentForm) {
        resortCommentForm.addEventListener('submit', function(e) {
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
                    document.getElementById('resortCommentText').value = '';
                    loadResortComments(resortId);
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

    // Update resort rating display function
    function updateResortRatingDisplay(averageRating, totalRatings) {
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
    }

    // Update resort rating button to show yellow stars after rating
    function updateResortRatingButton(userRating) {
        const ratingButton = document.querySelector('[onclick*="openResortRatingModal"]');
        if (ratingButton) {
            const starIcon = ratingButton.querySelector('svg');
            if (starIcon && userRating > 0) {
                starIcon.setAttribute('fill', 'currentColor');
                ratingButton.classList.remove('text-gray-600', 'hover:text-yellow-500');
                ratingButton.classList.add('text-yellow-500');
            }
        }
        
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
});

// Room rating functionality
document.querySelectorAll('.room-star-rating').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        const roomId = this.dataset.room;
        const form = this.closest('.room-rating-form');
        const ratingInput = form.querySelector('.room-rating-input');
        const submitButton = form.querySelector('button[type="submit"]');
        
        ratingInput.value = rating;
        submitButton.style.display = 'block';
        
        // Update star display for this room
        form.querySelectorAll('.room-star-rating').forEach((s, index) => {
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

// Cottage rating functionality
document.querySelectorAll('.cottage-star-rating').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        const cottageId = this.dataset.cottage;
        const form = this.closest('.cottage-rating-form');
        const ratingInput = form.querySelector('.cottage-rating-input');
        const submitButton = form.querySelector('button[type="submit"]');
        
        ratingInput.value = rating;
        submitButton.style.display = 'block';
        
        // Update star display for this cottage
        form.querySelectorAll('.cottage-star-rating').forEach((s, index) => {
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

// Image modal functionality
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Resort comment functionality
    const resortCommentForm = document.getElementById('resortCommentForm');
    if (resortCommentForm) {
        resortCommentForm.addEventListener('submit', function(e) {
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
                    document.getElementById('resortCommentText').value = '';
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
                const commentElement = document.getElementById(`resort-comment-${commentId}`);
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
