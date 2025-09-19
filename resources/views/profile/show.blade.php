@extends('layouts.app')

@section('title', 'My Profile - Pagsurong Lagonoy')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-serif font-bold text-gray-800 mb-2">My Profile</h1>
                <p class="text-gray-600">Manage your profile information</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-6">
                <!-- Profile Picture -->
                <div class="text-center">
                    @if($profile && $profile->profile_picture)
                        <img src="{{ asset('storage/' . $profile->profile_picture) }}" 
                             alt="Profile Picture" 
                             class="w-32 h-32 rounded-full object-cover mx-auto border-4 border-blue-500">
                    @else
                        <div class="w-32 h-32 rounded-full bg-blue-500 flex items-center justify-center mx-auto">
                            <span class="text-white text-4xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <p class="text-gray-900 font-medium">{{ $user->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-gray-900 font-medium">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Profile Info -->
                @if($profile)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <p class="text-gray-900">{{ $profile->phone_number ?: 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <p class="text-gray-900">{{ $profile->address ?: 'Not provided' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                        <p class="text-gray-900">{{ $profile->bio ?: 'No bio provided' }}</p>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">No profile information found.</p>
                        <a href="{{ route('profile.setup') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Complete Profile Setup
                        </a>
                    </div>
                @endif

                <!-- Actions -->
                @if($profile)
                    <div class="pt-6 flex justify-center">
                        <a href="{{ route('profile.edit') }}" 
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profile
                        </a>
                    </div>
                @endif
            </div>
        </div>
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
