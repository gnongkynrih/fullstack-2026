<?php

namespace App\Livewire\Admin;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class MenuCategoryManagement extends Component
{
    use Toast;
    public $headers = [];
    public $isEdit = false;
    public $name;
    public $is_active;
    public $category_id;
    public $confirmDelete = false;

    public function rules(){
        $rules = [
            'name' => 'required|min:4|max:20|unique:menu_categories,name',
            'is_active' => 'required|boolean',
        ];
        //tells laravel to ignore unique check when editing
        if ($this->isEdit && $this->category_id) {
            $rules['name'] .= ',' . $this->category_id;
        }
        return $rules;
    }

    public function messages(){
        return [
            'name.unique' => 'Category name already exists',
            'name.required' => 'Category name is required',
            'name.min' => 'Category name must be at least 4 characters long',
            'name.max' => 'Category name must be at most 20 characters long',
            'is_active.required' => 'Is active is required',
            'is_active.boolean' => 'Is active must be a boolean',
        ];
    }
    //this is executed immediately when the component is created
    public function mount()
    {
        $this->name = '';
        $this->is_active =true;
        $this->headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'bg-purple-500 w-1 text-white'],
        ['key' => 'name', 'label' => 'Menu Category'],
        ['key' => 'is_active', 'label' => 'Is Active', 'class' => 'hidden lg:table-cell'], // Responsive
    ];
    }

    public function resetForm(){
        $this->name = '';
        $this->is_active = true;
        $this->category_id = null;
        $this->isEdit = false;
    }
    public function saveCategory()
    {
        $this->validate(); //validate the form
        try{
            $category = MenuCategory::create([
            'name' => $this->name,
            'is_active' => $this->is_active,
        ]);
        $this->toast(
            type: 'success',
            title: 'Category created successfully',
        );
        }catch(\Exception $e){
            $this->toast(
                type: 'error',
                title: 'Category creation failed',
            );
        }
        $this->resetForm();
    }

    public function updateCategory(){
        $this->validate();
        $category = MenuCategory::find($this->category_id);
        $category->name = $this->name;
        $category->is_active = $this->is_active;
        $category->save();

        $category->menuItems()->update([
            'is_active' => $this->is_active,
        ]);
        $this->toast(
            type: 'success',
            title: 'Category updated successfully',
        );
        $this->isEdit = false;
        $this->resetForm();
    }
    public function edit($id){
        $this->category_id = $id;
        //select * from menu_categories where id = $id
        $category = MenuCategory::find($id); //use to search by primary key
        $this->name = $category->name;
        $this->is_active = (bool) $category->is_active;
        $this->isEdit = true;
    }
   public function delete($id){
    $this->category_id = $id; //store the id to delete
    $this->confirmDelete = true; //show the modal form
   }
    public function destroy($id){
        if(MenuItem::where('menu_category_id',$id)->exists()){
            $this->toast(
                type: 'error',
                title: 'Category cannot be deleted',
            );
            $this->confirmDelete = false; //hide the modal form
            return;
        }
        MenuCategory::find($id)->delete();
        // $t = MenuCategory::find($id);
        // $t->delete();
        $this->toast(
            type: 'success',
            title: 'Category deleted successfully',
            css: 'bg-primary-500 text-white'
        );
        $this->confirmDelete = false; //hide the modal form
    }
    public function render()
    {
        return view('livewire.admin.menu-category-management', [
            'categories' => MenuCategory::paginate(5)
        ]);
    }
}
