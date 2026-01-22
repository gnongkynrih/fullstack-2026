<?php

namespace App\Livewire;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TableSession;
use Livewire\Component;
use Mary\Traits\Toast;

class SelectItem extends Component
{
    use Toast;

    public $menuCategories = [];
    public $selectedCategoryId = null;
    public $menuItems = [];
    public $cartItems = [];
    public $order;

    public function mount()
    {
        // Check if the table_session_id exist
        if (!session('table_session_id')) {
            return redirect()->route('select-table');
        }

        $this->menuCategories = MenuCategory::where('is_active', true)
            ->orderBy('name')->get();

        // Select first category by default
        if ($this->menuCategories->isNotEmpty()) {
            $this->selectedCategoryId = 'all';// $this->menuCategories->first()->id;
            $this->loadMenuItems();
        }

        // Get or create order for the table session
        $tableSessionId = session('table_session_id');
        $this->order = Order::where('table_session_id', $tableSessionId)
            ->where('status', 'open')
            ->first();

        if (!$this->order) {
            $this->order = Order::create([
                'table_session_id' => $tableSessionId,
                'user_id' => auth()->id(),
                'status' => 'open',
            ]);
        }

        $this->loadCartItems();
    }

    public function selectCategory($categoryId)
    {
        $this->selectedCategoryId = $categoryId;
        $this->loadMenuItems();
    }

    public function loadMenuItems()
    {
        $query = MenuItem::query()->where('is_active', true);
        if($this->selectedCategoryId !== 'all') {
            $query->where('menu_category_id', $this->selectedCategoryId);
        }
        $this->menuItems = $query->orderBy('name')->get();
        
    }

    public function addToCart($menuItemId)
    {
        $menuItem = MenuItem::findOrFail($menuItemId);

        // Check if item already in cart
        // $existingItem = $this->order->orderItems()
        //     ->where('menu_item_id', $menuItemId)
        //     ->where('status', 'pending')
        //     ->first();

        $existingItem = OrderItem::where('menu_item_id', $menuItemId)
            ->where('order_id', $this->order->id)
            ->where('status', 'pending')
            ->first();
        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + 1,
                'line_total' => ($existingItem->quantity + 1) * $existingItem->unit_price,
            ]);
        } else {
            OrderItem::create([
                'order_id' => $this->order->id,
                'menu_item_id' => $menuItemId,
                'quantity' => 1,
                'unit_price' => $menuItem->price,
                'line_total' => $menuItem->price,
                'status' => 'pending',
            ]);
        }

        $this->loadCartItems();
        $this->updateOrderTotal();

        $this->toast(
            type: 'success',
            title: 'Added to Cart',
            description: "{$menuItem->name} added to cart",
        );
    }

    public function loadCartItems()
    {
        $this->cartItems = $this->order->orderItems()
            ->with('menuItem')
            ->where('status', 'pending')
            ->get();
    }

    public function updateOrderTotal(){
        $this->order->update([
            'total_amount' => $this->order->orderItems()
                ->where('status', 'pending')
                ->sum('line_total'),
        ]);

        //use alternate simplier method
        //select sum(line_total) as total from order_items 
        // where order_id = $this->order->id and status = 'pending'
        // $sum = OrderItem::where('order_id',$this->order->id)
        //     ->where('status', 'pending')
        //     ->sum('line_total');
        // $this->order->update([
        //     'total_amount' => $sum,
        // ]);
    }
    public function viewCart()
    {
        return redirect()->route('view-cart');
    }

    public function render()
    {
        return view('livewire.select-item');
    }
}
