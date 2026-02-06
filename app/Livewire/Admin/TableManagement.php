<?php

namespace App\Livewire\Admin;

use App\Models\RestaurantTable;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class TableManagement extends Component
{
    use WithPagination;
    use Toast;

    public $showModal = true;
    public $confirmDeleteModal = false;
    public $name;
    public $status = 'available';
    public $isEditing = false;
    public $editingId;
    public $deleteId;

    protected $rules = [
        'name' => 'required|min:2|max:50',
        'status' => 'required|in:available,occupied,reserved',
    ];

    protected $messages = [
        'name.required' => 'Table name is required',
        'name.min' => 'Table name must be at least 2 characters',
        'name.max' => 'Table name cannot exceed 50 characters',
        'status.required' => 'Status is required',
        'status.in' => 'Invalid status selected',
    ];

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->status = 'available';
        $this->editingId = null;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        try {
            RestaurantTable::create([
                'name' => $this->name,
                'status' => $this->status,
            ]);

            $this->toast(
                type: 'success',
                title: 'Table created successfully',
            );

            $this->closeModal();
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Failed to create table',
                description: $e->getMessage(),
            );
        }
    }

    public function edit($id)
    {
        $table = RestaurantTable::findOrFail($id);
        
        $this->editingId = $id;
        $this->name = $table->name;
        $this->status = $table->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        try {
            $table = RestaurantTable::findOrFail($this->editingId);
            $table->update([
                'name' => $this->name,
                'status' => $this->status,
            ]);

            $this->toast(
                type: 'success',
                title: 'Table updated successfully',
            );

            $this->closeModal();
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Failed to update table',
                description: $e->getMessage(),
            );
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmDeleteModal = true;
    }

    public function delete()
    {
        try {
            RestaurantTable::findOrFail($this->deleteId)->delete();

            $this->toast(
                type: 'success',
                title: 'Table deleted successfully',
            );

            $this->confirmDeleteModal = false;
            $this->deleteId = null;
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Failed to delete table',
                description: $e->getMessage(),
            );
        }
    }

    public function render()
    {
        return view('livewire.admin.table-management', [
            'tables' => RestaurantTable::orderBy('name')->paginate(10)
        ]);
    }
}
