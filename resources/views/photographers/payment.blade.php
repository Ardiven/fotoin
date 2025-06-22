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
                    }
                }
            }
        }
    </script>
    <title>FotoIn Payment</title>
    <style>
        body, html {
            background: linear-gradient(135deg, #f0f5ff 0%, #e6f3ff 100%);
            min-height: 100vh;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .payment-method-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .payment-method-card:hover {
            transform: scale(1.02);
        }
        .payment-method-card.selected {
            border-color: #0c7dcb;
            background-color: #eef9ff;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="paymentData()">
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

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
            <p class="text-gray-600">Secure checkout for your photography package</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <!-- Progress Steps -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-2 text-primary-600">
                            <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                            <span class="font-medium">Package</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-primary-200 mx-4"></div>
                        <div class="flex items-center space-x-2 text-primary-600">
                            <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                            <span class="font-medium">Payment</span>
                        </div>
                        <div class="flex-1 h-0.5 bg-gray-200 mx-4"></div>
                        <div class="flex items-center space-x-2 text-gray-400">
                            <div class="w-8 h-8 bg-gray-200 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">3</div>
                            <span class="font-medium">Confirmation</span>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Choose Payment Method</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4 text-center"
                                 :class="selectedPayment === 'card' ? 'selected' : ''"
                                 @click="selectedPayment = 'card'">
                                <svg class="h-8 w-8 mx-auto mb-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <div class="font-medium">Credit Card</div>
                            </div>
                            <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4 text-center"
                                 :class="selectedPayment === 'upi' ? 'selected' : ''"
                                 @click="selectedPayment = 'upi'">
                                <svg class="h-8 w-8 mx-auto mb-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <div class="font-medium">UPI</div>
                            </div>
                            <div class="payment-method-card border-2 border-gray-200 rounded-lg p-4 text-center"
                                 :class="selectedPayment === 'wallet' ? 'selected' : ''"
                                 @click="selectedPayment = 'wallet'">
                                <svg class="h-8 w-8 mx-auto mb-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div class="font-medium">Wallet</div>
                            </div>
                        </div>
                    </div>

                    <!-- Credit Card Form -->
                    <div x-show="selectedPayment === 'card'" x-transition class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Card Details</h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Card Number</label>
                                <input type="text" placeholder="1234 5678 9012 3456" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                                    <input type="text" placeholder="MM/YY" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                    <input type="text" placeholder="123" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name</label>
                                <input type="text" placeholder="John Doe" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                    </div>

                    <!-- UPI Form -->
                    <div x-show="selectedPayment === 'upi'" x-transition class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">UPI Payment</h4>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">UPI ID</label>
                            <input type="text" placeholder="yourname@upi" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-700">You'll be redirected to your UPI app to complete the payment.</p>
                        </div>
                    </div>

                    <!-- Wallet Form -->
                    <div x-show="selectedPayment === 'wallet'" x-transition class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Digital Wallet</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary-500 focus:border-primary-500 transition">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-600 rounded-lg mx-auto mb-2 flex items-center justify-center text-white font-bold">P</div>
                                    <div class="font-medium">Paytm</div>
                                </div>
                            </button>
                            <button class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary-500 focus:border-primary-500 transition">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-purple-600 rounded-lg mx-auto mb-2 flex items-center justify-center text-white font-bold">G</div>
                                    <div class="font-medium">GPay</div>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Billing Address -->
                    <div class="mb-8">
                        <h4 class="text-md font-semibold text-gray-900 mb-4">Billing Address</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                <input type="text" placeholder="John Doe" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" placeholder="john@example.com" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <input type="text" placeholder="123 Street Name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                                <input type="text" placeholder="Surabaya" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input type="text" placeholder="12345" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 card-hover sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    
                    <!-- Package Details -->
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="h-6 w-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">Wedding Photography Package</h4>
                                <p class="text-sm text-gray-600 mt-1">3 hours coverage</p>
                                <p class="text-sm text-gray-600">100 edited photos</p>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Package Price</span>
                            <span class="text-gray-900">Rp. 3.000.000</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Fee (5%)</span>
                            <span class="text-gray-900">Rp. 150.000</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between font-semibold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-primary-600">Rp. 3.150.000</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                        <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Secured by 256-bit SSL encryption</span>
                    </div>

                    <!-- Pay Button -->
                    <button @click="processPayment()" 
                            :disabled="!selectedPayment"
                            :class="selectedPayment ? 'bg-primary-600 hover:bg-primary-700' : 'bg-gray-300 cursor-not-allowed'"
                            class="w-full text-white py-4 rounded-lg font-semibold text-lg transition-colors flex items-center justify-center space-x-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Pay Rp. 3.150.000</span>
                    </button>

                    <!-- Money Back Guarantee -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">30-day money-back guarantee</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-show="showSuccess" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Payment Successful!</h3>
            <p class="text-gray-600 mb-6">Your booking has been confirmed. You'll receive a confirmation email shortly.</p>
            <button @click="showSuccess = false" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                Continue
            </button>
        </div>
    </div>

    <script>
        function paymentData() {
            return {
                selectedPayment: 'card',
                showSuccess: false,
                processPayment() {
                    // Simulate payment processing
                    this.showSuccess = true;
                }
            }
        }
    </script>
</body>
</html>