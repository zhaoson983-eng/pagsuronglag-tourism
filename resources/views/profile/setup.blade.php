@extends('layouts.app')

@section('title', 'Complete Your Profile | Pagsurong Lagonoy')

@section('content')
    <!-- Main Content -->
    <div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Complete Your Profile</h2>
                <p class="mt-4 text-sm text-gray-600">Please complete your profile to continue</p>
            </div>

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="avatar-box flex flex-col items-center mb-6">
                        <img id="preview" class="avatar-preview w-24 h-24 rounded-full object-cover border-2 border-blue-500 mb-2" src="{{ asset('uploads/default.png') }}" alt="avatar" />
                        <input type="file" name="profile_picture" accept="image/*" onchange="previewFile(this)" class="text-sm" />
                    </div>

                    <div class="py-2">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input id="full_name" name="full_name" type="text" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="Juan Dela Cruz" value="{{ old('full_name') }}">
                    </div>

                    <div class="py-2">
                        <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday <span class="text-red-500">*</span></label>
                        <input id="birthday" name="birthday" type="date" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                               value="{{ old('birthday') }}">
                    </div>

                    <div class="py-2">
                        <label for="age" class="block text-sm font-medium text-gray-700">Age <span class="text-red-500">*</span></label>
                        <input id="age" name="age" type="number" min="1" max="120" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="25" value="{{ old('age') }}">
                    </div>

                    <div class="py-2">
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex <span class="text-red-500">*</span></label>
                        <select id="sex" name="sex" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="py-2">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input id="phone_number" name="phone_number" type="tel" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="09xxxxxxxxx" value="{{ old('phone_number') }}">
                    </div>

                    <div class="py-2">
                        <x-location-select name="location" :required="true" :value="old('location')">Location</x-location-select>
                    </div>

                    <div class="py-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700">Short Bio</label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                  placeholder="Tell us a bit about yourself...">{{ old('bio') }}</textarea>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Complete Profile Setup
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('preview').src = e.target.result;
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
