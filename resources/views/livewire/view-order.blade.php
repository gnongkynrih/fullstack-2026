<div class="min-h-screen bg-gray-50 p-4 md:p-6" wire:poll.30s>
    {{-- Be like water. --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Current Orders</h1>

    <!-- Tables Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
        @forelse($tables as $table)
            <button 
                wire:click="selectTable({{ $table->id }})"
                class="p-4 bg-white rounded-lg shadow-sm border border-gray-200 hover:border-primary-500 hover:shadow-md transition duration-200 {{ $selectedTable && $selectedTable->id == $table->id ? 'border-primary-500 bg-primary-50' : '' }}"
            >
                <span class="text-lg font-semibold text-gray-700">Table {{ $table->name }}</span>
            </button>
        @empty
            <p class="col-span-full text-center text-gray-500">No occupied tables at the moment.</p>
        @endforelse
    </div>

    <!-- Order Details -->
    @if($selectedTable)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order for Table {{ $selectedTable->name }}</h2>
            
            @forelse($orderItems as $item)
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-medium text-gray-900 truncate">{{ $item->menuItem->name }}</h3>
                        <p class="text-sm font-medium text-primary-600 mt-1">₹{{ number_format($item->unit_price, 2) }}</p>
                        <span class="inline-block px-2 py-1 mt-2 text-xs font-medium rounded-full {{ $item->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($item->status == 'preparing' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="font-medium">Qty: {{ $item->quantity }}</span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No items in this order.</p>
            @endforelse
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-gray-500">Select a table to view order details.</p>
        </div>
    @endif
</div>
