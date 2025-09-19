@extends('layouts.app')

@section('title', 'Upload Tourist Spots')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Left Sidebar - Admin Profile -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <!-- Admin Profile Section -->
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden bg-gray-200">
                        @if(auth()->user()->profile && auth()->user()->profile->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-500 flex items-center justify-center">
                                <span class="text-white text-lg font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-600">Administrator</p>
                </div>

                <!-- Tourist Spot Statistics Section -->
                <div class="border-t pt-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-4">Tourist Spot Statistics</h4>
                    
                    <!-- Most Popular (Likes) -->
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-heart text-red-500 mr-2"></i>Most Popular
                        </h5>
                        @if($mostPopular)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200">
                                        @if($mostPopular->profile_avatar)
                                            <img src="{{ asset('storage/' . $mostPopular->profile_avatar) }}" 
                                                 alt="{{ $mostPopular->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-red-500 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">
                                                    {{ strtoupper(substr($mostPopular->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $mostPopular->name }}</p>
                                        <p class="text-xs text-red-600">{{ $mostPopular->likes_count }} likes</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-gray-500">No data available</p>
                        @endif
                    </div>

                    <!-- Top Rated -->
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>Top Rated
                        </h5>
                        @if($topRated)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200">
                                        @if($topRated->profile_avatar)
                                            <img src="{{ asset('storage/' . $topRated->profile_avatar) }}" 
                                                 alt="{{ $topRated->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-yellow-500 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">
                                                    {{ strtoupper(substr($topRated->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $topRated->name }}</p>
                                        <p class="text-xs text-yellow-600">{{ number_format($topRated->average_rating, 1) }} ★ ({{ $topRated->total_ratings }} ratings)</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-gray-500">No data available</p>
                        @endif
                    </div>

                    <!-- Most Talked (Comments) -->
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-comments text-blue-500 mr-2"></i>Most Talked
                        </h5>
                        @if($mostTalked)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200">
                                        @if($mostTalked->profile_avatar)
                                            <img src="{{ asset('storage/' . $mostTalked->profile_avatar) }}" 
                                                 alt="{{ $mostTalked->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">
                                                    {{ strtoupper(substr($mostTalked->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $mostTalked->name }}</p>
                                        <p class="text-xs text-blue-600">{{ $mostTalked->comments_count }} comments</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-gray-500">No data available</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Middle Content - Upload Form -->
        <div class="lg:col-span-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Upload Tourist Spot</h1>
                    <p class="text-gray-600 mt-2">Add a new tourist destination for visitors to discover</p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="touristSpotForm" action="{{ route('admin.upload.spots.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Tourist Spot Name -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tourist Spot Name *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Enter tourist spot name">
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe the tourist spot...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Profile Avatar -->
                    <div class="mb-6">
                        <label for="profile_avatar" class="block text-sm font-medium text-gray-700 mb-2">Profile Avatar</label>
                        <input type="file" 
                               id="profile_avatar" 
                               name="profile_avatar" 
                               accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Cover Image -->
                    <div class="mb-6">
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                        <input type="file" 
                               id="cover_image" 
                               name="cover_image" 
                               accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Location -->
                    <div class="mb-6">
                        <x-location-select name="location" :value="old('location')">
                            Location
                        </x-location-select>
                    </div>


                    <!-- Additional Info -->
                    <div class="mb-6">
                        <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">Additional Information</label>
                        <textarea id="additional_info" 
                                  name="additional_info" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any additional information about the tourist spot...">{{ old('additional_info') }}</textarea>
                    </div>

                    <!-- Upload Gallery Section -->
                    <div class="mb-6 border-t pt-6">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Upload Gallery</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                                <input type="file" 
                                       id="gallery_images" 
                                       name="gallery_images[]" 
                                       multiple 
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                        
                        <!-- Gallery Preview -->
                        <div id="gallery-preview" class="mt-4 grid grid-cols-2 gap-2"></div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors font-medium">
                            Upload Tourist Spot
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Sidebar - Tourist Spots -->
        <div class="lg:col-span-3">

            <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Uploaded Tourist Spots</h3>
                
                @if($touristSpots->count() > 0)
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach($touristSpots as $spot)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start space-x-3">
                                    <!-- Profile Avatar -->
                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
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
                                    
                                    <!-- Spot Info -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $spot->name }}</h4>
                                        @if($spot->location)
                                            <p class="text-xs text-gray-600 truncate">{{ $spot->location }}</p>
                                        @endif
                                        <div class="flex items-center mt-1">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3 h-3 {{ $i <= $spot->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-600 ml-1">({{ $spot->total_ratings }})</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex space-x-2 mt-3">
                                    <button onclick="editTouristSpot({{ $spot->id }})" 
                                            class="flex-1 bg-blue-600 text-white text-xs py-2 px-3 rounded hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <button onclick="deleteTouristSpot({{ $spot->id }})" 
                                            class="flex-1 bg-red-600 text-white text-xs py-2 px-3 rounded hover:bg-red-700 transition-colors">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-map-marked-alt text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No tourist spots uploaded yet</p>
                        <p class="text-sm text-gray-400">Upload your first spot using the form</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Edit Tourist Spot</h3>
                        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                                <input type="text" id="edit_name" name="name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea id="edit_description" name="description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            
                            <div>
                                <x-location-select name="location">
                                    Location
                                </x-location-select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Information</label>
                                <textarea id="edit_additional_info" name="additional_info" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Avatar</label>
                                    <input type="file" name="profile_avatar" accept="image/*"
                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                                    <input type="file" name="cover_image" accept="image/*"
                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeEditModal()" 
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Update Tourist Spot
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global functions for edit and delete
function editTouristSpot(id) {
    fetch(`/admin/tourist-spots/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_name').value = data.name || '';
            document.getElementById('edit_description').value = data.description || '';
            // Set location value for the location component
            const locationSearchInput = document.getElementById('location_search');
            const locationHiddenInput = document.getElementById('location');
            if (locationSearchInput && locationHiddenInput) {
                locationSearchInput.value = data.location || '';
                locationHiddenInput.value = data.location || '';
            }
            document.getElementById('edit_additional_info').value = data.additional_info || '';
            
            document.getElementById('editForm').action = `/admin/tourist-spots/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading tourist spot data');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function deleteTouristSpot(id) {
    if (confirm('Are you sure you want to delete this tourist spot? This action cannot be undone.')) {
        fetch(`/admin/tourist-spots/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting tourist spot');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting tourist spot');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const uploadGalleryBtn = document.getElementById('upload-gallery-btn');
    const galleryInput = document.getElementById('gallery_images');
    const galleryPreview = document.getElementById('gallery-preview');
    const touristSpotForm = document.getElementById('touristSpotForm');

    // Reset form after successful submission if there's a success message
    @if(session('success'))
        if (touristSpotForm) {
            touristSpotForm.reset();
            // Clear location component
            const locationSearchInput = document.getElementById('location_search');
            const locationHiddenInput = document.getElementById('location');
            if (locationSearchInput) locationSearchInput.value = '';
            if (locationHiddenInput) locationHiddenInput.value = '';
            // Clear gallery preview
            if (galleryPreview) galleryPreview.innerHTML = '';
        }
    @endif

    if (uploadGalleryBtn) {
        uploadGalleryBtn.addEventListener('click', function() {
            const files = galleryInput.files;
            if (files.length === 0) {
                alert('Please select images to upload');
                return;
            }

            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('gallery_images[]', files[i]);
            }

            // Add CSRF token
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("admin.upload.spots.gallery") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Clear the input
                    galleryInput.value = '';
                    // Update preview
                    updateGalleryPreview(data.files);
                } else {
                    alert('Upload failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Upload failed');
            });
        });
    }

    function updateGalleryPreview(files) {
        galleryPreview.innerHTML = '';
        files.forEach(file => {
            const img = document.createElement('img');
            img.src = '/storage/' + file;
            img.className = 'w-full h-20 object-cover rounded';
            galleryPreview.appendChild(img);
        });
    }

    // Close modal when clicking outside
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
});
</script>
@endsection
