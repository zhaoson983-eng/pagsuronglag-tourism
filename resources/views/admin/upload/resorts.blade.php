<!-- [file name]: resorts.blade.php (Admin Upload Form) -->
@extends('layouts.app')

@section('title', 'Upload Resort')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-2 text-center">Upload Resort</h1>
    <p class="text-gray-600 text-center mb-8">Add a new resort with all its details and images</p>

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

    <form action="{{ route('admin.resorts.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-lg rounded-2xl p-8 space-y-8">
        @csrf

        <!-- Cover Photo -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Photo *</label>
            <input type="file" name="cover_photo" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-1">This will be the main image shown in listings. Max: 2MB</p>
        </div>

        <!-- Resort Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Resort Name *</label>
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
            <label for="full_info" class="block text-sm font-medium text-gray-700">Full Resort Information</label>
            <textarea name="full_info" id="full_info" rows="6" placeholder="Detailed description, amenities, history, services..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('full_info') }}</textarea>
        </div>

        <!-- Rooms & Prices -->
        <div id="rooms-container" class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">Rooms and Prices *</h3>
            <div class="room-entry grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="rooms[0][type]" placeholder="Room Type (e.g., Deluxe, Family)" value="{{ old('rooms.0.type') }}" class="border border-gray-300 rounded-md p-2" required>
                <input type="number" name="rooms[0][price]" placeholder="Price per Night" value="{{ old('rooms.0.price') }}" class="border border-gray-300 rounded-md p-2" required min="0" step="0.01">
                <input type="text" name="rooms[0][features]" placeholder="Features (e.g., AC, WiFi)" value="{{ old('rooms.0.features') }}" class="border border-gray-300 rounded-md p-2">
            </div>
        </div>
        <button type="button" id="add-room" class="text-blue-600 text-sm hover:underline">+ Add Another Room</button>

        <!-- Additional Images -->
        <div>
            <label for="images" class="block text-sm font-medium text-gray-700">Additional Images (for gallery)</label>
            <input type="file" name="gallery_images[]" id="images" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-1">You can upload multiple images. Max: 2MB per image.</p>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-medium rounded-full hover:bg-blue-700 transition">
                Upload Resort
            </button>
        </div>
    </form>
</div>

<!-- Script to add more room entries -->
<script>
    document.getElementById('add-room').addEventListener('click', function () {
        const container = document.getElementById('rooms-container');
        const index = container.querySelectorAll('.room-entry').length;
        const div = document.createElement('div');
        div.className = 'room-entry grid grid-cols-1 md:grid-cols-3 gap-4';
        div.innerHTML = `
            <input type="text" name="rooms[${index}][type]" placeholder="Room Type" class="border border-gray-300 rounded-md p-2" required>
            <input type="number" name="rooms[${index}][price]" placeholder="Price per Night" class="border border-gray-300 rounded-md p-2" required min="0" step="0.01">
            <input type="text" name="rooms[${index}][features]" placeholder="Features" class="border border-gray-300 rounded-md p-2">
        `;
        container.appendChild(div);
    });
</script>
@endsection