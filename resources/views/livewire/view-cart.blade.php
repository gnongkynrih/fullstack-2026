<div class="min-h-screen bg-gray-50">
    <div class="max-w-md mx-auto bg-white min-h-screen">
        <!-- Header -->
        <div class="sticky top-0 z-10 bg-white shadow-sm border-b p-4">
            <div class="flex items-center justify-between">
                <button
                    wire:click="back"
                    class="p-2 hover:bg-gray-100 rounded-full"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h1 class="text-lg font-semibold">Your Cart</h1>
                <div class="w-8"></div> <!-- Spacer -->
            </div>
        </div>

        <!-- Cart Items -->
        <div class="p-4 space-y-4">
            @foreach($cartItems as $item)
                <div class="bg-white rounded-lg shadow-sm border p-4">
                    <div class="flex items-start space-x-3">
                        

                        <!-- Item Details -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 truncate">{{ $item->menuItem->name }}</h3>
                            @if($item->menuItem->description)
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $item->menuItem->description }}</p>
                            @endif
                            <p class="text-sm font-medium text-primary-600 mt-1">₹{{ number_format($item->unit_price, 2) }}</p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center space-x-2">
                            <button
                                wire:click="decreaseQuantity({{ $item->id }})"
                                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <span class="w-8 text-center font-medium">{{ $item->quantity }}</span>
                            <button
                                wire:click="increaseQuantity({{ $item->id }})"
                                class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <div class="mt-3 flex justify-between items-center">
                        <button
                            wire:click="removeItem({{ $item->id }})"
                            class="text-red-500 text-sm hover:text-red-700"
                        >
                            Remove
                        </button>
                        <span class="font-semibold text-gray-900">₹{{ number_format($item->line_total, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="sticky bottom-0 bg-white border-t p-4 space-y-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold">Total</span>
                <span class="text-lg font-bold text-primary-600">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>

            <x-button
                wire:click="placeOrder"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white py-3 text-lg font-semibold"
                label="Place Order"
            />
        </div>
    </div>
</div>

