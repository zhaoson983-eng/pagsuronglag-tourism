@extends('layouts.app')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded mx-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded mx-4">
        {{ session('error') }}
    </div>
@endif

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
<div class="min-h-screen bg-gray-100">
    <div class="w-full max-w-7xl mx-auto px-4 -mt-12 md:-mt-16 lg:-mt-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side - Profile and Resort Info -->
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
                                        <i class="fas fa-umbrella-beach text-white text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-2 shadow-md">
                                <i class="fas fa-camera text-blue-600 text-sm"></i>
                            </div>
                        </div>
                        <input type="file" id="profile-photo" class="hidden" accept="image/*" onchange="uploadProfilePhoto(this)">

                        <!-- Resort Name -->
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

                        <!-- Resort Info -->
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
                                        Publish Resort
                                    </button>
                                </form>
                                <span class="text-gray-500 text-sm mt-2">Your resort will be reviewed before going live</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Resort Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <i class="fas fa-door-open text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Rooms</p>
                                <p class="font-semibold">{{ $totalRooms }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <i class="fas fa-home text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Cottages</p>
                                <p class="font-semibold">{{ $totalCottages }}</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                <i class="fas fa-images text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Gallery Photos</p>
                                <p class="font-semibold">{{ $galleryCount }}</p>
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
                                <p class="text-xs text-gray-500">{{ $availableRooms }} available</p>
                            </div>
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-door-open text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Cottages</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalCottages }}</p>
                                <p class="text-xs text-gray-500">{{ $availableCottages }} available</p>
                            </div>
                            <div class="p-3 bg-green-50 rounded-full">
                                <i class="fas fa-home text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rooms Management -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Resort Rooms</h3>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($rooms as $room)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($room->galleries->first())
                                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($room->galleries->first()->image_path) }}" alt="{{ $room->room_name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $room->room_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $room->capacity }} guests</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $room->room_type ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₱{{ number_format($room->price_per_night, 2) }}<span class="text-gray-500 text-xs">/night</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $room->description ?? 'No description available' }}
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

                <!-- Cottages Management -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Cottages</h3>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="openModal('addCottageModal')" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">
                                <i class="fas fa-plus mr-1"></i> Add Cottage
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cottage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($cottages as $cottage)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    @if($cottage->galleries->first())
                                                        <img class="h-10 w-10 rounded-md object-cover" src="{{ Storage::url($cottage->galleries->first()->image_path) }}" alt="{{ $cottage->cottage_name }}">
                                                    @else
                                                        <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $cottage->cottage_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $cottage->capacity }} guests</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $cottage->cottage_type ?? 'Standard' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₱{{ number_format($cottage->price_per_night, 2) }}<span class="text-gray-500 text-xs">/night</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cottage->description ?? 'No description available' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="#" onclick="editCottage({{ $cottage->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            <a href="#" onclick="deleteCottage({{ $cottage->id }})" class="text-red-600 hover:text-red-900">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No cottages added yet. Add your first cottage to get started.
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
                        @forelse($business->gallery ?? [] as $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image->path) }}" alt="Gallery Image" class="w-full h-32 object-cover rounded-lg hover:shadow-md transition-shadow cursor-pointer"
                                     onclick="openImageModal('{{ Storage::url($image->path) }}')">
                                <button type="button" onclick="deleteImage({{ $image->id }})" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                <i class="fas fa-images text-4xl mb-4"></i>
                                <p class="text-gray-500">No gallery images yet. Add some photos to showcase your resort!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
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
            
            <form id="roomForm" action="{{ route('business.rooms.store') }}" method="POST" enctype="multipart/form-data" onsubmit="submitRoomForm(event)">
                @csrf
                <input type="hidden" id="roomId" name="roomId" value="">
                <input type="hidden" id="_method" name="_method" value="POST">
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="room_number" class="block text-sm font-medium text-gray-700">Room Number</label>
                            <input type="text" name="room_name" id="room_number" required
                                   placeholder="e.g., 101, A1, Ocean View 1"
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
                            <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price per Night (₱)</label>
                            <input type="number" name="price_per_night" id="price_per_night" step="0.01" min="0" required
                                   placeholder="2500.00"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Guest Capacity</label>
                            <input type="number" name="capacity" id="capacity" min="1" max="20" required
                                   placeholder="2"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="size" class="block text-sm font-medium text-gray-700">Room Size (sqm)</label>
                            <input type="number" name="size" id="size" step="0.01" min="0"
                                   placeholder="25.5"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="beds" class="block text-sm font-medium text-gray-700">Bed Configuration</label>
                            <input type="text" name="beds" id="beds"
                                   placeholder="e.g., 1 King Bed, 2 Single Beds"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Room Description</label>
                            <textarea name="description" id="description" rows="3"
                                      placeholder="Describe the room features, amenities, and what makes it special..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        
                        <input type="hidden" name="is_available" id="is_available" value="1">
                        
                        <div class="col-span-2">
                            <label for="images" class="block text-sm font-medium text-gray-700">Room Images</label>
                            <input type="file" name="images[]" id="images" accept="image/*" multiple
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">Upload multiple images to showcase your room</p>
                            
                            <!-- Image Previews -->
                            <div id="imagePreviews" class="mt-3 grid grid-cols-3 gap-2"></div>
                            <input type="hidden" name="existing_images" id="existingImages" value="">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeModal('addRoomModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" id="roomModalButton" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add Room
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Cottage Modal -->
<div id="addCottageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center pt-20">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="cottageModalTitle" class="text-xl font-semibold text-gray-900">Add New Cottage</h3>
                <button type="button" onclick="closeModal('addCottageModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="cottageForm" action="{{ route('business.cottages.store') }}" method="POST" enctype="multipart/form-data" onsubmit="submitCottageForm(event)">
                @csrf
                <input type="hidden" id="cottageId" name="cottageId" value="">
                <input type="hidden" id="cottage_method" name="_method" value="POST">
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="cottage_name" class="block text-sm font-medium text-gray-700">Cottage Name</label>
                            <input type="text" name="cottage_name" id="cottage_name" required
                                   placeholder="e.g., Beachfront Cottage"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="cottage_type" class="block text-sm font-medium text-gray-700">Cottage Type</label>
                            <select name="cottage_type" id="cottage_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                <option value="">Select cottage type</option>
                                <option value="standard">Standard Cottage</option>
                                <option value="beachfront">Beachfront Cottage</option>
                                <option value="family">Family Cottage</option>
                                <option value="premium">Premium Cottage</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price per Night (₱)</label>
                            <input type="number" name="price_per_night" id="price_per_night" step="0.01" min="0" required
                                   placeholder="1500.00"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Guest Capacity</label>
                            <input type="number" name="capacity" id="capacity" min="1" max="50" required
                                   placeholder="8"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Cottage Description</label>
                            <textarea name="description" id="description" rows="3"
                                      placeholder="Describe the cottage features, amenities, and what makes it special..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                        </div>
                        
                        <input type="hidden" name="is_available" id="cottage_is_available" value="1">
                        
                        <div class="col-span-2">
                            <label for="cottage_images" class="block text-sm font-medium text-gray-700">Cottage Images</label>
                            <input type="file" name="images[]" id="cottage_images" accept="image/*" multiple
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                            <p class="mt-1 text-sm text-gray-500">Upload multiple images to showcase your cottage</p>
                            
                            <!-- Image Previews -->
                            <div id="cottageImagePreviews" class="mt-3 grid grid-cols-3 gap-2"></div>
                            <input type="hidden" name="existing_images" id="cottageExistingImages" value="">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeModal('addCottageModal')" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit" id="cottageModalButton" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Add Cottage
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
        document.getElementById(modalId).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        if (modalId === 'addRoomModal') {
            resetRoomForm();
        } else if (modalId === 'addCottageModal') {
            resetCottageForm();
        }
    }
    
    function resetRoomForm() {
        document.getElementById('roomModalTitle').textContent = 'Add New Room';
        document.getElementById('roomModalButton').textContent = 'Add Room';
        document.getElementById('roomId').value = '';
        document.getElementById('_method').value = 'POST';
        document.getElementById('roomForm').action = '{{ route("business.rooms.store") }}';
        document.getElementById('roomForm').reset();
        document.getElementById('imagePreviews').innerHTML = '';
        // Removed amenities field reference
    }
    
    function resetCottageForm() {
        document.getElementById('cottageModalTitle').textContent = 'Add New Cottage';
        document.getElementById('cottageModalButton').textContent = 'Add Cottage';
        document.getElementById('cottageId').value = '';
        document.getElementById('cottage_method').value = 'POST';
        document.getElementById('cottageForm').action = '{{ route("business.cottages.store") }}';
        document.getElementById('cottageForm').reset();
        document.getElementById('cottageImagePreviews').innerHTML = '';
        document.getElementById('cottageExistingImages').value = '';
    }
    
    function removeExistingImage(imageId, button) {
        button.parentElement.remove();
        
        // Update existing images list
        const existingImages = document.getElementById('existingImages');
        let imageIds = existingImages.value.split(',').filter(id => id && id != imageId);
        existingImages.value = imageIds.join(',');
    }
    
    function removeCottageExistingImage(imageId, button) {
        button.parentElement.remove();
        
        // Update existing images list
        const existingImages = document.getElementById('cottageExistingImages');
        let imageIds = existingImages.value.split(',').filter(id => id && id != imageId);
        existingImages.value = imageIds.join(',');
    }
    
    function uploadProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const formData = new FormData();
            formData.append('profile_avatar', input.files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            
            fetch('{{ route("business.updateProfileAvatar") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error uploading profile photo: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error uploading profile photo');
            });
        }
    }
    
    function previewGalleryImages(input) {
        const previewContainer = document.getElementById('galleryPreviews');
        const fileNames = document.getElementById('fileNames');
        
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            fileNames.textContent = `${input.files.length} file(s) selected`;
            
            Array.from(input.files).forEach((file, index) => {
                if (index < 9) { // Limit preview to 9 images
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-20 object-cover rounded-md">
                            <div class="absolute inset-0 bg-black bg-opacity-20 rounded-md"></div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            fileNames.textContent = 'No files selected';
        }
    }
    
    function editRoom(roomId) {
        fetch(`/business/rooms/${roomId}/edit`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === false) {
                alert('Error: ' + data.message);
                return;
            }
            
            // Reset form and update modal for edit mode
            resetRoomForm();
            document.getElementById('roomModalTitle').textContent = 'Edit Room';
            document.getElementById('roomModalButton').textContent = 'Update Room';
            document.getElementById('roomId').value = data.id;
            document.getElementById('_method').value = 'PUT';
            document.getElementById('roomForm').action = `/business/rooms/${data.id}`;
            
            // Populate form fields
            document.getElementById('room_number').value = data.room_name;
            document.getElementById('room_type').value = data.room_type;
            document.getElementById('price_per_night').value = data.price_per_night;
            document.getElementById('capacity').value = data.capacity;
            document.getElementById('description').value = data.description || '';
            // Removed amenities field reference
            
            // Handle existing images
            const imagePreviews = document.getElementById('imagePreviews');
            if (imagePreviews) {
                imagePreviews.innerHTML = '';
                let existingImageIds = [];
                if (data.images && data.images.length > 0) {
                    data.images.forEach(image => {
                        existingImageIds.push(image.id);
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${image.url}" class="w-full h-20 object-cover rounded-md">
                            <button type="button" onclick="removeExistingImage(${image.id}, this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        imagePreviews.appendChild(div);
                    });
                }
                document.getElementById('existingImages').value = existingImageIds.join(',');
            }
            
            openModal('addRoomModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading room data');
        });
    }
    
    function deleteRoom(roomId) {
        if (confirm('Are you sure you want to delete this room? This action cannot be undone.')) {
            fetch(`/business/rooms/${roomId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                // Handle both successful responses and redirects
                if (response.ok || response.status === 302) {
                    alert('Room deleted successfully!');
                    location.reload();
                } else {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Failed to delete room');
                    });
                }
            })
            .then(data => {
                // If we get here, it was a successful redirect
                if (data && data.success !== false) {
                    alert('Room deleted successfully!');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to delete room. Please try again.');
            });
        }
    }
    
    function editCottage(cottageId) {
        fetch(`/business/cottages/${cottageId}/edit`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success === false) {
                alert('Error: ' + data.message);
                return;
            }
            
            // Reset form and update modal for edit mode
            resetCottageForm();
            document.getElementById('cottageModalTitle').textContent = 'Edit Cottage';
            document.getElementById('cottageModalButton').textContent = 'Update Cottage';
            document.getElementById('cottageId').value = data.id;
            document.getElementById('cottage_method').value = 'PUT';
            document.getElementById('cottageForm').action = `/business/cottages/${data.id}`;
            
            // Populate form fields
            document.getElementById('cottage_name').value = data.cottage_name;
            document.getElementById('cottage_type').value = data.cottage_type;
            document.getElementById('price_per_night').value = data.price_per_night;
            document.getElementById('capacity').value = data.capacity;
            document.getElementById('description').value = data.description || '';
            // Note: cottage_is_available is a hidden field, no need to set checked property
            
            // Handle existing images
            const imagePreviews = document.getElementById('cottageImagePreviews');
            if (imagePreviews) {
                imagePreviews.innerHTML = '';
                let existingImageIds = [];
                if (data.images && data.images.length > 0) {
                    data.images.forEach(image => {
                        existingImageIds.push(image.id);
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                            <img src="${image.url}" class="w-full h-20 object-cover rounded-md">
                            <button type="button" onclick="removeCottageExistingImage(${image.id}, this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        imagePreviews.appendChild(div);
                    });
                }
                document.getElementById('cottageExistingImages').value = existingImageIds.join(',');
            }
            
            openModal('addCottageModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading cottage data');
        });
    }
    
    function deleteCottage(cottageId) {
        if (confirm('Are you sure you want to delete this cottage? This action cannot be undone.')) {
            fetch(`/business/cottages/${cottageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cottage deleted successfully');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting cottage');
            });
        }
    }
    
    function submitRoomForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const url = form.action;
        const method = form.querySelector('input[name="_method"]') ? form.querySelector('input[name="_method"]').value : 'POST';

        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

        fetch(url, {
            method: method === 'PUT' ? 'POST' : 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Room saved successfully!');
                // Close modal and refresh the page
                closeModal('addRoomModal');
                window.location.reload();
            } else {
                // Show error message
                alert('Error: ' + (data.message || 'Failed to save room'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving room. Please try again.');
        })
        .finally(() => {
            // Restore button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    }

    function submitCottageForm(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const url = form.action;
        const method = form.querySelector('input[name="_method"]') ? form.querySelector('input[name="_method"]').value : 'POST';

        // Show loading state
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

        fetch(url, {
            method: method === 'PUT' ? 'POST' : 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Cottage saved successfully!');
                // Close modal and refresh the page
                closeModal('addCottageModal');
                window.location.reload();
            } else {
                // Show error message
                alert('Error: ' + (data.message || 'Failed to save cottage'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error saving cottage. Please try again.');
        })
        .finally(() => {
            // Restore button state
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        });
    }
</script>
@endpush

</div>
@endsection
