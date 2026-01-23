<div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center animate__animated animate__fadeInDown">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome Back!</h1>
            <p class="text-xl text-primary-100">What would you like to do today?</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12">
        <!-- Action Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Dine In Card -->
            <a href="{{ route('select-table') }}" class="group block animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 p-8 text-white">
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4 group-hover:scale-110 transition-transform">
                          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="7" r="4" stroke="#000000" stroke-width="2" fill="#e0e0e0"/>
                            <path d="M4 21V19C4 15.6863 6.68629 13 10 13H14C17.3137 13 20 15.6863 20 19V21H4Z" stroke="#000000" stroke-width="2"/>
                          </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Dine In</h2>
                        <p class="text-green-100">Seat customers and take orders</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-green-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Get Started</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- View Order Card -->
            <a href="{{ route('view-order') }}" class="group block animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-8 text-white">
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">View Orders</h2>
                        <p class="text-blue-100">Track and manage active orders</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-blue-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>View All</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Checkout Card -->
            <a href="{{ route('checkout') }}" class="group block animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-8 text-white">
                        <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-2">Checkout</h2>
                        <p class="text-purple-100">Process payments and close tables</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center text-purple-600 font-semibold group-hover:translate-x-2 transition-transform">
                            <span>Process Payment</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Stats Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Quick Actions
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-4 border border-orange-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-orange-600 font-medium">Available Tables</p>
                            <p class="text-2xl font-bold text-orange-700 mt-1">{{ \App\Models\RestaurantTable::where('status', 'available')->count() }}</p>
                        </div>
                        <div class="bg-orange-200 p-3 rounded-full">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-red-600 font-medium">Occupied Tables</p>
                            <p class="text-2xl font-bold text-red-700 mt-1">{{ \App\Models\RestaurantTable::where('status', 'occupied')->count() }}</p>
                        </div>
                        <div class="bg-red-200 p-3 rounded-full">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600 font-medium">Active Orders</p>
                            <p class="text-2xl font-bold text-blue-700 mt-1">{{ \App\Models\Order::where('status', 'open')->count() }}</p>
                        </div>
                        <div class="bg-blue-200 p-3 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600 font-medium">Reserved Tables</p>
                            <p class="text-2xl font-bold text-green-700 mt-1">{{ \App\Models\RestaurantTable::where('status', 'reserved')->count() }}</p>
                        </div>
                        <div class="bg-green-200 p-3 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-2xl shadow-lg p-8 text-white animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
            <div class="flex items-start space-x-4">
                <div class="bg-white/20 p-3 rounded-full flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-2">Quick Tip</h4>
                    <p class="text-primary-100">Use the View Orders page to track order status and update items as they're prepared and served. Don't forget to process checkout when customers are ready to leave!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Spacing -->
    <div class="h-16"></div>
</div>
