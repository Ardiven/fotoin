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
    <!-- SVG Background -->
    <svg xmlns="http://www.w3.org/2000/svg" class="fixed top-0 left-0 z-[-1] w-full h-full opacity-10" viewBox="0 0 1440 810" preserveAspectRatio="xMinYMin slice">
        <path fill="#14b8a6" opacity="0.5" d="M0,192L48,208C96,224,192,256,288,250.7C384,245,480,203,576,186.7C672,171,768,181,864,197.3C960,213,1056,235,1152,229.3C1248,224,1344,192,1392,176L1440,160L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
    </svg>

    <!-- Navigation -->
    
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

    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-primary-50 to-white dark:from-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-primary-800 dark:text-white mb-8 animate-bounce-slow">Featured Collection</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @for($i = 0; $i < 4; $i++)
                
                <!-- Product Cards with Enhanced Design -->
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden transform transition-all hover:-translate-y-1 hover:shadow-2xl">
                    <!-- Foto Profil Fotografer -->
                    <a href="{{route('show')}}">
                    <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722163/AbhirajK/Abhirajk%20mykare.webp" alt="Alex Johnson" class="w-full h-48 object-cover">
                  
                    <div class="p-6">
                      <!-- Nama dan Role -->
                      <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Alex Johnson</h2>
                      <p class="text-gray-600 dark:text-gray-400 text-sm">Wedding & Prewedding Specialist</p>
                  
                      <!-- Info Paket -->
                      <div class="mt-4">
                        <h3 class="text-md font-medium text-gray-700 dark:text-gray-200">Winter Special Package</h3>
                        <div class="flex justify-between items-center mt-2">
                          <p class="text-primary-600 font-bold">Start From Rp.1.000.000</p>
                          {{-- <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Limited</span> --}}
                        </div>
                      </div>
                  
                      <!-- Tombol Aksi -->
                      {{-- <button class="mt-4 w-full bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition-colors">
                        Book Now
                      </button>
                   --}}
                      
                    </div>
                    </a> 
                  </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Featured Categories with Interactive SVG -->
    <div class="bg-white dark:bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-primary-800 dark:text-white mb-8">Featured Categories</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="relative category-overlay overflow-hidden rounded-2xl shadow-lg">
                    <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722161/AbhirajK/Abhirajk3.webp" 
                        alt="Men's Wear" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-primary-500 bg-opacity-40 hover:bg-opacity-50 transition-all duration-300 rounded-2xl"></div>
                    <span class="absolute bottom-4 left-4 text-white font-semibold text-xl drop-shadow-lg">Nearby</span>
                </div>
                <div class="relative category-overlay overflow-hidden rounded-2xl shadow-lg">
                    <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722160/AbhirajK/Abhirajk4.webp" 
                        alt="Accessories" class="w-full h-64 object-cover">
                    <div class="absolute inset-0 bg-primary-500 bg-opacity-40 hover:bg-opacity-50 transition-all duration-300 rounded-2xl"></div>
                    <span class="absolute bottom-4 left-4 text-white font-semibold text-xl drop-shadow-lg">Explore by City</span>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Footer with SVG Elements -->
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