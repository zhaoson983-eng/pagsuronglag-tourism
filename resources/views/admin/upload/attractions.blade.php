<!-- [file name]: attractions.blade.php (Admin Upload Form) -->
@extends('layouts.app')

@section('title', 'Upload Tourist Attraction')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-2 text-center">Upload Tourist Attraction</h1>
    <p class="text-gray-600 text-center mb-8">Add a new tourist attraction with all its details and images</p>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.attractions.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-2xl p-8 space-y-8">
        @csrf

        <!-- Cover Photo -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Photo *</label>
            <input type="file" name="cover_photo" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-1">This will be the main image shown in listings. Max: 2MB</p>
        </div>

        <!-- Attraction Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Attraction Name *</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Location -->
        <div>
            <label for="location" class="block text-sm font-medium text-gray-700">Location (e.g., Barangay, Street) *</label>
            <input type="text" name="location" id="location" value="{{ old('location') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Short Info -->
        <div>
            <label for="short_info" class="block text-sm font-medium text-gray-700">Short Description *</label>
            <textarea name="short_info" id="short_info" rows="3" placeholder="A brief overview shown in listings..." required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('short_info') }}</textarea>
        </div>

        <!-- Full Info -->
        <div>
            <label for="full_info" class="block text-sm font-medium text-gray-700">Full Attraction Information</label>
            <textarea name="full_info" id="full_info" rows="6" placeholder="Detailed description, history, significance..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('full_info') }}</textarea>
        </div>

        <!-- Entrance Fee -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Entrance Fee</label>
            <div class="flex items-center space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="has_entrance_fee" value="0" {{ old('has_entrance_fee', 0) == 0 ? 'checked' : '' }} class="form-radio text-blue-600">
                    <span class="ml-2">Free Entrance</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="has_entrance_fee" value="1" {{ old('has_entrance_fee') == 1 ? 'checked' : '' }} class="form-radio text-blue-600">
                    <span class="ml-2">With Entrance Fee</span>
                </label>
            </div>
        </div>

        <div id="entrance-fee-container" class="{{ old('has_entrance_fee') == 1 ? '' : 'hidden' }}">
            <label for="entrance_fee" class="block text-sm font-medium text-gray-700">Entrance Fee Amount (₱)</label>
            <input type="number" name="entrance_fee" id="entrance_fee" value="{{ old('entrance_fee') }}" min="0" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Additional Info -->
        <div>
            <label for="additional_info" class="block text-sm font-medium text-gray-700">Additional Information</label>
            <textarea name="additional_info" id="additional_info" rows="4" placeholder="Operating hours, best time to visit, restrictions, etc." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('additional_info') }}</textarea>
        </div>

        <!-- Additional Images -->
        <div>
            <label for="images" class="block text-sm font-medium text-gray-700">Additional Images (for gallery)</label>
            <input type="file" name="gallery_images[]" id="images" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-1">You can upload multiple images. Max: 2MB per image.</p>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-medium rounded-full hover:bg-blue-700 transition">
                Upload Attraction
            </button>
        </div>
    </form>
</div>

<!-- Script to toggle entrance fee field -->
<script>
    document.querySelectorAll('input[name="has_entrance_fee"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const feeContainer = document.getElementById('entrance-fee-container');
            feeContainer.classList.toggle('hidden', this.value != 1);
            
            if (this.value != 1) {
                document.getElementById('entrance_fee').value = '';
            }
        });
    });
</script>
@endsection