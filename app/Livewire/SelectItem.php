<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MenuCategory;

class SelectItem extends Component
{
    public $menuCategories = [];
    public $selectedCategoryId = null;
    public function mount(){
        //check if the table_session_id exist
        if(!session('table_session_id')) {
            return redirect()->route('select-table');
        }
        $this->menuCategories = MenuCategory::where('is_active',true)
        ->orderBy('name')->get();
    }
    public function render()
    {
        
        return view('livewire.select-item');
    }
}
