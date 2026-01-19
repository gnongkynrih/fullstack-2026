<div>
    <x-card class="p-6">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Select Your Table</h2>
            <p class="text-gray-600">Choose a table to start your order</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($tables as $table)
                <x-card
                    class="border p-4 hover:shadow-lg transition-shadow cursor-pointer min-h-40 {{ $table->status === 'available' ? 'border-green-200' : ($table->status === 'occupied' ? 'border-red-200' : 'border-yellow-200') }}"
                    wire:click="selectTable({{ $table->id }})"
                    {{-- @if($table->status !== 'available') disabled @endif --}}
                >
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $table->name }}</h3>
                        <span class="px-2 py-1 rounded-full text-xs font-medium text-white {{ $table->status === 'available' ? 'bg-green-500' : ($table->status === 'occupied' ? 'bg-red-500' : 'bg-yellow-500') }}">
                            {{ ucfirst($table->status) }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        @if($table->status === 'available')
                            <x-button
                                wire:click.stop="selectTable({{ $table->id }})"
                                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white"
                                label="Select Table"
                            />
                        @else
                            <x-button
                                class="flex-1 bg-gray-300 text-gray-500 cursor-not-allowed"
                                label="Unavailable"
                                disabled
                            />
                        @endif

                        <x-button
                            wire:click.stop="reserveTable({{ $table->id }})"
                            class="px-3 {{ $table->status === 'reserved' ? 'bg-green-500 hover:bg-green-600' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white"
                            label="{{ $table->status === 'reserved' ? 'Unreserve' : 'Reserve' }}"
                        />
                    </div>
                </x-card>
            @endforeach
        </div>
    </x-card>


    <x-modal wire:model="showGuestForm" title="Guest Information" subtitle="Please provide your details">
        <x-form no-separator>
            <x-input type="number" wire:model="guestCount" label="Guest Count" icon="o-user" placeholder="Number of guests" />
            <x-input type="email" wire:model="email" label="Email (optional)" icon="o-envelope" placeholder="Your email" />

            {{-- Notice we are using now the `actions` slot from `x-form`, not from modal --}}
            <x-slot:actions>
                <x-button label="Cancel" wire:click="clearTable()" />
                <x-button label="Confirm" class="btn-primary" wire:click="OpenTable()" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
