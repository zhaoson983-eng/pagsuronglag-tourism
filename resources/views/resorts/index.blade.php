<!-- [file name]: resorts.blade.php (Customer View) -->
@extends('layouts.app')

@section('title', 'Resorts')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Our Resorts</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($resorts as $resort)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <div class="relative w-full h-48">
                        @if($resort->cover_photo)
                            <img src="{{ Storage::url($resort->cover_photo) }}" class="w-full h-full object-cover" alt="{{ $resort->name }}">
                        @elseif(!empty($resort->gallery_images))
                            <img src="{{ Storage::url($resort->gallery_images[0]) }}" class="w-full h-full object-cover" alt="{{ $resort->name }}">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                No Image
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $resort->name }}</h2>
                        <p class="text-sm text-gray-600">{{ $resort->location }}</p>
                        <p class="mt-2 text-sm text-gray-700 line-clamp-2">{{ $resort->short_info }}</p>

                        <div class="mt-4">
                            <a href="{{ route('resorts.show', $resort->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
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