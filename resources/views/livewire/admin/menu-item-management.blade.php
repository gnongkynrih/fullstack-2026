<div class="max-w-7xl mx-auto p-6 bg-neutral-50">
    <p class="text-2xl font-bold mb-6 text-primary-600">Menu Item Management</p>
    <div class="flex justify-between">
        <x-input 
        wire:model.live.debounce.500ms="searchItem" 
        placeholder="Search menu items"
        icon-right="o-magnifying-glass" 
        class="w-full" />
    <x-button label="Add New Item" wire:click="openModal" icon="o-plus"  class="mb-6 btn-primary"/>

    </div>
    <x-card title="Existing Menu Items" subtitle="List of existing menu items" shadow separator>
            <x-table :headers="$headers" :rows="$existingItems->items()">
                @scope('cell_id', $item)
                    {{ $loop->iteration }} <!-- this is to display the row number -->
                @endscope
                @scope('cell_menu_category_id', $item)
                    {{ $item->menuCategory->name ?? '-' }}
                @endscope
                @scope('cell_is_active', $item)
                    {{ $item->is_active ? 'Yes' : 'No' }}
                @endscope
                @scope('actions', $item)
                <div class="flex gap-4">
                    <x-button icon="o-pencil" wire:click="edit({{ $item->id }})" spinner class="bg-secondary-200 text-primary-600" />
                    <x-button icon="o-trash" wire:click="delete({{$item->id}})" spinner class="bg-primary-400 text-white" />
                </div>
                @endscope
            </x-table>
            <div class="mt-4">
                {{ $existingItems->links() }}
            </div>
        </x-card>


    {{-- Modal --}}
    <x-modal wire:model="showModal" title="Hello" subtitle="Livewire example">
        <x-form no-separator>
            <x-select 
                label="Category" 
                wire:model="category_id" 
                :options="$categories" 
                option-label="name" 
                option-value="id" 
                placeholder="Select Category"
            />
            <x-input 
                label="Name" 
                wire:model="name" 
                placeholder="menu Item" />
            <x-textarea 
                label="Description" 
                wire:model="description" 
                placeholder="Description" />
            <x-input 
                label="Price" 
                wire:model="price" 
                placeholder="Price" />
            <x-toggle 
                label="Is Active" 
                wire:model="is_active" />
    
            <x-slot:actions>
                <x-button label="Cancel" wire:click="closeModal" />
                <x-button label="{{$isEdit === false ? 'Save' : 'Update'}}" wire:click="saveItem" spinner="save3" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-modal wire:model="showDelete" title="Delete" class="backdrop-blur">
        Are you sure you want to delete
    
        <x-slot:actions>
            <x-button label="Cancel" wire:click="resetForm" />
            <x-button label="Yes" wire:click="destroy({{ $item_id }})" spinner class="bg-primary-400 text-white" />
        </x-slot:actions>
    </x-modal>
    
</div>
