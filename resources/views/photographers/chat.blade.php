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
                            50: '#eef9ff',
                            100: '#dcf2ff',
                            200: '#b8e8ff',
                            300: '#7ad9ff',
                            400: '#38c2ff',
                            500: '#169fe8',
                            600: '#0c7dcb',
                            700: '#1262a5',
                            800: '#1a5288',
                            900: '#1c4872'
                        },
                        secondary: {
                            50: '#fff9eb',
                            100: '#ffefc9',
                            200: '#ffdc8a',
                            300: '#ffc342',
                            400: '#ffa60d',
                            500: '#ff8c00',
                            600: '#e06a00',
                            700: '#b44d04',
                            800: '#943c0c',
                            900: '#7a330f'
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
            background-color: #f0f5ff;
        }
        .chat-container {
            height: calc(100vh - 4rem);
        }
        .message-container {
            height: calc(100% - 4rem);
        }
        .messages-area {
            height: calc(100% - 4rem);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>
<body class="flex flex-col h-screen bg-gray-50" x-data="{ packageModalOpen: false }">
    <!-- Navbar -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('index') }}" class="flex items-center space-x-2">
                        <img src="{{ asset('storage/icon/image.png') }}" alt="Logo" class="h-10 w-auto">
                        <span class="text-2xl font-bold">
                            <span class="text-primary-700">Foto</span><span class="text-secondary-500">in</span>
                        </span>
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
                    <a href="{{ route('chat') }}" class="pl-2 rounded-lg text-white hover:scale-125 transition-all duration-300">
                        <svg class ="h-7 w-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" width="200" height="200"> <path d="M60 10 C85 10, 105 30, 105 55 C105 80, 85 100, 60 100 C55 100, 50 99, 45 97 L20 110 L30 85 C15 75, 15 60, 15 55 C15 30, 35 10, 60 10Z"fill="white"stroke="black"stroke-width="5"/><circle cx="45" cy="55" r="4" fill="black" /><circle cx="60" cy="55" r="4" fill="black" /><circle cx="75" cy="55" r="4" fill="black"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Chat Container -->
    <div class="flex flex-1 overflow-hidden chat-container bg-gray-100">
        <!-- Sidebar daftar pengguna -->
        <aside class="w-1/4 bg-white border-r border-gray-200 dark:border-gray-700 overflow-y-auto">
            <div class="p-4 font-bold text-lg border-b dark:text-white dark:border-gray-700 flex items-center justify-between">
                <span>Chat Users</span>
                <button class="text-primary-600 hover:text-primary-700 text-sm font-normal">+ New</button>
            </div>
            <div class="p-3">
                <div class="relative">
                    <input type="text" placeholder="Search chats..." 
                        class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <ul class="divide-y">
                <li class="p-4 bg-primary-50 border-l-4 border-primary-500 cursor-pointer flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0 relative">
                        <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-primary-700">Jonathan</span>
                            <span class="text-xs text-gray-500">3m ago</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Lagi belajar buat aplikasi chat...</p>
                    </div>
                </li>
                <li class="p-4 hover:bg-gray-50 cursor-pointer flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Steven</span>
                            <span class="text-xs text-gray-500">1h ago</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Bagaimana dengan proyek kita?</p>
                    </div>
                </li>
                <li class="p-4 hover:bg-gray-50 cursor-pointer flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex-shrink-0">
                        <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Victor</span>
                            <span class="text-xs text-gray-500">2d ago</span>
                        </div>
                        <p class="text-sm text-gray-600 truncate">Terima kasih atas foto-fotonya!</p>
                    </div>
                </li>
            </ul>
        </aside>

        <!-- Chat utama -->
        <div class="flex flex-col flex-1 bg-gray-50">
            <!-- Header chat -->
            <header class="p-4 bg-white border-b font-medium shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gray-300">
                        <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <div class="font-medium">Jonathan</div>
                        <div class="text-xs text-gray-500">Online</div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button class="text-gray-500 hover:text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </button>
                    <button class="text-gray-500 hover:text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Area pesan -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 messages-area">
                <!-- Pesan masuk -->
                <div class="flex items-start space-x-2 max-w-xs">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0">
                        <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <p>Halo, bro. Gimana kabarnya?</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 ml-1">9:32 AM</div>
                    </div>
                </div>
                
                <!-- Pesan keluar -->
                <div class="flex items-start justify-end space-x-2">
                    <div class="flex flex-col items-end">
                        <div class="bg-primary-600 text-white p-3 rounded-lg shadow-sm max-w-xs">
                            <p>Baik, bro. Kamu gimana?</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 mr-1">9:33 AM</div>
                    </div>
                </div>
                
                <!-- Tambahkan beberapa pesan untuk contoh -->
                <div class="flex items-start space-x-2 max-w-xs">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0">
                        <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <p>Baik juga, lagi ngapain?</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 ml-1">9:34 AM</div>
                    </div>
                </div>
                
                <div class="flex items-start justify-end space-x-2">
                    <div class="flex flex-col items-end">
                        <div class="bg-primary-600 text-white p-3 rounded-lg shadow-sm max-w-xs">
                            <p>Lagi belajar buat aplikasi chat pakai Laravel dan Tailwind</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 mr-1">9:35 AM</div>
                    </div>
                </div>
                
                <div class="flex items-start space-x-2 max-w-xs">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0">
                        <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <p>Wah keren banget, jadi sudah bisa realtime ya?</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 ml-1">9:36 AM</div>
                    </div>
                </div>
                
                <!-- Custom Package Message -->
                <div class="flex items-start space-x-2">
                    <div class="w-8 h-8 rounded-full bg-gray-300 flex-shrink-0">
                        <img src="https://i.pinimg.com/736x/e7/29/b3/e729b3d73b621c92997f3bb3e1961c6a.jpg" alt="User" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div>
                        <div class="bg-white p-4 rounded-lg shadow-sm max-w-md">
                            <div class="font-medium text-lg text-primary-600 mb-2 flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>Custom Photography Package</span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Category:</span>
                                    <span class="font-medium">Wedding</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-medium">3 hours</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Price:</span>
                                    <span class="font-medium">₹299.99</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Valid Until:</span>
                                    <span class="font-medium">June 30, 2025</span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-gray-600 block mb-1">Description:</span>
                                    <p class="text-sm">Complete wedding photography package including pre-wedding shots, ceremony coverage, and reception. Includes 100 edited photos.</p>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <a href="{{route('payment')}}" class="bg-primary-600 hover:bg-primary-700 text-white px-3 py-1 rounded-md text-sm">Accept Offer</a>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 ml-1">9:38 AM</div>
                    </div>
                </div>
                
                <div class="flex items-start justify-end space-x-2">
                    <div class="flex flex-col items-end">
                        <div class="bg-primary-600 text-white p-3 rounded-lg shadow-sm max-w-xs">
                            <p>Iya, pakai Laravel Echo dan Pusher untuk komunikasi realtime</p>
                        </div>
                        <div class="text-xs text-gray-500 mt-1 mr-1">9:40 AM</div>
                    </div>
                </div>
            </div>

            <!-- Form input -->
            <form class="p-4 bg-white border-t flex items-center space-x-3">
                <button type="button" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </button>
                <input
                    type="text"
                    placeholder="Ketik pesan..."
                    class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-300 text-sm"
                />
                <button 
                    type="button" 
                    @click="packageModalOpen = true"
                    class="p-2 rounded-full text-gray-500 hover:bg-gray-100 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </button>
                <button
                    type="submit"
                    class="bg-primary-600 text-white px-4 py-2 rounded-full hover:bg-primary-700 transition flex items-center space-x-1"
                >
                    <span>Kirim</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
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
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full max-h-screen overflow-y-auto" @click.away="packageModalOpen = false">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Send Custom Package</h3>
                    <button @click="packageModalOpen = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form>
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="category" class="text-sm font-medium text-gray-700 block mb-2">Category</label>
                            <input type="text" id="category" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-primary-500 focus:border-primary-500" placeholder="Wedding" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="duration" class="text-sm font-medium text-gray-700 block mb-2">Duration (in hours)</label>
                            <input type="number" id="duration" name="duration" min="1" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-primary-500 focus:border-primary-500" placeholder="e.g., 2" required>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="price" class="text-sm font-medium text-gray-700 block mb-2">Price</label>
                            <input type="number" id="price" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-primary-500 focus:border-primary-500" placeholder="₹199.99" required>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="valid-to" class="text-sm font-medium text-gray-700 block mb-2">Valid To</label>
                            <input type="date" id="valid-to" name="valid-to" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-primary-500 focus:border-primary-500">
                        </div>

                        <div class="col-span-6">
                            <label for="description" class="text-sm font-medium text-gray-700 block mb-2">Description</label>
                            <textarea id="description" rows="3" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg block w-full p-2.5 focus:ring-primary-500 focus:border-primary-500" placeholder="Describe your package..."></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" @click="packageModalOpen = false" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 text-sm font-medium">
                            Send Package
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>