@extends('layouts.app')

@section('title', 'Upload Promotions')

@section('content')
    <!-- Explore Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-20">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-10">Upload Promotions for Lagonoy</h2>

        <!-- Centered single item layout -->
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <!-- Tourist Spots -->
                <a href="{{ route('admin.upload.spots') }}" class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 border border-gray-100 block overflow-hidden group">
                    <div class="p-8 text-center flex flex-col items-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-blue-100 text-blue-500 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-map-marked-alt text-4xl group-hover:scale-110 transition-transform duration-300"></i>
                        </div>
                        <h3 class="text-2xl font-serif font-bold text-gray-800 mb-4">Tourist Spots</h3>
                        <p class="text-gray-600 text-base mb-6 leading-relaxed">Discover must-visit places in Lagonoy</p>
                        <span class="inline-block px-8 py-4 bg-blue-500 text-white rounded-full text-base font-medium hover:bg-blue-600 transition-colors duration-300 transform group-hover:-translate-y-1">
                            Upload Spots
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection