<!-- resources/views/attractions.blade.php -->
@extends('layouts.app')

@section('title', 'Tourist Attractions')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tourist Attractions</h1>

        @if($attractions->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($attractions as $attraction)
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                        <div class="relative w-full h-48">
                            @if($attraction->cover_photo)
                                <img src="{{ Storage::url($attraction->cover_photo) }}" class="w-full h-full object-cover" alt="{{ $attraction->name }}">
                            @elseif(!empty($attraction->gallery_images) && is_array($attraction->gallery_images) && count($attraction->gallery_images) > 0)
                                <img src="{{ Storage::url($attraction->gallery_images[0]) }}" class="w-full h-full object-cover" alt="{{ $attraction->name }}">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                    No Image
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $attraction->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $attraction->location }}</p>
                            <p class="mt-2 text-sm text-gray-700 line-clamp-2">{{ $attraction->short_info }}</p>
                            
                            <div class="mt-3">
                                @if($attraction->has_entrance_fee)
                                    <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                        Entrance Fee: ₱{{ number_format($attraction->entrance_fee, 2) }}
                                    </span>
                                @else
                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                        Free Entrance
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('attractions.show', $attraction->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">No attractions found.</p>
            </div>
        @endif
    </div>
</div>
@endsection