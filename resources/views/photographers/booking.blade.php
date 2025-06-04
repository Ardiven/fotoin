<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Session</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
<body class="bg-gray-50">
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
    <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Book a Photoshoot Session</h1>
            <div class="text-orange-500 font-bold bg-orange-100 px-3 py-1 rounded-lg">Beta</div>
        </div>
        
        <form action="/bookings" method="POST">
            @csrf
            <!-- Photographer Selection -->
            <div class="mb-4">
                <label for="photographer" class="text-gray-700 font-medium">Select Photographer</label>
                <select id="photographer" name="photographer_id" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    <option value="">Choose Photographer</option>
                    <option value="1">Alex Johnson</option>
                    <option value="2">Jane Smith</option>
                </select>
            </div>

            <!-- Date Selection -->
            <div class="mb-4">
                <label for="date" class="text-gray-700 font-medium">Select Date</label>
                <input type="date" id="date" name="date" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>

            <!-- Time Selection -->
            <div class="mb-4">
                <label for="time" class="text-gray-700 font-medium">Select Time</label>
                <input type="time" id="time" name="time" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>

            <!-- Package Selection -->
            <div class="mb-4">
                <label for="package" class="text-gray-700 font-medium">Select Package</label>
                <select id="package" name="package" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
                    <option value="">Choose Package</option>
                    <option value="basic">Basic Session - Rp 500.000</option>
                    <option value="premium">Premium Session - Rp 1.200.000</option>
                    <option value="wedding">Wedding Package - Rp 3.500.000</option>
                </select>
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label for="location" class="text-gray-700 font-medium">Location</label>
                <input type="text" id="location" name="location" placeholder="Enter location details" class="w-full px-4 py-2 mt-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-700 text-white py-3 px-4 rounded-lg hover:bg-blue-800 transition-colors font-medium">
                    Booking Sekarang
                </button>
            </div>
        </form>

        <div class="mt-6 flex justify-center space-x-4 text-sm text-gray-600">
            <div class="flex flex-col items-center">
                <span class="font-bold text-blue-700">1,200+</span>
                <span>Fotografer Aktif</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="font-bold text-blue-700">24+</span>
                <span>Kota di Indonesia</span>
            </div>
            <div class="flex flex-col items-center">
                <span class="font-bold text-blue-700">15K+</span>
                <span>Momen Terabadikan</span>
            </div>
        </div>
    </div>
</body>
</html>