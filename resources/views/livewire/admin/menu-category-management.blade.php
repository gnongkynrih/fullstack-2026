<div class="max-w-7xl mx-auto p-6 bg-neutral-50">
    <p class="text-2xl font-bold mb-6 text-primary-600">Menu Category Management</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2  gap-6">
        <x-card title="Menu Category" subtitle="Add New Category" shadow separator>
            <form wire:submit.prevent={{ $isEdit ? 'updateCategory' : 'saveCategory' }} class="space-y-6">
                <x-input 
                    label="Category Name" 
                    wire:model="name" 
                    placeholder="Category Name" 
                />
 
                <x-toggle 
                    label="Is Active" 
                    wire:model="is_active" />
                <div class="flex gap-4">
                <x-button 
                    label="Cancel" 
                    class="bg-secondary-100" 
                    wire:click="resetForm" 
                    icon="o-x-mark"
                />
                    {{-- @if($isEdit)   
                        <x-button 
                            label="Update" 
                            class="bg-secondary-500 hover:bg-secondary-600 text-white" 
                            type="submit" 
                            spinner="save3" 
                            icon="o-pencil"
                        />
                    @else
                        <x-button 
                            label="Save" 
                            class="bg-primary-500 hover:bg-primary-600 text-white" 
                            type="submit" 
                            spinner="save3" 
                            icon="o-plus"
                        />
                    @endif --}}
                    <x-button 
                            label='{{ $isEdit === false ? "Save" : "Update" }}' 
                            class="bg-secondary-500 hover:bg-secondary-600 text-white" 
                            type="submit" 
                            spinner="save3" 
                            icon="{{ $isEdit === false ? 'o-plus' : 'o-pencil' }}"
                        />
                </div>
            </form>
        </x-card>
        

        <x-card title="Existing Categories" subtitle="List of existing categories" shadow separator>
            <x-table :headers="$headers" :rows="$categories->items()">
                @scope('cell_is_active', $cat)
                    {{ $cat->is_active ? 'Yes' : 'No' }}
                @endscope
                @scope('actions', $cat)
                <div class="flex gap-4">
                    <x-button icon="o-pencil" wire:click="edit({{ $cat->id }})" spinner class="bg-secondary-200 text-primary-600" />
                    <x-button icon="o-trash" wire:click="delete({{$cat->id}})" spinner class="bg-primary-400 text-white" />
                </div>
                @endscope
            </x-table>
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </x-card>
    </div>

    <x-modal wire:model="confirmDelete" title="Delete?" class="backdrop-blur">
        Are you sure you want to delete ?
    
        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.confirmDelete = false" />
            <x-button label="Yes" wire:click="destroy({{ $category_id }})" spinner class="bg-primary-400 text-white" />
        </x-slot:actions>
    </x-modal>
</div>
