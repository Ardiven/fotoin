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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white dark:bg-gray-900 transition-colors duration-200">
    <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Book a Photoshoot Session</h1>
        
        <form action="/bookings" method="POST">
            @csrf
            <!-- Photographer Selection -->
            <div class="mb-4">
                <label for="photographer" class="text-gray-700 dark:text-gray-300">Select Photographer</label>
                <select id="photographer" name="photographer_id" class="w-full px-4 py-2 mt-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-300" required>
                    <option value="">Choose Photographer</option>
                    <!-- Populate photographer options here from database -->
                    <option value="1">Alex Johnson</option>
                    <option value="2">Jane Smith</option>
                </select>
            </div>

            <!-- Date Selection -->
            <div class="mb-4">
                <label for="date" class="text-gray-700 dark:text-gray-300">Select Date</label>
                <input type="date" id="date" name="date" class="w-full px-4 py-2 mt-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-300" required>
            </div>

            <!-- Time Selection -->
            <div class="mb-4">
                <label for="time" class="text-gray-700 dark:text-gray-300">Select Time</label>
                <input type="time" id="time" name="time" class="w-full px-4 py-2 mt-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-300" required>
            </div>

            <!-- Price (display or select a package) -->
            <div class="mb-4">
                <label for="price" class="text-gray-700 dark:text-gray-300">Price</label>
                <select id="price" name="price" class="w-full px-4 py-2 mt-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-300" required>
                    <option value="">Select a Package</option>
                    <option value="129.99">Winter Special Package - ₹129.99</option>
                    <option value="249.99">Premium Package - ₹249.99</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="w-full bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition-colors">
                    Book Now
                </button>
            </div>
        </form>
    </div>
</body>
</html>
