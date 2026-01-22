<div class="min-h-screen bg-gray-50">
    <!-- Category Tabs -->
    @include('livewire.sale.show-categories')

    <!-- Menu Items Grid -->
    <div class="px-4 py-4">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach ($menuItems as $item)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Item Details -->
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 text-sm leading-tight mb-2">{{ $item->name }}</h3>
                        {{-- @if($item->description)
                            <p class="text-gray-600 text-xs leading-tight mb-3 line-clamp-2">{{ $item->description }}</p>
                        @endif --}}
                        <div class="mt-6 flex items-center justify-between">
                            <span class="font-bold text-primary-600 text-sm">₹{{ number_format($item->price, 2) }}</span>
                            <x-button
                                wire:click="addToCart({{ $item->id }})"
                                class="bg-primary-500 hover:bg-primary-600 text-white text-xs px-3 py-1 h-auto"
                                label="+ Add"
                            />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Floating Cart -->
    @if($cartItems->count() > 0)
        <div class="fixed bottom-4 left-4 right-4 bg-white rounded-lg shadow-lg border p-4 z-20">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-primary-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.07 5.36M7 13l-1.07 5.36M7 13l-1.07 5.36M20 21a2 2 0 11-4 0 2 2 0 014 0zM9 21a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium">{{ $cartItems->sum('quantity') }} items</p>
                        <p class="text-xs text-gray-600">₹{{ number_format($order->total_amount, 2) }}</p>
                    </div>
                </div>
                <x-button
                    wire:click="viewCart"
                    class="bg-primary-500 hover:bg-primary-600 text-white px-6"
                    label="View Cart"
                />
            </div>
        </div>
    @endif
</div>
