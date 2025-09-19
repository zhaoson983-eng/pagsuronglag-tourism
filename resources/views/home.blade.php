<?php $currentYear = date("Y"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pagsurong Lagonoy')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
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
    <header class="py-4 px-4 md:px-10 flex flex-col md:flex-row justify-between items-center shadow-sm relative z-10 text-white" style="background-color: #012844ff;">
        <div class="flex items-center mb-4 md:mb-0">
            <img src="{{ asset('logo.png') }}" alt="Pagsurong Lagonoy Logo" class="w-12 h-auto mr-3">
            <div class="font-playfair text-2xl font-bold text-white">Pagsurong Lagonoy</div>
        </div>
        <nav class="flex flex-wrap justify-center">
            <a href="{{ route('home') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-200 {{ request()->routeIs('home') ? 'font-semibold border-b-2 border-white' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-200 {{ request()->routeIs('about') ? 'font-semibold border-b-2 border-white' : '' }}">About Us</a>
            <a href="{{ route('contact') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-200 {{ request()->routeIs('contact') ? 'font-semibold border-b-2 border-white' : '' }}">Contact Us</a>
            @auth
                <a href="{{ route('dashboard') }}" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-200 {{ request()->routeIs('dashboard') ? 'font-semibold border-b-2 border-white' : '' }}">Dashboard</a>
                <a href="#" onclick="logoutUser(event)" class="mx-3 my-1 md:my-0 text-white font-medium text-sm md:text-base hover:text-blue-200">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @endauth
        </nav>
    </header>

    <!-- Hero Banner with Dynamic Background -->
    <div class="relative h-[70vh] min-h-[500px] bg-cover bg-center flex flex-col justify-center items-center text-center text-white" style="background-image: url('plaza1.jpg');">
        <div class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-30 backdrop-blur-sm"></div>
        <div class="relative z-10 max-w-3xl px-5">
            <h1 class="font-playfair text-4xl md:text-5xl font-bold mb-5 text-white drop-shadow-lg leading-tight">
                Showcasing Lagonoy Local Products, Hotels, Resorts and Tourist Spots
            </h1>
            <p class="text-xl md:text-2xl text-white text-opacity-90 mb-10 font-light drop-shadow-md">
                Local Finds, Unforgettable Memories!
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-8">
                @guest
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3 rounded-full bg-blue-500 text-white font-medium text-lg hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-300">Register</a>
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 rounded-full border-2 border-white text-white bg-white bg-opacity-10 font-medium text-lg hover:bg-opacity-20 transform hover:-translate-y-1 transition-all duration-300">Login</a>
                @else
                    <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 rounded-full bg-blue-500 text-white font-medium text-lg hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-300">Go to Dashboard</a>
                @endguest
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
                <p>&copy; {{ $currentYear }} Pagsurong Lagonoy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Logout Script -->
    <script>
        function logoutUser(event) {
            event.preventDefault();
            document.getElementById('logout-form').submit();
        }
    </script>

</body>
</html>