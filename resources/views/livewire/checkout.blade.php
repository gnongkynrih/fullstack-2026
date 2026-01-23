<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Checkout & Payment</h1>
                    <p class="mt-1 text-sm text-gray-500">Process payment and close table</p>
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
                Select Table to Checkout
            </h2>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-8 gap-3">
                @forelse($tables as $table)
                    <button 
                        wire:click="selectTable({{ $table->id }})"
                        class="group relative p-4 bg-white rounded-lg shadow-sm border-2 transition-all duration-200 hover:shadow-md {{ $selectedTable && $selectedTable->id == $table->id ? 'border-primary-500 bg-gradient-to-br from-primary-50 to-white ring-2 ring-primary-200' : 'border-gray-200 hover:border-primary-300' }}"
                    >
                        <div class="flex flex-col items-center space-y-1.5">
                            <x-icon name="o-plus" class="w-12 h-12 text-primary-600" />
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

        <!-- Payment Details Section -->
        @if($selectedTable && $order)

        <!-- Table Info Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Table {{ $selectedTable->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $tableSession->guest_count }} {{ Str::plural('guest', $tableSession->guest_count) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Session Duration</p>
                    <p class="text-lg font-bold text-primary-600">{{ $tableSession->opened_at->diffForHumans(null, true) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Order Summary
                </h2>
            </div>

            <div class="p-6">
                @forelse($orderItems as $item)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->menuItem->name }}</h3>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                <span class="text-sm text-gray-400">×</span>
                                <span class="text-sm text-gray-600">₹{{ number_format($item->unit_price, 2) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-900">₹{{ number_format($item->line_total, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-8">No items to checkout</p>
                @endforelse
            </div>
        </div>

        <!-- Bill Breakdown -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Bill Details</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold text-gray-900">₹{{ number_format($subtotal, 2) }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Tax (5%)</span>
                    <span class="font-semibold text-gray-900">₹{{ number_format($tax, 2) }}</span>
                </div>
                
                @if($discount > 0)
                    <div class="flex justify-between items-center text-green-600">
                        <span>Discount</span>
                        <span class="font-semibold">-₹{{ number_format($discount, 2) }}</span>
                    </div>
                @endif
                
                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-900">Total Amount</span>
                        <span class="text-2xl font-bold text-primary-600">₹{{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <button 
                    wire:click="$set('paymentMethod', 'cash')"
                    class="p-4 border-2 rounded-lg transition-all {{ $paymentMethod == 'cash' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-primary-300' }}"
                >
                    <div class="flex flex-col items-center space-y-2">
                        <svg class="w-8 h-8 {{ $paymentMethod == 'cash' ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="font-semibold {{ $paymentMethod == 'cash' ? 'text-primary-700' : 'text-gray-700' }}">Cash</span>
                    </div>
                </button>

                <button 
                    wire:click="$set('paymentMethod', 'card')"
                    class="p-4 border-2 rounded-lg transition-all {{ $paymentMethod == 'card' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-primary-300' }}"
                >
                    <div class="flex flex-col items-center space-y-2">
                        <svg class="w-8 h-8 {{ $paymentMethod == 'card' ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="font-semibold {{ $paymentMethod == 'card' ? 'text-primary-700' : 'text-gray-700' }}">Card</span>
                    </div>
                </button>

                <button 
                    wire:click="$set('paymentMethod', 'upi')"
                    class="p-4 border-2 rounded-lg transition-all {{ $paymentMethod == 'upi' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 hover:border-primary-300' }}"
                >
                    <div class="flex flex-col items-center space-y-2">
                        <svg class="w-8 h-8 {{ $paymentMethod == 'upi' ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="font-semibold {{ $paymentMethod == 'upi' ? 'text-primary-700' : 'text-gray-700' }}">UPI</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Action Button -->
        <div>
            <x-button
                label="Complete Payment & Close Table"
                icon="o-check-circle"
                class="w-full bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white font-bold py-4 px-6"
                @click="$wire.showPaymentModal = true"
            />
        </div>

        <!-- Payment Confirmation Modal -->
        <x-modal wire:model="showPaymentModal" title="Confirm Payment" subtitle="Please review before completing">
            <div class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Table:</span>
                        <span class="font-semibold text-gray-900">{{ $selectedTable->name }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-semibold text-gray-900">{{ ucfirst($paymentMethod) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-bold text-primary-600 text-lg">₹{{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start space-x-2">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-yellow-800">This action will close the table and cannot be undone.</p>
                    </div>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancel" @click="$wire.showPaymentModal = false" />
                <x-button 
                    label="Confirm Payment" 
                    icon="o-check-circle"
                    class="btn-primary" 
                    wire:click="processPayment" 
                />
            </x-slot:actions>
        </x-modal>

        @else
        <!-- No Table Selected -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="bg-primary-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Select a Table</h3>
                <p class="text-gray-500">Choose an occupied table from above to process payment and close the table</p>
            </div>
        </div>
        @endif
    </div>
</div>
