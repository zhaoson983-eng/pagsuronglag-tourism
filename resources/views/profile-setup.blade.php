<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile | Pagsurong Lagonoy</title>
    
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
    <div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <div class="text-center">
                <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="mx-auto h-12 w-auto">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Complete Your Profile</h2>
                <p class="mt-2 text-sm text-gray-600">Please complete your profile to continue</p>
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

            <form method="POST" action="{{ route('profile.setup.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="avatar-box flex flex-col items-center mb-6">
                        <img id="preview" class="avatar-preview w-24 h-24 rounded-full object-cover border-2 border-blue-500 mb-2" src="{{ asset('uploads/default.png') }}" alt="avatar" />
                        <input type="file" name="avatar" accept="image/*" onchange="previewFile(this)" class="text-sm" />
                    </div>

                    <div class="py-2">
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="full_name" name="full_name" type="text" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="Juan Dela Cruz" value="{{ old('full_name') }}">
                    </div>

                    <div class="py-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input id="address" name="address" type="text" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="Brgy. San Vicente, Lagonoy" value="{{ old('address') }}">
                    </div>

                    <div class="py-2">
                        <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday</label>
                        <input id="birthday" name="birthday" type="date" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                               value="{{ old('birthday') }}">
                    </div>

                    <div class="py-2">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input id="phone" name="phone" type="tel" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="09xxxxxxxxx" value="{{ old('phone') }}">
                    </div>

                    <div class="py-2">
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex</label>
                        <select id="sex" name="sex" required 
                                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select</option>
                            <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="py-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700">Short Bio</label>
                        <textarea id="bio" name="bio" 
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
                        Save Profile
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