<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Business Profile | Pagsurong Lagonoy</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'roboto': ['Roboto', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="font-roboto text-gray-800 leading-relaxed">

    <!-- Header -->
    <header class="bg-white py-4 px-4 md:px-10 flex flex-col md:flex-row justify-between items-center shadow-sm relative z-10">
        <div class="flex items-center mb-4 md:mb-0">
            <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-12 h-auto mr-3">
            <div class="font-playfair text-2xl font-bold text-gray-800">Pagsurong Lagonoy</div>
        </div>
        <nav class="flex flex-wrap justify-center">
            <a href="{{ route('home') }}" class="mx-3 my-1 md:my-0 text-gray-800 font-medium text-sm md:text-base hover:text-blue-500 {{ request()->routeIs('home') ? 'text-blue-500 font-semibold border-b-2 border-blue-500' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="mx-3 my-1 md:my-0 text-gray-800 font-medium text-sm md:text-base hover:text-blue-500 {{ request()->routeIs('about') ? 'text-blue-500 font-semibold border-b-2 border-blue-500' : '' }}">About Us</a>
            <a href="{{ route('contact') }}" class="mx-3 my-1 md:my-0 text-gray-800 font-medium text-sm md:text-base hover:text-blue-500 {{ request()->routeIs('contact') ? 'text-blue-500 font-semibold border-b-2 border-blue-500' : '' }}">Contact Us</a>
            @auth
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endauth
        </nav>
    </header>

    <!-- Main Content -->
    <div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 pt-24">
        <div class="max-w-2xl w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center">
                <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="mx-auto h-12 w-auto">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Complete Your Business Profile</h2>
                <p class="mt-2 text-sm text-gray-600">Set up your business information to start showcasing your products</p>
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

                <div class="space-y-6">
                    <!-- Profile Avatar -->
                    <div class="avatar-box flex flex-col items-center">
                        <img id="preview" class="avatar-preview w-24 h-24 rounded-full object-cover border-2 border-blue-500 mb-2" src="{{ asset('uploads/default.png') }}" alt="avatar" />
                        <input type="file" name="profile_picture" accept="image/*" onchange="previewFile(this)" class="text-sm" />
                        <p class="mt-1 text-sm text-gray-500">Upload your profile picture</p>
                    </div>

                    <!-- Personal Information Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                                <input id="full_name" name="full_name" type="text" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                       placeholder="Juan Dela Cruz" value="{{ old('full_name') }}">
                            </div>

                            <div>
                                <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday <span class="text-red-500">*</span></label>
                                <input id="birthday" name="birthday" type="date" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                                       value="{{ old('birthday') }}">
                            </div>

                            <div>
                                <label for="age" class="block text-sm font-medium text-gray-700">Age <span class="text-red-500">*</span></label>
                                <input id="age" name="age" type="number" min="1" max="120" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                       placeholder="25" value="{{ old('age') }}">
                            </div>

                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700">Gender <span class="text-red-500">*</span></label>
                                <select id="sex" name="sex" required 
                                        class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-location-select name="location" :required="true" :value="old('location')">Personal Location</x-location-select>
                        </div>
                    </div>

                    <!-- Business Information Section -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Business Information</h3>
                        
                        <!-- Business Name -->
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700">Business Name <span class="text-red-500">*</span></label>
                            <input id="business_name" name="business_name" type="text" required 
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="Enter your business name" value="{{ old('business_name') }}">
                        </div>
                    </div>

                    <!-- Business History/Story -->
                    <div>
                        <label for="business_info" class="block text-sm font-medium text-gray-700">Business History/Story <span class="text-red-500">*</span></label>
                        <textarea id="business_info" name="business_info" rows="4" required
                                  class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                  placeholder="Tell us about your business history, specialties, and what makes you unique">{{ old('business_info') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">You can edit this later in your profile section</p>
                    </div>

                    <!-- Business Location -->
                    <div>
                        <x-location-select name="business_location" :required="true" :value="old('business_location')">Business Location</x-location-select>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Contact Number <span class="text-red-500">*</span></label>
                            <input id="phone_number" name="phone_number" type="tel" required 
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="09xxxxxxxxx" value="{{ old('phone_number') }}">
                        </div>

                        <div>
                            <label for="email_address" class="block text-sm font-medium text-gray-700">Email Address (Optional)</label>
                            <input id="email_address" name="email_address" type="email" 
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="Enter your email address" value="{{ old('email_address') }}">
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Social Media Links (Optional)</label>
                        
                        <div>
                            <label for="facebook" class="block text-sm font-medium text-gray-600">Facebook</label>
                            <input id="facebook" name="facebook" type="url" 
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="https://facebook.com/yourbusiness" value="{{ old('facebook') }}">
                        </div>

                        <div>
                            <label for="instagram" class="block text-sm font-medium text-gray-600">Instagram</label>
                            <input id="instagram" name="instagram" type="url" 
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="https://instagram.com/yourbusiness" value="{{ old('instagram') }}">
                        </div>

                        <div>
                            <label for="twitter" class="block text-sm font-medium text-gray-600">Twitter/X</label>
                            <input id="twitter" name="twitter" type="url" 
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="https://twitter.com/yourbusiness" value="{{ old('twitter') }}">
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Complete Business Profile Setup
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-8 text-gray-500 text-sm bg-gray-50">
        &copy; {{ date('Y') }} Pagsurong Lagonoy. All rights reserved.
    </footer>

    <!-- Logout Script -->
    <script>
        function logoutUser(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
        
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById('preview').src = e.target.result;
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>
