@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="pt-16">
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        {{ session('error') }}
    </div>
@endif

<div class="w-full">
    <!-- Hero Banner Section with Dynamic Background -->
    <div class="w-full h-64 relative bg-gray-200"
         id="cover-banner"
         @if($business && $business->cover_image)
             style="background-image: url('{{ Storage::url($business->cover_image) }}'); background-size: cover; background-position: center;"
         @endif>
         
        <!-- Cover Photo Upload Form -->
        <div class="absolute top-4 right-4 z-40">
            <form action="{{ route('business.updateCover') }}" method="POST" enctype="multipart/form-data" class="inline">
                @csrf
                <label class="bg-white bg-opacity-90 text-gray-700 px-4 py-2 rounded-lg hover:bg-opacity-100 transition-all duration-200 flex items-center text-sm font-medium cursor-pointer shadow-lg">
                    <i class="fas fa-camera mr-2"></i> Edit Cover Image
                    <input type="file" name="cover_image" accept="image/*" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="w-full max-w-7xl mx-auto px-4 -mt-12 md:-mt-16 lg:-mt-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side - Profile and Hotel Info -->
            <div class="lg:col-span-1">
                <!-- Profile Photo Section -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 relative">
                    <div class="text-center">
                        <div class="relative w-32 h-32 mx-auto">
                            <div class="w-full h-full border-4 border-white rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-all duration-200 shadow-lg overflow-hidden"
                                 onclick="document.getElementById('profile-photo').click()">
                                @if($business->profile_avatar)
                                    <img src="{{ Storage::url($business->profile_avatar) }}?v={{ time() }}" 
                                         alt="{{ $business->business_name }}" 
                                         class="w-full h-full object-cover profile-photo">
                                @else
                                    <div class="w-full h-full bg-blue-600 flex items-center justify-center">
                                        <i class="fas fa-hotel text-white text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-2 shadow-md">
                                <i class="fas fa-camera text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <input type="file" id="profile-photo" class="hidden" accept="image/*" onchange="uploadProfilePhoto(this)">

                        <!-- Hotel Name -->
                        <h1 class="text-3xl font-bold text-gray-800 mt-4 mb-3">
                            {{ $business->business_name }}
                        </h1>

                        <!-- Availability Status -->
                        <div class="mb-4">
                            <div class="inline-flex items-center px-3 py-1 rounded-full 
                                {{ $business && $business->is_published ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-2 h-2 rounded-full mr-2 
                                    {{ $business && $business->is_published ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                <span class="text-sm font-medium">
                                    {{ $business && $business->is_published ? 'Available Now' : 'Not Available' }}
                                </span>
                            </div>
                        </div>

                        <!-- Hotel Info -->
                        <div class="space-y-3 text-left">
                            @if($business && $business->address)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-map-marker-alt w-5 text-gray-400 mr-3"></i>
                                    <span class="text-sm">{{ $business->address }}</span>
                                </div>
                            @endif
                            @if($business && $business->contact_number)
                                <div class="flex items-center text-gray-600 mb-1">
                                    <i class="fas fa-phone w-5 text-gray-400 mr-3"></i>
                                    <span class="text-sm">{{ $business->contact_number }}</span>
                                </div>
                                <!-- Star Rating -->
                                <div class="flex items-center text-yellow-400 mb-2 ml-8">
                                    @php
                                        $rating = $business->average_rating ?? 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = $rating - $fullStars >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    @endphp
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                    @if($hasHalfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @endif
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <i class="far fa-star"></i>
                                    @endfor
                                    <span class="text-gray-600 text-sm ml-2">({{ number_format($rating, 1) }})</span>
                                </div>
                            @endif
                        </div>

                        <!-- Publish/Unpublish Buttons -->
                        <div class="mt-6 flex flex-wrap gap-2 justify-center">
                            @if($business && $business->is_published)
                                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">Published</span>
                                <form action="{{ route('business.unpublish') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Unpublish
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('business.publish') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm font-medium">
                                        Publish Hotel
                                    </button>
                                </form>
                                <span class="text-gray-500 text-sm mt-2">Your hotel will be reviewed before going live</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Hotel Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <i class="fas fa-eye text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Profile Views</p>
                                <p class="font-semibold">0</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                <i class="fas fa-star text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Average Rating</p>
                                <div class="flex items-center">
                                    @php
                                        $avgRating = $business->average_rating ?? 0;
                                        $fullStars = floor($avgRating);
                                        $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                    @endphp
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    @endfor
                                    @if($hasHalfStar)
                                        <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                    @endif
                                    @for($i = 0; $i < (5 - $fullStars - ($hasHalfStar ? 1 : 0)); $i++)
                                        <i class="far fa-star text-yellow-400 text-sm"></i>
                                    @endfor
                                    <span class="ml-1 text-sm text-gray-600">({{ number_format($avgRating, 1) }})</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Side - Dashboard Content -->
            <div class="lg:col-span-2">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Rooms</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalRooms }}</p>
                                <p class="text-xs text-gray-500">Rooms available</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-door-open text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Available Rooms</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $availableRooms }}</p>
                                <p class="text-xs text-gray-500">Available rooms</p>
                            </div>
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-bed text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rooms Management -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Rooms</h3>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="openModal('addRoomModal')" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                <i class="fas fa-plus mr-1"></i> Add Room
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($rooms as $room)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($room->images->first())
                                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($room->images->first()->image_path) }}" alt="{{ $room->name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $room->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $room->capacity }} guests</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $room->roomType->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₱{{ number_format($room->price_per_night, 2) }}<span class="text-gray-500 text-xs">/night</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($room->is_available)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Available
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Booked
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" onclick="editRoom({{ $room->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <a href="#" onclick="deleteRoom({{ $room->id }})" class="text-red-600 hover:text-red-900">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No rooms added yet. Add your first room to get started.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Gallery Management -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Gallery</h3>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="openModal('galleryModal')" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                                <i class="fas fa-plus mr-1"></i> Add Photos
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($galleries ?? [] as $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image->image_path) }}" alt="Gallery Image" class="w-full h-32 object-cover rounded-lg hover:shadow-md transition-shadow cursor-pointer"
                                     onclick="openImageModal('{{ Storage::url($image->image_path) }}')">
                                <button type="button" onclick="deleteGalleryImage({{ $image->id }})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                <i class="fas fa-images text-4xl mb-4"></i>
                                <p class="text-gray-500">No gallery images yet. Add some photos to showcase your hotel!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- Add Promotion Modal -->
<div id="addPromotionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center pt-20">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Add New Promotion</h3>
                <button type="button" onclick="closeModal('addPromotionModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('business.promotions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="promotion_title" class="block text-sm font-medium text-gray-700">Promotion Title</label>
                            <input type="text" name="title" id="promotion_title" required
                                   placeholder="e.g., Summer Special 50% Off"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="promotion_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="promotion_description" rows="3" required
                                      placeholder="Describe your promotion details, terms and conditions..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        
                        <div>
                            <label for="promotion_image" class="block text-sm font-medium text-gray-700">Promotion Image</label>
                            <input type="file" name="image" id="promotion_image" accept="image/*" required
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">Upload an attractive image for your promotion</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeModal('addPromotionModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Create Promotion
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Room Modal -->
<div id="addRoomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center pt-20">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="roomModalTitle" class="text-xl font-semibold text-gray-900">Add New Room</h3>
                <button type="button" onclick="closeModal('addRoomModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="roomForm" action="{{ route('business.rooms.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="room_name" class="block text-sm font-medium text-gray-700">Room Name</label>
                            <input type="text" name="name" id="room_name" required
                                   placeholder="e.g., Deluxe Ocean View"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="room_type" class="block text-sm font-medium text-gray-700">Room Type</label>
                            <select name="room_type" id="room_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select room type</option>
                                <option value="standard">Standard Room</option>
                                <option value="deluxe">Deluxe Room</option>
                                <option value="suite">Suite</option>
                                <option value="family">Family Room</option>
                                <option value="presidential">Presidential Suite</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="room_price" class="block text-sm font-medium text-gray-700">Price per Night (₱)</label>
                            <input type="number" name="price_per_night" id="room_price" step="0.01" min="0" required
                                   placeholder="2500.00"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="room_capacity" class="block text-sm font-medium text-gray-700">Guest Capacity</label>
                            <input type="number" name="capacity" id="room_capacity" min="1" max="20" required
                                   placeholder="2"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div class="col-span-2">
                            <label for="room_description" class="block text-sm font-medium text-gray-700">Room Description</label>
                            <textarea name="description" id="room_description" rows="3" required
                                      placeholder="Describe the room features, amenities, and what makes it special..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        
                        <div class="col-span-2">
                            <label for="room_amenities" class="block text-sm font-medium text-gray-700">Amenities</label>
                            <input type="text" name="amenities" id="room_amenities"
                                   placeholder="e.g., WiFi, Air Conditioning, TV, Mini Bar, Ocean View"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">Separate amenities with commas</p>
                        </div>
                        
                        <div class="col-span-2">
                            <label for="room_images" class="block text-sm font-medium text-gray-700">Room Images</label>
                            <input type="file" name="images[]" id="room_images" accept="image/*" multiple onchange="previewRoomImages(this)"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div id="imagePreviews" class="mt-2 grid grid-cols-4 gap-2"></div>
                            <p class="mt-1 text-sm text-gray-500">Upload multiple images to showcase your room</p>
                        </div>
                        
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeModal('addRoomModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" id="roomSubmitBtn" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add Room
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center pt-20">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Add Photos to Gallery</h3>
                <button type="button" onclick="closeModal('galleryModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('business.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Upload Photos</label>
                    <div class="mt-2 flex items-center">
                        <input type="file" name="images[]" id="galleryImages" multiple accept="image/*" class="hidden" onchange="previewGalleryImages(this)">
                        <label for="galleryImages" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-upload mr-2"></i> Select Files
                        </label>
                        <span id="fileNames" class="ml-4 text-sm text-gray-500">No files selected</span>
                    </div>
                    <div id="galleryPreviews" class="mt-4 grid grid-cols-3 gap-2"></div>
                    <p class="mt-2 text-xs text-gray-500">Upload up to 10 images at once (JPG, PNG, max 5MB each)</p>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeModal('galleryModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Upload Photos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal(modalId) {
        // Reset modal to add mode when opening for new room
        if (modalId === 'addRoomModal') {
            resetRoomModal();
        }
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function resetRoomModal() {
        // Reset form action and method
        document.getElementById('roomForm').action = '{{ route("business.rooms.store") }}';
        
        // Remove method input if exists
        const methodInput = document.getElementById('roomForm').querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }
        
        // Reset modal title and button text
        document.getElementById('roomModalTitle').textContent = 'Add New Room';
        document.getElementById('roomSubmitBtn').textContent = 'Add Room';
        
        // Clear form fields
        document.getElementById('room_name').value = '';
        document.getElementById('room_type').value = '';
        document.getElementById('room_price').value = '';
        document.getElementById('room_capacity').value = '';
        document.getElementById('room_description').value = '';
        document.getElementById('room_amenities').value = '';
        document.getElementById('room_images').value = '';
        
        // Clear image previews
        const previewContainer = document.getElementById('imagePreviews');
        if (previewContainer) {
            previewContainer.innerHTML = '';
        }
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    function previewRoomImages(input) {
        const previewContainer = document.getElementById('imagePreviews');
        previewContainer.innerHTML = '';
        
        if (input.files.length > 0) {
            for (let i = 0; i < input.files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'relative group';
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="h-16 w-16 object-cover rounded-md">
                        <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100" onclick="this.parentElement.remove(); updateFileInput(input, ${i})">
                            ×
                        </button>
                    `;
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(input.files[i]);
            }
        }
    }
    
    function previewGalleryImages(input) {
        const fileNames = [];
        const previewContainer = document.getElementById('galleryPreviews');
        previewContainer.innerHTML = '';
        
        if (input.files.length > 0) {
            for (let i = 0; i < Math.min(input.files.length, 10); i++) {
                fileNames.push(input.files[i].name);
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.createElement('div');
                    preview.className = 'relative group';
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="h-24 w-full object-cover rounded-md">
                    `;
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(input.files[i]);
            }
            
            document.getElementById('fileNames').textContent = `${fileNames.length} file(s) selected`;
        } else {
            document.getElementById('fileNames').textContent = 'No files selected';
        }
    }
    
    function updateFileInput(input, indexToRemove) {
        const dt = new DataTransfer();
        const { files } = input;
        
        for (let i = 0; i < files.length; i++) {
            if (i !== indexToRemove) {
                dt.items.add(files[i]);
            }
        }
        
        input.files = dt.files;
    }
    
    function editRoom(roomId) {
        // Fetch room data and populate the form
        fetch(`/business/rooms/${roomId}/edit`)
            .then(response => response.json())
            .then(room => {
                // Populate form fields
                document.getElementById('roomForm').action = `/business/rooms/${roomId}`;
                
                // Add method spoofing for PUT request
                let methodInput = document.getElementById('roomForm').querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    document.getElementById('roomForm').appendChild(methodInput);
                } else {
                    methodInput.value = 'PUT';
                }
                
                document.getElementById('room_name').value = room.name;
                document.getElementById('room_type').value = room.room_type || 'standard';
                document.getElementById('room_price').value = room.price_per_night;
                document.getElementById('room_capacity').value = room.capacity;
                document.getElementById('room_description').value = room.description;
                
                // Update modal title and button text for editing
                document.getElementById('roomModalTitle').textContent = 'Edit Room';
                document.getElementById('roomSubmitBtn').textContent = 'Update Room';
                
                // Show existing images
                const previewContainer = document.getElementById('imagePreviews');
                if (previewContainer) {
                    previewContainer.innerHTML = '';
                    
                    if (room.images && room.images.length > 0) {
                        room.images.forEach(image => {
                            const preview = document.createElement('div');
                            preview.className = 'relative group';
                            preview.innerHTML = `
                                <img src="${image.path}" class="h-16 w-16 object-cover rounded-md">
                                <input type="hidden" name="existing_images[]" value="${image.id}">
                                <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100" onclick="this.parentElement.remove();">
                                    ×
                                </button>
                            `;
                            previewContainer.appendChild(preview);
                        });
                    }
                }
                
                // Open the modal without resetting
                document.getElementById('addRoomModal').classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            })
            .catch(error => console.error('Error fetching room data:', error));
    }
    
    function deleteRoom(roomId) {
        if (confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
            fetch(`/business/rooms/${roomId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Failed to delete room. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error deleting room:', error);
                alert('An error occurred while deleting the room.');
            });
        }
    }
    
    function deleteImage(imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`/business/gallery/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Failed to delete image. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error deleting image:', error);
                alert('An error occurred while deleting the image.');
            });
        }
    }
    
    function uploadProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const formData = new FormData();
            formData.append('profile_avatar', input.files[0]);

            fetch('{{ route("business.updateAvatar") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the profile picture in the navigation bar and dashboard
                    const navProfileImg = document.querySelector('.user-profile-image');
                    const profileImg = document.querySelector('.profile-photo');
                    const timestamp = new Date().getTime();
                    const newSrc = data.url + '?t=' + timestamp;
                    
                    if (navProfileImg) navProfileImg.src = newSrc;
                    if (profileImg) profileImg.src = newSrc;
                    
                    // Show success message
                    alert('Profile picture updated successfully!');
                    
                    // Reload page to show updated image
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    throw new Error(data.message || 'Failed to update profile picture');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating profile picture: ' + (error.message || 'Unknown error occurred'));
            });
        }
    }

    // Delete gallery image function
    function deleteGalleryImage(imageId) {
        if (confirm('Are you sure you want to delete this image?')) {
            fetch(`/business/gallery/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting image: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting image');
            });
        }
    }

    // Gallery preview function
    function previewGalleryImages(input) {
        const previewContainer = document.getElementById('galleryPreviews');
        const fileNames = document.getElementById('fileNames');
        
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            fileNames.textContent = `${input.files.length} file(s) selected`;
            
            Array.from(input.files).forEach((file, index) => {
                if (index < 10) { // Limit to 10 previews
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-20 object-cover rounded-lg">`;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            fileNames.textContent = 'No files selected';
        }
    }
    
</script>
@endpush
