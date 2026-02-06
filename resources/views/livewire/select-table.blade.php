<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-white border-b border-gray-200 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto animate__animated animate__fadeInDown">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-1">Select Your Table</h1>
                    <p class="text-gray-600">Choose a table to start taking orders</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button
                        wire:click="openReservationModal"
                        class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-5 rounded-lg transition-all flex items-center space-x-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Reserve Table</span>
                    </button>
                    <div class="hidden md:block">
                        <div class="bg-primary-50 rounded-lg px-4 py-2 text-center border border-primary-100">
                            <p class="text-xs text-primary-700 mb-0.5">Total Tables</p>
                            <p class="text-2xl font-bold text-primary-900">{{ $tables->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Status Legend -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6 animate__animated animate__backInLeft animate__delay-1s">
            <div class="flex flex-wrap gap-6 justify-center items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-primary-600"></div>
                    <span class="text-sm text-gray-700">Available <span class="font-semibold">({{ $tables->where('status', 'available')->count() }})</span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                    <span class="text-sm text-gray-700">Occupied <span class="font-semibold">({{ $tables->where('status', 'occupied')->count() }})</span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-gray-600"></div>
                    <span class="text-sm text-gray-700">Reserved <span class="font-semibold">({{ $tables->where('status', 'reserved')->count() }})</span></span>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($tables as $index => $table)
                <div class="animate__animated animate__fadeInUp" style="animation-delay: {{ 0.1 + ($index * 0.05) }}s">
                    <div class="group relative bg-white rounded-lg border-2 transition-all duration-200 overflow-hidden
                        {{ $table->status === 'available' ? 'border-primary-600 hover:border-primary-700 hover:shadow-lg cursor-pointer' : ($table->status === 'occupied' ? 'border-gray-300 hover:shadow-lg cursor-pointer' : 'border-gray-400') }}"
                        @if($table->status === 'available')
                            wire:click="selectTable({{ $table->id }})"
                        @endif
                    >
                        <!-- Status Indicator -->
                        <div class="absolute top-3 right-3 z-10">
                            <div class="w-2.5 h-2.5 rounded-full {{ $table->status === 'available' ? 'bg-primary-600' : ($table->status === 'occupied' ? 'bg-gray-400' : 'bg-gray-600') }}"></div>
                        </div>

                        <!-- Table Icon & Name -->
                        <div class="p-5 text-center">
                            <div class="flex items-center justify-center w-14 h-14 mx-auto mb-3 rounded-full transition-all
                                {{ $table->status === 'available' ? 'bg-primary-50 group-hover:bg-primary-100' : ($table->status === 'occupied' ? 'bg-gray-50' : 'bg-gray-100') }}">
                                <svg class="w-7 h-7 {{ $table->status === 'available' ? 'text-primary-600' : ($table->status === 'occupied' ? 'text-gray-400' : 'text-gray-600') }}" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 mb-1">{{ $table->name }}</h3>
                            <span class="inline-block px-2.5 py-0.5 rounded text-xs font-medium
                                {{ $table->status === 'available' ? 'bg-primary-50 text-primary-700' : ($table->status === 'occupied' ? 'bg-gray-100 text-gray-600' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($table->status) }}
                            </span>
                        </div>

                        <!-- Action Button -->
                        <div class="px-4 pb-4">
                            @if($table->status === 'available' || $table->status === 'occupied')
                                <button
                                    wire:click.stop="selectTable({{ $table->id }})"
                                    class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-all flex items-center justify-center space-x-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    <span>Open</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Guest Information Modal -->
    <x-modal wire:model="showGuestForm" title="Guest Information" subtitle="Please provide guest details to proceed">
        <div class="space-y-4">
            <div class="bg-primary-50 border border-primary-200 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-primary-100 p-2 rounded-full">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm text-primary-800">Enter the number of guests to open this table</p>
                </div>
            </div>

            <x-input 
                type="number" 
                wire:model="guestCount" 
                label="Guest Count" 
                icon="o-user-group" 
                placeholder="Number of guests"
                min="1"
            />
            <x-input 
                type="email" 
                wire:model="email" 
                label="Email (Optional)" 
                icon="o-envelope" 
                placeholder="Guest email address" 
            />
        </div>

        <x-slot:actions>
            <x-button label="Cancel" wire:click="clearTable()" />
            <x-button label="Open Table" icon="o-check-circle" class="btn-primary" wire:click="OpenTable()" />
        </x-slot:actions>
    </x-modal>

    <!-- Reservation Modal -->
    <x-modal wire:model="showReservationModal" title="Reserve or Unreserve Table" subtitle="Manage table reservations">
        <div class="space-y-4">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-100 p-2 rounded-full">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm text-yellow-800">Select a table to reserve or unreserve it</p>
                </div>
            </div>

            <x-select 
                label="Select Table" 
                icon="o-table-cells"
                wire:model="reservationTableId"
                placeholder="Choose a table"
                :options="$tables->where('status', '!=', 'occupied')->map(fn($t) => ['id' => $t->id, 'name' => $t->name . ' - ' . ucfirst($t->status)])"
                option-value="id"
                option-label="name"
            />

            @if($reservationTableId)
                @php
                    $selectedTable = $tables->firstWhere('id', $reservationTableId);
                @endphp
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Current Status</p>
                            <p class="text-lg font-bold text-gray-900">{{ ucfirst($selectedTable->status) }}</p>
                        </div>
                        <div class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $selectedTable->status === 'reserved' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            Will become: {{ $selectedTable->status === 'reserved' ? 'Available' : 'Reserved' }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.showReservationModal = false" />
            <x-button 
                label="{{ $reservationTableId && $tables->firstWhere('id', $reservationTableId)?->status === 'reserved' ? 'Unreserve Table' : 'Reserve Table' }}" 
                icon="o-check-circle"
                class="btn-primary" 
                wire:click="toggleReservation" 
            />
        </x-slot:actions>
    </x-modal>
</div>
