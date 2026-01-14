<div class="max-w-7xl mx-auto p-6 bg-neutral-50">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary-600">Restaurant Tables</h1>
            <p class="mt-1 text-sm text-gray-600">Manage dining tables and their availability status</p>
        </div>
        <x-button 
            label="Add New Table" 
            wire:click="openCreateModal" 
            icon="o-plus" 
            class="bg-primary-500 hover:bg-primary-600 text-white"
        />
    </div>

    <!-- Table Card -->
    <x-card shadow separator>
        <x-table :headers="[
            ['key' => 'name', 'label' => 'Table Name', 'class' => 'text-primary-700 font-semibold'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'actions', 'label' => 'Actions', 'class' => 'text-right']
        ]" :rows="$tables->items()">
            
            @scope('cell_name', $table)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="font-medium text-gray-900">{{ $table->name }}</span>
                </div>
            @endscope

            @scope('cell_status', $table)
                @if($table->status === 'available')
                    <x-badge value="Available" class="badge-success" />
                @elseif($table->status === 'occupied')
                    <x-badge value="Occupied" class="badge-error" />
                @else
                    <x-badge value="{{ ucfirst($table->status) }}" class="badge-warning" />
                @endif
            @endscope

            @scope('cell_actions', $table)
                <div class="flex gap-2 justify-end">
                    <x-button 
                        icon="o-pencil" 
                        wire:click="edit({{ $table->id }})" 
                        spinner 
                        class="btn-sm bg-secondary-200 text-primary-600 hover:bg-secondary-300"
                        tooltip="Edit table"
                    />
                    <x-button 
                        icon="o-trash" 
                        wire:click="confirmDelete({{ $table->id }})" 
                        spinner 
                        class="btn-sm bg-red-100 text-red-600 hover:bg-red-200"
                        tooltip="Delete table"
                    />
                </div>
            @endscope
        </x-table>

        <div class="mt-4">
            {{ $tables->links() }}
        </div>
    </x-card>

    <!-- Modal -->
    <x-modal wire:model="showModal" title="{{ $isEditing ? 'Edit Table' : 'Create New Table' }}" subtitle="Manage table information" class="backdrop-blur">
        <x-form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
            
            <x-input 
                label="Table Name / Number" 
                wire:model="name" 
                placeholder="e.g., Table 5, VIP-02, Bar-1"
                icon="o-hashtag"
                hint="Enter a unique name for this table"
            />

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-3">Table Status</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all {{ $status === 'available' ? 'border-secondary-500 bg-secondary-50 shadow-sm' : 'border-gray-200 hover:border-secondary-300' }}">
                        <input type="radio" wire:model.live="status" value="available" class="sr-only">
                        
                        <x-icon name="o-check-circle" class="w-6 h-6 mb-2 {{ $status === 'available' ? 'text-secondary-600' : 'text-gray-400' }}" />
                        <span class="text-sm font-semibold {{ $status === 'available' ? 'text-secondary-700' : 'text-gray-600' }}">Available</span>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all {{ $status === 'occupied' ? 'border-red-500 bg-red-50 shadow-sm' : 'border-gray-200 hover:border-red-300' }}">
                        <input type="radio" wire:model.live="status" value="occupied" class="sr-only">
                        <x-icon name="o-users" class="w-6 h-6 mb-2 {{ $status === 'occupied' ? 'text-red-600' : 'text-gray-400' }}" />
                        <span class="text-sm font-semibold {{ $status === 'occupied' ? 'text-red-700' : 'text-gray-600' }}">Occupied</span>
                    </label>

                    <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all {{ $status === 'reserved' ? 'border-primary-500 bg-primary-50 shadow-sm' : 'border-gray-200 hover:border-primary-300' }}">
                        <input type="radio" wire:model.live="status" value="reserved" class="sr-only">
                        <x-icon name="o-bookmark" class="w-6 h-6 mb-2 {{ $status === 'reserved' ? 'text-primary-600' : 'text-gray-400' }}" />
                        <span class="text-sm font-semibold {{ $status === 'reserved' ? 'text-primary-700' : 'text-gray-600' }}">Reserved</span>
                    </label>
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancel" wire:click="closeModal" />
                <x-button 
                    label="{{ $isEditing ? 'Update Table' : 'Create Table' }}" 
                    type="submit" 
                    spinner 
                    class="bg-primary-500 hover:bg-primary-600 text-white"
                    icon="{{ $isEditing ? 'o-pencil' : 'o-plus' }}"
                />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal wire:model="confirmDeleteModal" title="Delete Table?" class="backdrop-blur">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-gray-700 font-medium mb-2">Are you sure you want to delete this table?</p>
                <p class="text-sm text-gray-500">This action cannot be undone. The table will be permanently removed from the system.</p>
            </div>
        </div>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.confirmDeleteModal = false" />
            <x-button 
                label="Delete Table" 
                wire:click="delete" 
                spinner 
                class="bg-red-500 hover:bg-red-600 text-white"
                icon="o-trash"
            />
        </x-slot:actions>
    </x-modal>

</div>