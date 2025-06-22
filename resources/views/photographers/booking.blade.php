<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Photography Session - FotoIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }
        .photographer-card {
            transition: all 0.3s ease;
        }
        .photographer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .package-selected {
            border: 2px solid #3b82f6;
            background: #eff6ff;
        }
        .dark .package-selected {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
        }
        .btn-primary:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-primary:hover:before {
            left: 100%;
        }
        .back-btn {
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            transform: translateX(-5px);
        }
        .stats-card {
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: scale(1.05);
        }
        .profile-img {
            transition: all 0.3s ease;
        }
        .profile-img:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-200">
    <!-- SVG Background -->
    <svg xmlns="http://www.w3.org/2000/svg" class="fixed top-0 left-0 z-[-1] w-full h-full opacity-10" viewBox="0 0 1440 810" preserveAspectRatio="xMinYMin slice">
        <path fill="#3b82f6" opacity="0.5" d="M0,192L48,208C96,224,192,256,288,250.7C384,245,480,203,576,186.7C672,171,768,181,864,197.3C960,213,1056,235,1152,229.3C1248,224,1344,192,1392,176L1440,160L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
    </svg>

    <!-- Background Animation -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-20 h-20 bg-primary-500/10 rounded-full floating-animation"></div>
        <div class="absolute top-32 right-20 w-16 h-16 bg-primary-500/5 rounded-full floating-animation" style="animation-delay: -2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-primary-500/5 rounded-full floating-animation" style="animation-delay: -4s;"></div>
        <div class="absolute bottom-32 right-1/3 w-12 h-12 bg-primary-500/10 rounded-full floating-animation" style="animation-delay: -1s;"></div>
    </div>

    <!-- Navigation -->
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

    <!-- Back Button -->
    <div class="relative pt-8 pb-4 fade-in">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <button onclick="history.back()" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-all duration-300 back-btn px-4 py-2 rounded-lg border border-primary-200 dark:border-primary-700 bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-primary-900/30">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Photographer Profile
            </button>
        </div>
    </div>

    <div class="relative bg-gradient-to-br from-primary-50 to-white dark:from-gray-800 dark:to-gray-900 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8 fade-in">
                <h1 class="text-5xl font-bold text-primary-700 dark:text-white mb-4">
                    Book Your Session
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300">Complete your booking details to secure your photoshoot</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Form -->
                <div class="lg:col-span-2 fade-in">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg border border-gray-200 dark:border-gray-700">
                        <form id="bookingForm" action="{{ route('payment') }}" method="GET" class="space-y-6">
                            <!-- Date Selection -->
                            <div class="group">
                                <label for="date" class="block text-gray-700 dark:text-gray-200 font-medium mb-3 text-lg">
                                    <i class="fas fa-calendar-alt mr-3 text-primary-500"></i>Select Date
                                </label>
                                <input type="date" id="date" name="date" 
                                       class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white form-input text-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500" 
                                       required>
                            </div>

                            <!-- Time Selection -->
                            <div class="group">
                                <label for="time" class="block text-gray-700 dark:text-gray-200 font-medium mb-3 text-lg">
                                    <i class="fas fa-clock mr-3 text-primary-500"></i>Select Time
                                </label>
                                <select id="time" name="time" required
                                        class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white form-input text-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500">
                                    <option value="" disabled selected>Select a time</option>
                                    <option value="09:00">09:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="13:00">01:00 PM</option>
                                    <option value="14:00">02:00 PM</option>
                                    <option value="15:00">03:00 PM</option>
                                    <option value="16:00">04:00 PM</option>
                                    <option value="17:00">05:00 PM</option>
                                </select>
                            </div>

                            <!-- Location Type -->
                            <div class="group">
                                <label for="locationType" class="block text-gray-700 dark:text-gray-200 font-medium mb-3 text-lg">
                                    <i class="fas fa-map-marker-alt mr-3 text-primary-500"></i>Location Type
                                </label>
                                <select id="locationType" name="location_type" 
                                        class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white form-input text-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500" required>
                                    <option value="">Choose Location Type</option>
                                    <option value="studio">Studio</option>
                                    <option value="outdoor">Outdoor</option>
                                    <option value="client_home">Client's Home</option>
                                    <option value="venue">Event Venue</option>
                                </select>
                            </div>

                            <!-- Specific Location -->
                            <div class="group">
                                <label for="location" class="block text-gray-700 dark:text-gray-200 font-medium mb-3 text-lg">
                                    <i class="fas fa-location-dot mr-3 text-primary-500"></i>Specific Location
                                </label>
                                <input type="text" id="location" name="location" 
                                       placeholder="Enter detailed address or location name" 
                                       class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 form-input text-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500" 
                                       required>
                            </div>

                            <!-- Additional Notes -->
                            <div class="group">
                                <label for="notes" class="block text-gray-700 dark:text-gray-200 font-medium mb-3 text-lg">
                                    <i class="fas fa-sticky-note mr-3 text-primary-500"></i>Additional Notes (Optional)
                                </label>
                                <textarea id="notes" name="notes" rows="4" 
                                          placeholder="Any special requirements or additional information..." 
                                          class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 resize-none form-input text-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500"></textarea>
                            </div>

                            <!-- Contact Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label for="clientName" class="block text-gray-700 dark:text-gray-200 font-medium mb-3 text-lg">
                                        <i class="fas fa-user mr-3 text-primary-500"></i>Your Name
                                    </label>
                                    <input type="text" id="clientName" name="client_name" 
                                           placeholder="Enter your full name" 
                                           class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 form-input text-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500" 
                                           required>
                                </div>
                                <div class="group">
                                    <label for="clientPhone" class="block text-gray-700 dark:text-gray-200 font-medium mb-3 text-lg">
                                        <i class="fas fa-phone mr-3 text-primary-500"></i>Phone Number
                                    </label>
                                    <input type="tel" id="clientPhone" name="client_phone" 
                                           placeholder="Enter your phone number" 
                                           class="w-full px-6 py-4 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 form-input text-lg focus:ring-2 focus:ring-primary-400 focus:border-primary-500" 
                                           required>
                                </div>
                            </div>

                            <!-- Hidden fields to pass additional data -->
                            <input type="hidden" name="photographer_name" value="Alex Johnson">
                            <input type="hidden" name="package_name" value="Premium Portrait Session">
                            <input type="hidden" name="package_price" value="1500000">

                            <!-- Submit Button -->
                            <div class="pt-6">
                                <button type="submit" class="w-full btn-primary text-white font-bold py-5 px-8 rounded-xl text-xl relative overflow-hidden">
                                    <i class="fas fa-credit-card mr-3"></i>
                                    Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="space-y-6 fade-in">
                    <!-- Photographer Info -->
                    <div class="photographer-card bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="text-center mb-4">
                            <img src="https://res.cloudinary.com/djv4xa6wu/image/upload/v1735722163/AbhirajK/Abhirajk%20mykare.webp" 
                                 alt="Photographer" class="w-24 h-24 rounded-full mx-auto mb-4 border-3 border-primary-300 object-cover profile-img">
                            <h3 class="text-gray-800 dark:text-white font-bold text-xl">Alex Johnson</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">Professional Portrait Photographer</p>
                            <div class="flex items-center justify-center mt-2">
                                <div class="flex text-yellow-400 text-lg mr-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="text-gray-600 dark:text-gray-400 text-sm">4.9 (127 reviews)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Package -->
                    <div class="package-selected rounded-3xl p-6 shadow-lg">
                        <h3 class="text-gray-800 dark:text-white font-bold text-xl mb-4">
                            <i class="fas fa-camera mr-2 text-primary-500"></i>Selected Package
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-800 dark:text-white font-medium text-lg">Wedding Special Package</span>
                            </div>
                            <div class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                                5-hour photo session, 100 edited photos, includes travel within city and one album.
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                <div class="flex justify-between items-center text-xl">
                                    <span class="text-primary-600 dark:text-primary-400 font-bold">Rp. 3.000.000</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Summary -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                        <h3 class="text-gray-800 dark:text-white font-bold text-xl mb-4">
                            <i class="fas fa-clipboard-list mr-2 text-primary-500"></i>Booking Summary
                        </h3>
                        <div class="space-y-4 text-base">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                <span id="summaryDate" class="text-gray-800 dark:text-white font-medium">Select a date</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200 dark:border-gray-600">
                                <span class="text-gray-600 dark:text-gray-400">Time:</span>
                                <span id="summaryTime" class="text-gray-800 dark:text-white font-medium">Select a time</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 dark:text-gray-400">Location:</span>
                                <span id="summaryLocation" class="text-gray-800 dark:text-white font-medium">Enter location</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="stats-card bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-200 dark:border-gray-700">
                        <div class="grid grid-cols-1 gap-6 text-center">
                            <div class="stats-item">
                                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-1">1,200+</div>
                                <div class="text-gray-600 dark:text-gray-400 text-sm">Active Photographers</div>
                            </div>
                            <div class="stats-item">
                                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-1">24+</div>
                                <div class="text-gray-600 dark:text-gray-400 text-sm">Cities in Indonesia</div>
                            </div>
                            <div class="stats-item">
                                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400 mb-1">15K+</div>
                                <div class="text-gray-600 dark:text-gray-400 text-sm">Moments Captured</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                        <li class="hover:text-secondary-500 transition-colors cursor-pointer">Home</li>
                        <li class="hover:text-secondary-500 transition-colors cursor-pointer">Photographers</li>
                        <li class="hover:text-secondary-500 transition-colors cursor-pointer">Categories</li>
                        <li class="hover:text-secondary-500 transition-colors cursor-pointer">Contact</li>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').setAttribute('min', today);

            // Real-time summary updates
            document.getElementById('date').addEventListener('change', function() {
                const date = new Date(this.value);
                const formattedDate = date.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                document.getElementById('summaryDate').textContent = formattedDate;
            });

            document.getElementById('time').addEventListener('change', function() {
                document.getElementById('summaryTime').textContent = this.value || 'Select a time';
            });

            document.getElementById('location').addEventListener('input', function() {
                const location = this.value || 'Enter location';
                document.getElementById('summaryLocation').textContent = location.length > 20 ? 
                    location.substring(0, 20) + '...' : location;
            });

            // Form submission with validation
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                // Validate required fields
                const requiredFields = ['date', 'time', 'locationType', 'location', 'clientName', 'clientPhone'];
                let isValid = true;

                requiredFields.forEach(field => {
                    const element = document.getElementById(field);
                    if (!element.value.trim()) {
                        isValid = false;
                        element.style.borderColor = '#ef4444';
                        element.focus();
                        e.preventDefault(); // Prevent form submission
                    } else {
                        element.style.borderColor = '';
                    }
                });

                if (isValid) {
                    // Show loading state
                    const button = this.querySelector('button[type="submit"]');
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-3"></i>Processing...';
                    button.disabled = true;
                }
            });

            // Add smooth transitions to form inputs
            const inputs = document.querySelectorAll('.form-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
        });
    </script>
</body>
</html>