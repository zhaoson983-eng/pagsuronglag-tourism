@extends('layouts.app')

@section('title', 'Complete Your Profile | Pagsurong Lagonoy')

@section('content')
    <!-- Main Content -->
    <div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Complete Your Profile</h2>
                <p class="mt-4 text-sm text-gray-600">Please complete your profile to continue</p>
            </div>

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Enhanced Profile Picture Upload -->
                    <div class="mb-8">
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <!-- Upload Zone -->
                                <div id="upload-zone" class="relative w-32 h-32 rounded-full border-4 border-dashed border-blue-300 bg-blue-50 hover:bg-blue-100 transition-all duration-300 cursor-pointer group">
                                    <div class="absolute inset-0 rounded-full overflow-hidden">
                                        <img id="preview" class="w-full h-full object-cover" src="{{ asset('uploads/default.png') }}" alt="Profile Picture">
                                    </div>

                                    <!-- Upload Overlay -->
                                    <div id="upload-overlay" class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="text-center text-white">
                                            <i class="fas fa-camera text-2xl mb-2"></i>
                                            <p class="text-xs font-medium">Upload Photo</p>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <button id="remove-btn" type="button" onclick="removeImage()" class="hidden absolute -top-2 -right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition-colors duration-200" title="Remove photo">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>

                                <!-- Hidden File Input -->
                                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="handleFileSelect(event)">
                            </div>

                            <!-- Upload Instructions -->
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-600 mb-2">Click to upload or drag and drop</p>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>

                            <!-- Progress Bar -->
                            <div id="progress-container" class="hidden mt-3 w-32">
                                <div class="bg-gray-200 rounded-full h-2">
                                    <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <p id="progress-text" class="text-xs text-gray-500 mt-1 text-center">Uploading...</p>
                            </div>

                            <!-- Error Message -->
                            <div id="error-message" class="hidden mt-2 text-red-600 text-sm text-center"></div>
                        </div>
                    </div>

                    <div class="py-2">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input id="full_name" name="full_name" type="text" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="Juan Dela Cruz" value="{{ old('full_name', auth()->user()->name) }}">
                    </div>

                    <div class="py-2">
                        <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday <span class="text-red-500">*</span></label>
                        <input id="birthday" name="birthday" type="date" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                               value="{{ old('birthday') }}">
                    </div>

                    <div class="py-2">
                        <label for="age" class="block text-sm font-medium text-gray-700">Age <span class="text-red-500">*</span></label>
                        <input id="age" name="age" type="number" min="1" max="120" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="25" value="{{ old('age') }}">
                    </div>

                    <div class="py-2">
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex <span class="text-red-500">*</span></label>
                        <select id="sex" name="sex" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="py-2">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input id="phone_number" name="phone_number" type="tel" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="09xxxxxxxxx" value="{{ old('phone_number') }}">
                    </div>

                    <div class="py-2">
                        <x-location-select name="location" :required="true" :value="old('location')">Location</x-location-select>
                    </div>

                    <div class="py-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700">Short Bio</label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                  placeholder="Tell us a bit about yourself...">{{ old('bio') }}</textarea>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Complete Profile Setup
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
let selectedFile = null;

// Handle file selection
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (file) {
        validateAndPreviewFile(file);
    }
}

// Validate and preview file
function validateAndPreviewFile(file) {
    const maxSize = 2 * 1024 * 1024; // 2MB
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

    // Clear previous errors
    hideError();

    // Validate file type
    if (!allowedTypes.includes(file.type)) {
        showError('Please select a valid image file (PNG, JPG, JPEG)');
        return;
    }

    // Validate file size
    if (file.size > maxSize) {
        showError('File size must be less than 2MB');
        return;
    }

    selectedFile = file;
    previewFile(file);
    showRemoveButton();
}

// Preview file
function previewFile(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
}

// Remove image
function removeImage() {
    document.getElementById('preview').src = "{{ asset('uploads/default.png') }}";
    document.getElementById('profile_picture').value = '';
    selectedFile = null;
    hideRemoveButton();
    hideError();
}

// Show error message
function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.classList.remove('hidden');
}

// Hide error message
function hideError() {
    document.getElementById('error-message').classList.add('hidden');
}

// Show remove button
function showRemoveButton() {
    document.getElementById('remove-btn').classList.remove('hidden');
}

// Hide remove button
function hideRemoveButton() {
    document.getElementById('remove-btn').classList.add('hidden');
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('upload-zone');
    const fileInput = document.getElementById('profile_picture');

    // Click to upload
    uploadZone.addEventListener('click', function() {
        fileInput.click();
    });

    // Drag and drop
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadZone.classList.add('border-blue-500', 'bg-blue-200');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('border-blue-500', 'bg-blue-200');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadZone.classList.remove('border-blue-500', 'bg-blue-200');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            validateAndPreviewFile(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', handleFileSelect);
});

// Simulate upload progress (since we're using basic form submission)
function simulateProgress() {
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');

    progressContainer.classList.remove('hidden');

    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = progress + '%';

        if (progress >= 100) {
            clearInterval(interval);
            setTimeout(() => {
                progressContainer.classList.add('hidden');
                progressBar.style.width = '0%';
            }, 500);
        }
    }, 100);
}

// Form submission
document.querySelector('form').addEventListener('submit', function(e) {
    if (selectedFile) {
        simulateProgress();
    }
});
</script>
@endpush
