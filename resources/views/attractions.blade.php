@extends('layouts.app')

@section('title', 'Tourist Attractions - Pagsurong Lagonoy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 text-center">
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-gray-800 mb-2">Tourist Attractions</h1>
        <p class="text-gray-600">Discover must-visit places and hidden gems in Lagonoy</p>
    </div>

    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Coming Soon</h3>
        <p class="text-gray-500 mb-4">Tourist attraction listings will be available soon!</p>
        <a href="{{ route('dashboard') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap');
    
    .font-serif {
        font-family: 'Playfair Display', serif;
    }
</style>
@endsection
