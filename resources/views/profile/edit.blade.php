@extends('layouts.app')

@section('title', 'Edit Profile - Pagsurong Lagonoy')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-serif font-bold text-gray-800 mb-2">Edit Profile</h1>
                <p class="text-gray-600">Update your profile information</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="full_name" 
                           name="full_name" 
                           value="{{ old('full_name', $profile->full_name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your full name"
                           required>
                    @error('full_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birthday -->
                <div>
                    <label for="birthday" class="block text-sm font-medium text-gray-700 mb-2">
                        Birthday <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="birthday" 
                           name="birthday" 
                           value="{{ old('birthday', $profile->birthday) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('birthday')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-2">
                        Age <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="age" 
                           name="age" 
                           min="1" 
                           max="120"
                           value="{{ old('age', $profile->age) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your age"
                           required>
                    @error('age')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sex -->
                <div>
                    <label for="sex" class="block text-sm font-medium text-gray-700 mb-2">
                        Sex <span class="text-red-500">*</span>
                    </label>
                    <select id="sex" 
                            name="sex" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select</option>
                        <option value="Male" {{ old('sex', $profile->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $profile->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('sex', $profile->sex) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('sex')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="phone_number" 
                           name="phone_number" 
                           value="{{ old('phone_number', $profile->phone_number) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your phone number"
                           required>
                    @error('phone_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter your complete address"
                              required>{{ old('address', $profile->address) }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">
                        Bio
                    </label>
                    <textarea id="bio" 
                              name="bio" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Tell us a bit about yourself (optional)">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Media Links -->
                @if(auth()->user()->role === 'business_owner')
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Social Media Links</label>
                    
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-gray-600">Facebook</label>
                        <input type="url" 
                               id="facebook" 
                               name="facebook" 
                               value="{{ old('facebook', $profile->facebook) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://facebook.com/yourprofile">
                        @error('facebook')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-600">Instagram</label>
                        <input type="url" 
                               id="instagram" 
                               name="instagram" 
                               value="{{ old('instagram', $profile->instagram) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://instagram.com/yourprofile">
                        @error('instagram')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="twitter" class="block text-sm font-medium text-gray-600">Twitter/X</label>
                        <input type="url" 
                               id="twitter" 
                               name="twitter" 
                               value="{{ old('twitter', $profile->twitter) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://twitter.com/yourprofile">
                        @error('twitter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif

                <!-- Profile Picture -->
                <div>
                    <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">
                        Profile Picture
                    </label>
                    <div class="flex items-center space-x-4">
                        @if($profile->profile_picture)
                            <img src="{{ asset('storage/' . $profile->profile_picture) }}" 
                                 alt="Current Profile Picture" 
                                 class="w-20 h-20 rounded-full object-cover">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-gray-400 text-2xl"></i>
                            </div>
                        @endif
                        <div class="flex-1">
                            <input type="file" 
                                   id="profile_picture" 
                                   name="profile_picture"
                                   accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-sm text-gray-500">JPG, PNG or JPEG. Max 2MB.</p>
                        </div>
                    </div>
                    @error('profile_picture')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-6 flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        Update Profile
                    </button>
                    <a href="{{ route('profile.show') }}" 
                       class="flex-1 bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-medium hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 text-center">
                        Cancel
                    </a>
                </div>
            </form>
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
