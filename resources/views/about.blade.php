@php $currentYear = date("Y"); @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pagsurong Lagonoy</title>
    
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
        <h1 class="font-playfair text-4xl md:text-5xl font-bold mb-5 text-gray-800 leading-tight">About Pagsurong Lagonoy</h1>
        <p class="text-xl text-gray-600 font-light">Connecting you to the best of Lagonoy</p>
    </div>

    <div class="max-w-6xl mx-auto px-5 fade-in">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Our Platform</h2>
        <p class="mb-8 text-gray-700">Pagsurong Lagonoy is a comprehensive web platform designed to showcase and promote local products, resorts, and hotels in Lagonoy, Camarines Sur. We bridge the gap between local businesses and consumers by providing a centralized marketplace for unique local offerings.</p>
        
        <div class="bg-gray-100 rounded-lg p-6 mb-10 border-l-4 border-blue-500">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Our Mission</h2>
            <p class="text-gray-700">To enhance the visibility of Lagonoy's local businesses, boost tourism, and support sustainable economic growth in our community while preserving our cultural heritage.</p>
        </div>

        <h2 class="text-2xl font-bold mb-6 text-gray-800">Our Values</h2>
        <div class="flex flex-wrap justify-between mb-12">
            <div class="basis-full md:basis-[30%] bg-white p-6 rounded-lg shadow-md mb-6 md:mb-0 hover:translate-y-[-5px] transition-transform">
                <div class="text-4xl mb-4">🌱</div>
                <h3 class="text-xl font-bold mb-2">Sustainability</h3>
                <p class="text-gray-600">Promoting eco-friendly practices and supporting local sustainable businesses.</p>
            </div>
            <div class="basis-full md:basis-[30%] bg-white p-6 rounded-lg shadow-md mb-6 md:mb-0 hover:translate-y-[-5px] transition-transform">
                <div class="text-4xl mb-4">🤝</div>
                <h3 class="text-xl font-bold mb-2">Community</h3>
                <p class="text-gray-600">Strengthening local connections and fostering economic growth for all residents.</p>
            </div>
            <div class="basis-full md:basis-[30%] bg-white p-6 rounded-lg shadow-md hover:translate-y-[-5px] transition-transform">
                <div class="text-4xl mb-4">🎯</div>
                <h3 class="text-xl font-bold mb-2">Excellence</h3>
                <p class="text-gray-600">Delivering high-quality services and authentic local experiences.</p>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-6 text-gray-800">The Development Team</h2>
        <div class="flex flex-wrap justify-around mb-12">
            <div class="basis-full md:basis-[30%] bg-white border border-gray-200 rounded-lg p-5 text-center shadow-sm mb-6 md:mb-0 hover:translate-y-[-5px] transition-transform">
                <img src="{{ asset('images/john-vincent.jpg') }}" alt="John Vincent L. Villarin" class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                <h3 class="text-xl font-bold mb-1">John Vincent L. Villarin</h3>
                <p class="text-blue-600 mb-2">Lead Developer</p>
                <p class="text-gray-600 text-sm">jvloriaga572.pbox@parsu.edu.ph</p>
            </div>
            <div class="basis-full md:basis-[30%] bg-white border border-gray-200 rounded-lg p-5 text-center shadow-sm mb-6 md:mb-0 hover:translate-y-[-5px] transition-transform">
                <img src="{{ asset('images/ranel.jpg') }}" alt="Ranel D. Carulla" class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                <h3 class="text-xl font-bold mb-1">Ranel D. Carulla</h3>
                <p class="text-blue-600 mb-2">Lead Data Manager</p>
                <p class="text-gray-600 text-sm">rdcarulla507.pbox@parsu.edu.ph</p>
            </div>
            <div class="basis-full md:basis-[30%] bg-white border border-gray-200 rounded-lg p-5 text-center shadow-sm hover:translate-y-[-5px] transition-transform">
                <img src="{{ asset('images/jason.jpg') }}" alt="Jason P. Villareal" class="w-32 h-32 rounded-full object-cover mx-auto mb-4">
                <h3 class="text-xl font-bold mb-1">Jason P. Villareal</h3>
                <p class="text-blue-600 mb-2">Lead Documenter</p>
                <p class="text-gray-600 text-sm">jpvillareal571.pbox@parsu.edu.ph</p>
            </div>
        </div>
        
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Institutional Affiliation</h2>
        <p class="mb-12 text-gray-700">This project was developed by students from the <strong>College of Engineering and Computational Sciences</strong> at <strong>Partido State University</strong> as part of their capstone/thesis requirements in the Department of Computational Sciences.</p>
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