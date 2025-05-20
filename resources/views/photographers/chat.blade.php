<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
     <script>
        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
        }
        
        function togglePackageModal() {
            const modal = document.getElementById('packageModal');
            modal.classList.toggle('hidden');
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
    <title>FotoIn Chat</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        .chat-container {
            height: calc(100vh - 4rem); /* Menghitung tinggi container dengan mempertimbangkan navbar */
        }
        .message-container {
            height: calc(100% - 4rem); /* Menghitung tinggi area pesan dengan mempertimbangkan header chat */
        }
        .messages-area {
            height: calc(100% - 4rem); /* Menghitung tinggi area pesan dengan mempertimbangkan form input */
        }
    </style>
</head>
<body class="flex flex-col h-screen" x-data="{ packageModalOpen: false }">
    <!-- Navbar -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('index') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('storage/icon/image.png') }}" alt="Logo" class="h-10 w-auto">
                        <span class="text-2xl font-bold text-gray-800 dark:text-white leading-tight">FotoIn</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-2">
                    <input type="text" placeholder="Search products..." 
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-400 transition-all">
                    
                    <button onclick="toggleDarkMode()" 
                        class="p-2 rounded-lg bg-primary-500 text-white hover:bg-primary-600 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    <a href="#" class="pl-2 rounded-lg text-white hover:scale-125 transition-transform">
                        <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="Chat" class="h-6 w-6 rounded-full">
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Chat Container -->
    <div class="flex flex-1 overflow-hidden chat-container">
        <!-- Sidebar daftar pengguna -->
        <aside class="w-1/4 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
            <div class="p-4 font-bold text-lg border-b dark:text-white dark:border-gray-700">Chat Users</div>
            <ul class="divide-y dark:divide-gray-700">
                <li class="p-4 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer dark:text-white">Jonathan</li>
                <li class="p-4 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer dark:text-white">Steven</li>
                <li class="p-4 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer dark:text-white">Victor</li>
            </ul>
        </aside>

        <!-- Chat utama -->
        <div class="flex flex-col flex-1 bg-gray-50 dark:bg-gray-900">
            <!-- Header chat -->
            <header class="p-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700 font-semibold shadow dark:text-white">Chat with Jonathan</header>

            <!-- Area pesan -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-900 messages-area">
                <!-- Pesan masuk -->
                <div class="flex items-start space-x-2">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow max-w-xs dark:text-white">Halo, bro. Gimana kabarnya?</div>
                </div>
                <!-- Pesan keluar -->
                <div class="flex items-start justify-end space-x-2">
                    <div class="bg-primary-500 text-white p-3 rounded-lg shadow max-w-xs">Baik, bro. Kamu gimana?</div>
                </div>
                <!-- Tambahkan beberapa pesan untuk contoh -->
                <div class="flex items-start space-x-2">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow max-w-xs dark:text-white">Baik juga, lagi ngapain?</div>
                </div>
                <div class="flex items-start justify-end space-x-2">
                    <div class="bg-primary-500 text-white p-3 rounded-lg shadow max-w-xs">Lagi belajar buat aplikasi chat pakai Laravel dan Tailwind</div>
                </div>
                <div class="flex items-start space-x-2">
                    <div class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow max-w-xs dark:text-white">Wah keren banget, jadi sudah bisa realtime ya?</div>
                </div>
                
                <!-- Custom Package Message -->
                <div class="flex items-start space-x-2">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md max-w-md dark:text-white">
                        <div class="font-medium text-lg text-primary-600 dark:text-primary-400 mb-2">Custom Photography Package</div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Category:</span>
                                <span class="font-medium">Wedding</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Duration:</span>
                                <span class="font-medium">3 hours</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Price:</span>
                                <span class="font-medium">₹299.99</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Valid Until:</span>
                                <span class="font-medium">June 30, 2025</span>
                            </div>
                            <div class="mt-2">
                                <span class="text-gray-600 dark:text-gray-400 block mb-1">Description:</span>
                                <p class="text-sm">Complete wedding photography package including pre-wedding shots, ceremony coverage, and reception. Includes 100 edited photos.</p>
                            </div>
                            <div class="mt-3 flex justify-end">
                                <button class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded-md text-sm">Accept Offer</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-start justify-end space-x-2">
                    <div class="bg-primary-500 text-white p-3 rounded-lg shadow max-w-xs">Iya, pakai Laravel Echo dan Pusher untuk komunikasi realtime</div>
                </div>
            </div>

            <!-- Form input -->
            <form class="p-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700 flex items-center space-x-2">
                <input
                    type="text"
                    placeholder="Ketik pesan..."
                    class="flex-1 border dark:border-gray-600 rounded-full px-4 py-2 focus:outline-none focus:ring focus:ring-primary-200 dark:bg-gray-700 dark:text-white"
                />
                <button 
                    type="button" 
                    @click="packageModalOpen = true"
                    class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-white p-2 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </button>
                <button
                    type="submit"
                    class="bg-primary-500 text-white px-4 py-2 rounded-full hover:bg-primary-600 transition"
                >
                    Kirim
                </button>
            </form>
        </div>
    </div>

    <!-- Package Modal -->
    <div x-show="packageModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full max-h-screen overflow-y-auto" @click.away="packageModalOpen = false">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Send Custom Package</h3>
                    <button @click="packageModalOpen = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form>
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
                    
                    <div class="mt-5 flex justify-end space-x-2">
                        <button type="button" @click="packageModalOpen = false" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded-lg hover:bg-primary-600">
                            Send Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>