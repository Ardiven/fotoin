<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FotoIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
        }
        
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a'
                        }
                    },
                    animation: {
                        'bounce-slow': 'bounce 2s infinite',
                    }
                }
            }
        }
    </script>
    <style>
        .category-overlay {
            transition: all 0.3s ease;
        }
        .category-overlay:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-200">
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('index') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('storage/icon/image.png') }}" alt="Logo" class="h-10 w-auto">
                        <span class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">FotoIn</span>
                    </a>
                    
                </div>
                
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Search products..." 
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-400 transition-all">
                    
                    <button onclick="toggleDarkMode()" 
                        class="p-2 rounded-lg bg-primary-500 text-white hover:bg-primary-600 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <!-- Cover Foto -->
        <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722163/AbhirajK/Abhirajk%20mykare.webp"
             alt="Alex Johnson"
             class="w-full h-64 object-cover">
  
        <div class="p-6">
            <!-- Nama Fotografer -->
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Alex Johnson</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Wedding & Prewedding Specialist</p>
  
            <!-- Tentang -->
            <div class="mt-4">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">About</h2>
                <p class="text-gray-700 dark:text-gray-300 mt-2">
                    Experienced photographer with 10+ years in wedding and event photography. Passionate about capturing timeless moments.
                </p>
            </div>
  
            <!-- Paket Layanan -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Photography Packages</h2>
  
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Paket 1 -->
                    <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden">
                        <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722161/AbhirajK/Abhirajk2.webp"
                             alt="Winter Special"
                             class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Winter Special Package</h3>
                            <p class="text-primary-600 font-semibold mt-1">₹129.99</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                3-hour photo session, 50 edited photos, includes travel within city.
                            </p>
                            <a href="{{ route('booking') }}"
                            class="mt-4 w-full block text-center bg-primary-500 text-white py-2 rounded hover:bg-primary-600 transition">
                            Book Now
                            </a>
                        </div>
                    </div>

                    <!-- Paket 2 -->
                    <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden">
                        <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722161/AbhirajK/Abhirajk2.webp"
                             alt="Summer Special"
                             class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Summer Special Package</h3>
                            <p class="text-primary-600 font-semibold mt-1">₹159.99</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                5-hour photo session, 100 edited photos, includes travel within city and one album.
                            </p>
                            <a href="{{ route('booking') }}"
                            class="mt-4 w-full block text-center bg-primary-500 text-white py-2 rounded hover:bg-primary-600 transition">
                            Book Now
                            </a>
                        </div>
                    </div>

                    <!-- Paket 3 -->
                    <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden">
                        <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722161/AbhirajK/Abhirajk2.webp"
                             alt="Premium Package"
                             class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Premium Package</h3>
                            <p class="text-primary-600 font-semibold mt-1">₹249.99</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                Full-day photo session, 200 edited photos, travel anywhere, two albums, and video coverage.
                            </p>
                            <a href="{{ route('booking') }}"
                            class="mt-4 w-full block text-center bg-primary-500 text-white py-2 rounded hover:bg-primary-600 transition">
                            Book Now
                            </a>

                        </div>
                    </div>
                    <!-- Tambah Paket lain jika perlu -->
                </div>
            </div>

            <!-- Portfolio -->
            <div class="mt-10">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Portfolio</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <img src="{{asset('storage/portofolio/1.jpg')}}" class="rounded-lg object-cover h-40 w-full" alt="Portfolio 1">
                    <img src="{{asset('storage/portofolio/2.jpg')}}" class="rounded-lg object-cover h-40 w-full" alt="Portfolio 2">
                    <img src="{{asset('storage/portofolio/3.jpg')}}" class="rounded-lg object-cover h-40 w-full" alt="Portfolio 3">
                    <img src="{{asset('storage/portofolio/4.jpg')}}" class="rounded-lg object-cover h-40 w-full" alt="Portfolio 4">
                    <img src="{{asset('storage/portofolio/5.jpg')}}" class="rounded-lg object-cover h-40 w-full" alt="Portfolio 5">
                    <img src="{{asset('storage/portofolio/7.jpg')}}" class="rounded-lg object-cover h-40 w-full" alt="Portfolio 6">
                </div>
            </div>

            <!-- Sosial Media -->
            {{-- <div class="mt-10">
                <h3 class="text-md font-semibold text-gray-700 dark:text-gray-200">Connect</h3>
                <div class="flex space-x-4 mt-2">
                    <a href="#" class="text-blue-500 hover:text-blue-600">Facebook</a>
                    <a href="#" class="text-blue-400 hover:text-blue-500">Twitter</a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-gray-900">Portfolio</a>
                </div>
            </div> --}}
        </div>
    </div>
</div>

  <footer class="bg-primary-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-primary-800 dark:text-white mb-4">About Us</h3>
                <p class="text-primary-700 dark:text-gray-300">Your premier destination for fashion and style.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-primary-800 dark:text-white mb-4">Quick Links</h3>
                <ul class="space-y-2 text-primary-700 dark:text-gray-300">
                    <li class="hover:text-primary-500 transition-colors">Home</li>
                    <li class="hover:text-primary-500 transition-colors">Shop</li>
                    <li class="hover:text-primary-500 transition-colors">Categories</li>
                    <li class="hover:text-primary-500 transition-colors">Contact</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-primary-800 dark:text-white mb-4">Contact Us</h3>
                <p class="text-primary-700 dark:text-gray-300">Email: info@shopstyle.com</p>
                <p class="text-primary-700 dark:text-gray-300">Phone: (555) 123-4567</p>
            </div>
        </div>
    </div>
</footer>
</body>
</html>