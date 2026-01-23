<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100" wire:poll.30s>
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Current Orders</h1>
                    <p class="mt-1 text-sm text-gray-500">Monitor and manage active table orders</p>
                </div>
                <div class="flex items-center space-x-2 bg-primary-50 px-4 py-2 rounded-lg">
                    <svg class="w-5 h-5 text-primary-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="text-sm font-medium text-primary-700">Auto-refresh</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Tables Section -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Occupied Tables
            </h2>
            
            <div class="max-h-[500px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                    @forelse($tables as $table)
                        <button 
                            wire:click="selectTable({{ $table->id }})"
                            class="animate__animated animate__bounceInDown  animate__slower group relative p-4 bg-white rounded-lg shadow-sm border-2 transition-all duration-200 hover:shadow-md {{ $selectedTable && $selectedTable->id == $table->id ? 'border-primary-500 bg-gradient-to-br from-primary-50 to-white ring-2 ring-primary-200' : 'border-gray-200 hover:border-primary-300' }}"
                        >
                            <div class="flex flex-col items-center space-y-1.5">
                                <div class="p-2 rounded-full {{ $selectedTable && $selectedTable->id == $table->id ? 'bg-primary-100' : 'bg-gray-100 group-hover:bg-primary-50' }} transition-colors">
                                    <svg class="w-5 h-5 {{ $selectedTable && $selectedTable->id == $table->id ? 'text-primary-600' : 'text-gray-600 group-hover:text-primary-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-base font-bold {{ $selectedTable && $selectedTable->id == $table->id ? 'text-primary-700' : 'text-gray-700' }}">{{ $table->name }}</span>
                            </div>
                            @if($selectedTable && $selectedTable->id == $table->id)
                                <div class="absolute top-1.5 right-1.5">
                                    <svg class="w-4 h-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </button>
                    @empty
                        <div class="col-span-full">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-gray-500 text-lg font-medium">No occupied tables</p>
                                <p class="text-gray-400 text-sm mt-1">All tables are currently available</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Order Details Section -->
        @if($selectedTable)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Table {{ $selectedTable->name }} - Order Details
                        </h2>
                        <span class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium text-white">
                            {{ count($orderItems) }} {{ Str::plural('item', count($orderItems)) }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    @forelse($orderItems as $item)
                        <div class="p-4 mb-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $item->menuItem->name }}</h3>
                                    <span class="text-lg font-bold text-primary-600">₹{{ number_format($item->unit_price, 2) }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between flex-wrap gap-3">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : ($item->status == 'preparing' ? 'bg-blue-100 text-blue-800 border border-blue-200' : ($item->status == 'prepared' ? 'bg-purple-100 text-purple-800 border border-purple-200' : ($item->status == 'served' ? 'bg-green-100 text-green-800 border border-green-200' : ($item->status == 'returned' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-red-100 text-red-800 border border-red-200')))) }}">
                                        <span class="w-2 h-2 rounded-full mr-2 {{ $item->status == 'pending' ? 'bg-yellow-500' : ($item->status == 'preparing' ? 'bg-blue-500 animate-pulse' : ($item->status == 'prepared' ? 'bg-purple-500' : ($item->status == 'served' ? 'bg-green-500' : ($item->status == 'returned' ? 'bg-orange-500' : 'bg-red-500')))) }}"></span>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                    
                                    <div class="flex items-center space-x-2 bg-white px-3 py-1 rounded-full border border-gray-300">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        <span class="text-sm font-bold text-gray-700">{{ $item->quantity }}</span>
                                    </div>
                                    
                                    <span class="text-sm font-medium text-gray-600">
                                        Total: ₹{{ number_format($item->line_total, 2) }}
                                    </span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-2">
                                    @if($item->status != 'prepared' && $item->status != 'served' && $item->status != 'cancelled' && $item->status != 'returned')
                                        <button 
                                            wire:click="updateItemStatus({{ $item->id }}, 'prepared')"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors flex items-center space-x-1"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Prepared</span>
                                        </button>
                                    @endif
                                    
                                    @if($item->status == 'prepared')
                                        <button 
                                            wire:click="updateItemStatus({{ $item->id }}, 'served')"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors flex items-center space-x-1"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Served</span>
                                        </button>
                                    @endif
                                    
                                    @if($item->status == 'served')
                                        <button 
                                            wire:click="returnItem({{ $item->id }})"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition-colors flex items-center space-x-1"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                            </svg>
                                            <span>Return</span>
                                        </button>
                                        <button 
                                            wire:click="replaceItem({{ $item->id }})"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors flex items-center space-x-1"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span>Replace</span>
                                        </button>
                                    @endif
                                    
                                    @if($item->status != 'served' && $item->status != 'cancelled' && $item->status != 'returned')
                                        <button 
                                            wire:click="updateItemStatus({{ $item->id }}, 'cancelled')"
                                            class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors flex items-center space-x-1"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span>Cancel</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">No items in this order</p>
                            <p class="text-gray-400 text-sm mt-1">This table has no pending items</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div class="bg-primary-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                    </div>
                    <h3 class="animate__animated animate__fadeInDown  animate__slower text-xl font-semibold text-gray-900 mb-2">Select a Table</h3>
                    <p class="text-gray-500 animate__animated animate__backInLeft  animate__slower ">Choose an occupied table from above to view its order details and status</p>
                </div>
            </div>
        @endif
    </div>
</div>
