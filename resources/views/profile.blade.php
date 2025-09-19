@extends('layouts.app')

@section('title', $user->full_name . ' – Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Profile Header with Gradient Background -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-12 text-center">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <img src="{{ !empty($user->avatar) ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}" 
                         alt="{{ $user->full_name }}" 
                         class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                    @if(Auth::id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="absolute bottom-2 right-2 bg-white rounded-full p-2 shadow-md hover:bg-gray-100 transition-colors">
                            <i class="fas fa-pencil-alt text-blue-500"></i>
                        </a>
                    @endif
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $user->full_name }}</h1>
            <p class="text-blue-100">{{ $user->email }}</p>
            <div class="mt-4 flex justify-center space-x-4">
                <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm">
                    {{ ucfirst($user->role) }}
                </span>
                @if($user->is_business_owner)
                    <span class="bg-amber-500 bg-opacity-90 px-3 py-1 rounded-full text-sm">
                        Business Owner
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Profile Information -->
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b">Profile Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-user text-blue-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Full Name</h3>
                            <p class="mt-1 text-gray-900">{{ $user->full_name }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-envelope text-green-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Email</h3>
                            <p class="mt-1 text-gray-900">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-phone text-purple-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Phone</h3>
                            <p class="mt-1 text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="bg-amber-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-birthday-cake text-amber-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Birthday</h3>
                            <p class="mt-1 text-gray-900">
                                {{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('F j, Y') : 'Not provided' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5 md:col-span-2">
                    <div class="flex items-start">
                        <div class="bg-red-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-map-marker-alt text-red-500 text-xl"></i>
                        </div>
                        <div class="w-full">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Address</h3>
                            <p class="mt-1 text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="bg-pink-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-venus-mars text-pink-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Gender</h3>
                            <p class="mt-1 text-gray-900">{{ $user->sex ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="bg-teal-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-user-tag text-teal-500 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Member Since</h3>
                            <p class="mt-1 text-gray-900">
                                {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('F Y') : 'Unknown' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 rounded-xl p-5 md:col-span-2">
                    <div class="flex items-start">
                        <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-quote-left text-indigo-500 text-xl"></i>
                        </div>
                        <div class="w-full">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Bio</h3>
                            <p class="mt-1 text-gray-900">{{ $user->bio ?? 'No bio provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-8 text-gray-500 text-sm bg-gray-50 mt-8">
    &copy; {{ date('Y') }} Pagsurong Lagonoy. All rights reserved.
</footer>
@endsection

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
    }
    
    .profile-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .info-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
    }
    
    @media (max-width: 768px) {
        .profile-header {
            padding: 2rem 1rem;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
        }
        
        .profile-name {
            font-size: 1.5rem;
        }
    }
</style>
@endsection