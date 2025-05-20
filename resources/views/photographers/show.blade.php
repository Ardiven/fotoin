<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FotoIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
                        },
                        secondary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12'
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
                        <span class="text-2xl font-bold">
                            <span class="text-primary-700">Foto</span><span class="text-secondary-500">in</span>
                        </span>
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
                <h2 class="text-lg font-semibold text-primary-700 dark:text-gray-200">About</h2>
                <p class="text-gray-700 dark:text-gray-300 mt-2">
                    Experienced photographer with 10+ years in wedding and event photography. Passionate about capturing timeless moments.
                </p>
            </div>
  
            <!-- Paket Layanan -->
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-primary-700 dark:text-gray-200 mb-4">Photography Packages</h2>
  
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Paket 1 -->
                    <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden">
                        <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722161/AbhirajK/Abhirajk2.webp"
                             alt="Winter Special"
                             class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Winter Special Package</h3>
                            <p class="text-secondary-500 font-semibold mt-1">₹129.99</p>
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
                            <p class="text-secondary-500 font-semibold mt-1">₹159.99</p>
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
                            <p class="text-secondary-500 font-semibold mt-1">₹249.99</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                Full-day photo session, 200 edited photos, travel anywhere, two albums, and video coverage.
                            </p>
                            <a href="{{ route('booking') }}"
                            class="mt-4 w-full block text-center bg-primary-500 text-white py-2 rounded hover:bg-primary-600 transition">
                            Book Now
                            </a>

                        </div>
                    </div>
                    <!-- Custom Package with Modal -->
                    <div x-data="{ open: false }" class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden">
                        <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722161/AbhirajK/Abhirajk2.webp"
                            alt="Custom Package"
                            class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">Custom Package</h3>
                            <p class="text-secondary-500 font-semibold mt-1">Custom Price</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                You can customize your package based on your needs.
                            </p>
                            <button @click="open = true" class="mt-4 w-full block text-center bg-primary-500 text-white py-2 rounded hover:bg-primary-600 transition">Edit Package</button>

                            <!-- Modal -->
                            <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                <div @click.away="open = false" class="bg-white dark:bg-gray-800 border-4 rounded-lg shadow w-full max-w-3xl mx-auto">

                                    <!-- Header -->
                                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Package</h3>
                                        <button @click="open = false" type="button" class="text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Body -->
                                    <div class="p-6 space-y-6">
                                        <form action="#">
                                            <div class="grid grid-cols-6 gap-6">
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="category" class="text-sm font-medium text-gray-900 dark:text-white block mb-2">Category</label>
                                                    <input type="text" id="category" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white sm:text-sm rounded-lg block w-full p-2.5" placeholder="Wedding" required>
                                                </div>
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="duration" class="text-sm font-medium text-gray-900 dark:text-white block mb-2">Duration (in hours)</label>
                                                    <input type="number" id="duration" name="duration" min="1" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white sm:text-sm rounded-lg block w-full p-2.5" placeholder="e.g., 2" required>
                                                </div>

                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="price" class="text-sm font-medium text-gray-900 dark:text-white block mb-2">Price</label>
                                                    <input type="number" id="price" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white sm:text-sm rounded-lg block w-full p-2.5" placeholder="₹199.99" required>
                                                </div>
                                                <div class="col-span-6 sm:col-span-3">
                                                    <label for="valid-to" class="text-sm font-medium text-gray-900 dark:text-white block mb-2">Valid To</label>
                                                    <input type="date" id="valid-to" name="valid-to" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white sm:text-sm rounded-lg block w-full p-2.5">
                                                </div>

                                                <div class="col-span-6">
                                                    <label for="description" class="text-sm font-medium text-gray-900 dark:text-white block mb-2">Description</label>
                                                    <textarea id="description" rows="3" class="shadow-sm bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white sm:text-sm rounded-lg block w-full p-2.5" placeholder="Describe your package..."></textarea>
                                                </div>
                                            </div>
                                            <div class="mt-6 flex justify-end">
                                                <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Modal -->

                        </div>
                    </div>

                </div>
            </div>

            <!-- Portfolio -->
            <div class="mt-10">
                <h2 class="text-lg font-semibold text-primary-700 dark:text-gray-200 mb-4">Portfolio</h2>
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
                <h3 class="text-lg font-semibold text-primary-700 dark:text-white mb-4">About Us</h3>
                <p class="text-primary-600 dark:text-gray-300">Your premier destination for professional photography.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-primary-700 dark:text-white mb-4">Quick Links</h3>
                <ul class="space-y-2 text-primary-600 dark:text-gray-300">
                    <li class="hover:text-secondary-500 transition-colors">Home</li>
                    <li class="hover:text-secondary-500 transition-colors">Photographers</li>
                    <li class="hover:text-secondary-500 transition-colors">Categories</li>
                    <li class="hover:text-secondary-500 transition-colors">Contact</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-primary-700 dark:text-white mb-4">Contact Us</h3>
                <p class="text-primary-600 dark:text-gray-300">Email: info@fotoin.com</p>
                <p class="text-primary-600 dark:text-gray-300">Phone: (555) 123-4567</p>
            </div>
        </div>
    </div>
</footer>

</body>
</html>