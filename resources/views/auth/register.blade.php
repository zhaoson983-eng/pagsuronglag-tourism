<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Pagsurong Lagonoy</title>
    
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
    <header style="background-color: #012844ff;" class="py-4 px-4 md:px-10 flex flex-col md:flex-row justify-between items-center shadow-sm relative z-10">
        <div class="flex items-center mb-4 md:mb-0">
            <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-12 h-auto mr-3">
            <div class="font-playfair text-2xl font-bold text-white">Pagsurong Lagonoy</div>
        </div>
        <nav class="flex flex-wrap justify-center">
            <a href="{{ route('home') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-300">Home</a>
            <a href="{{ route('about') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-300">About Us</a>
            <a href="{{ route('contact') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-300">Contact Us</a>
            @auth
                <a href="{{ route('dashboard') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-300">Dashboard</a>
                <a href="#" onclick="logoutUser(event)" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-300">Logout</a>
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
                
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Create Your Account</h2>
                <p class="mt-2 text-sm text-gray-600">Or <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">sign in to your account</a></p>
            </div>

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @elseif ($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                <div class="rounded-md shadow-sm -space-y-px">
                    <div class="py-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="name" name="name" type="text" required autofocus 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="Juan D. Cruz"
                               value="{{ old('name') }}">
                    </div>

                    <div class="py-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input id="email" name="email" type="email" required 
                               class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                               placeholder="Enter a valid email address"
                               value="{{ old('email') }}">
                    </div>

                    <div class="py-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required 
                                   class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="Create a strong password">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                                <i id="password-toggle-icon" class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="py-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                   class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900"
                                   placeholder="Confirm your password">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-blue-600 focus:outline-none">
                                <i id="password_confirmation-toggle-icon" class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Account Type -->
                    <div class="py-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="role" value="customer" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                    {{ old('role') === 'customer' ? 'checked' : '' }}>
                                <span class="ml-2 block text-sm text-gray-700">Customer</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="role" value="business_owner" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                    {{ old('role') === 'business_owner' ? 'checked' : '' }}>
                                <span class="ml-2 block text-sm text-gray-700">Business Owner</span>
                            </label>
                        </div>
                    </div>

                    <!-- Business Type (shown only when Business Owner is selected) -->
                    <div id="businessTypeSection" class="py-2 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Business Type</label>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="business_type" value="local_products" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" 
                                    {{ old('business_type') === 'local_products' ? 'checked' : '' }} required>
                                <span class="ml-2 block text-sm text-gray-700">Local Products Shop</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="business_type" value="hotel" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                    {{ old('business_type') === 'hotel' ? 'checked' : '' }} required>
                                <span class="ml-2 block text-sm text-gray-700">Hotel</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="business_type" value="resort" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                    {{ old('business_type') === 'resort' ? 'checked' : '' }} required>
                                <span class="ml-2 block text-sm text-gray-700">Resort</span>
                            </label>
                        </div>
                        @error('business_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Removed business detail collection during registration; this will be done in setup -->
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Register
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Already have an account? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">Login here</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: #012844ff;" class="text-white py-10">
        <div class="max-w-6xl mx-auto px-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-playfair font-bold mb-4">Pagsurong Lagonoy</h3>
                    <p class="text-gray-300">
                        Showcasing the best of Lagonoy's local products, accommodations, and tourist destinations.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Home</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors duration-200">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Contact</a></li>
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Login</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Register</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Connect With Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                    <p class="mt-4 text-gray-300">
                        Email: info@pagsuronglagonoy.com
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Pagsurong Lagonoy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Logout Script -->
    <script>
        function logoutUser(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                document.getElementById('logout-form').submit();
            }
        }

        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-toggle-icon') || document.getElementById('password-toggle-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Show/hide business sections based on account type selection
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const businessTypeSection = document.getElementById('businessTypeSection');
            const businessTypeInputs = document.querySelectorAll('input[name="business_type"]');

            function toggleBusinessSections() {
                const isBusinessOwner = document.querySelector('input[name="role"]:checked')?.value === 'business_owner';
                
                if (isBusinessOwner) {
                    businessTypeSection.classList.remove('hidden');
                    businessTypeInputs.forEach(input => input.required = true);
                } else {
                    businessTypeSection.classList.add('hidden');
                    businessTypeInputs.forEach(input => input.required = false);
                }
            }

            // Add event listeners to all role radio buttons
            roleInputs.forEach(input => {
                input.addEventListener('change', toggleBusinessSections);
            });

            // Initialize the view based on any previously selected value
            toggleBusinessSections();
        });
    </script>

</body>
</html>