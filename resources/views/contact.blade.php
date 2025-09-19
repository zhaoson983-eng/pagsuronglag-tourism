<?php $currentYear = date("Y"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pagsurong Lagonoy</title>
    
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
    
    <!-- Custom Styles -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
</head>
<body class="font-roboto text-gray-800 leading-relaxed bg-gray-50">

    <!-- Header -->
    <header style="background-color: #012844ff;" class="py-4 px-4 md:px-10 flex flex-col md:flex-row justify-between items-center shadow-sm sticky top-0 z-10">
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
            <!-- Removed Login and Register links -->
        </nav>
    </header>

    <div class="text-center py-20 px-4 fade-in">
        <h1 class="font-playfair text-4xl md:text-5xl font-bold mb-5 text-gray-800 leading-tight">Get In Touch</h1>
        <p class="text-xl text-gray-600 font-light">We'd love to hear from you</p>
    </div>

    <div class="max-w-6xl mx-auto px-5 text-center fade-in">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md text-left">
            <form>
                <div class="mb-5">
                    <label for="name" class="block mb-2 font-medium text-gray-800">Name</label>
                    <input type="text" id="name" required placeholder="Your full name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div class="mb-5">
                    <label for="email" class="block mb-2 font-medium text-gray-800">Email</label>
                    <input type="email" id="email" required placeholder="Your email address" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div class="mb-5">
                    <label for="subject" class="block mb-2 font-medium text-gray-800">Subject</label>
                    <input type="text" id="subject" placeholder="What is this regarding?" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                </div>
                <div class="mb-6">
                    <label for="message" class="block mb-2 font-medium text-gray-800">Message</label>
                    <textarea id="message" required placeholder="How can we help you?" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition h-40 resize-y"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-3.5 rounded-full font-medium text-lg hover:bg-blue-600 transform hover:-translate-y-1 transition-all duration-300 shadow-md hover:shadow-lg">
                    Send Message
                </button>
            </form>
        </div>

        <div class="flex flex-wrap justify-center gap-8 mt-12">
            <div class="flex-1 min-w-[250px] bg-white p-6 rounded-lg shadow-sm hover:translate-y-[-5px] transition-transform">
                <div class="text-4xl mb-4 text-blue-500">📞</div>
                <h3 class="text-xl font-bold mb-3">Phone</h3>
                <p class="text-gray-600">+63 123 456 7890</p>
                <p class="text-gray-600">+63 987 654 3210</p>
            </div>
            
            <div class="flex-1 min-w-[250px] bg-white p-6 rounded-lg shadow-sm hover:translate-y-[-5px] transition-transform">
                <div class="text-4xl mb-4 text-blue-500">📧</div>
                <h3 class="text-xl font-bold mb-3">Email</h3>
                <p class="text-gray-600">info@pagsuronglagonoy.com</p>
                <p class="text-gray-600">support@pagsuronglagonoy.com</p>
            </div>
            
            <div class="flex-1 min-w-[250px] bg-white p-6 rounded-lg shadow-sm hover:translate-y-[-5px] transition-transform">
                <div class="text-4xl mb-4 text-blue-500">📍</div>
                <h3 class="text-xl font-bold mb-3">Address</h3>
                <p class="text-gray-600">Municipal Building</p>
                <p class="text-gray-600">Lagonoy, Camarines Sur</p>
                <p class="text-gray-600">Philippines</p>
            </div>
        </div>
        
        <!-- Updated Map Section with Google Maps Embed -->
        <div class="mt-12 rounded-lg overflow-hidden shadow-md h-80">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3875.761398331468!2d123.51834237411634!3d13.732890497732994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a1c351609944a1%3A0x29fbae3f657b85f7!2sLagonoy%20Municipal%20Hall!5e0!3m2!1sen!2sph!4v1755818544100!5m2!1sen!2sph" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <div class="w-4/5 max-w-md h-px bg-gray-200 mx-auto my-10"></div>

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