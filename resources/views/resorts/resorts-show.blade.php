<!-- [file name]: resorts-show.blade.php -->
@extends('layouts.app')

@section('title', $resort->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-xl shadow overflow-hidden">
            @if($resort->cover_photo)
                <img src="{{ Storage::url($resort->cover_photo) }}" class="w-full h-64 object-cover" alt="{{ $resort->name }}">
            @endif

            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ $resort->name }}</h1>
                <p class="text-gray-600">{{ $resort->location }}</p>
                <p class="mt-4 text-gray-700">{{ $resort->full_info }}</p>
            </div>

            @if(!empty($resort->gallery_images))
                <div class="px-6 pb-6">
                    <h2 class="text-lg font-semibold mb-3">Gallery</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($resort->gallery_images as $img)
                            <img src="{{ Storage::url($img) }}" class="w-full h-32 object-cover rounded-lg" alt="Gallery image">
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($resort->room_details))
                <div class="px-6 pb-6">
                    <h2 class="text-lg font-semibold mb-3">Rooms</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($resort->room_details as $room)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <h3 class="font-semibold">{{ $room['type'] ?? 'Room' }}</h3>
                                <p class="text-sm text-gray-600">{{ $room['features'] ?? '' }}</p>
                                <p class="mt-2 font-medium text-gray-900">₱{{ number_format($room['price'] ?? 0, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection