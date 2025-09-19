@extends('layouts.app')

@section('title', 'Hotels')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Our Hotels</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($hotels as $hotel)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <div class="relative w-full h-48">
                        @if($hotel->cover_photo)
                            <img src="{{ Storage::url($hotel->cover_photo) }}" class="w-full h-full object-cover" alt="{{ $hotel->name }}">
                        @elseif(!empty($hotel->gallery_images))
                            <img src="{{ Storage::url($hotel->gallery_images[0]) }}" class="w-full h-full object-cover" alt="{{ $hotel->name }}">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $hotel->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $hotel->location }}</p>
                        <p class="mt-2 text-sm text-gray-700 line-clamp-2">{{ $hotel->short_info }}</p>

                        <div class="mt-4">
                            <a href="{{ route('hotels.show', $hotel->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                View Details →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
