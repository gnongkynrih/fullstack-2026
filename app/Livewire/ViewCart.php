<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\TableSession;
use Livewire\Component;
use Mary\Traits\Toast;

class ViewCart extends Component
{
    use Toast;

    public $order;
    public $cartItems = [];

    public function mount()
    {
        // Check if the table_session_id exist
        if (!session('table_session_id')) {
            return redirect()->route('select-table');
        }

        $tableSessionId = session('table_session_id');
        $this->order = Order::where('table_session_id', $tableSessionId)
            ->where('status', 'open')
            ->with('orderItems.menuItem')
            ->first();

        
        if (!$this->order || $this->order->orderItems->isEmpty()) {
            return redirect()->route('select-item');
        }

        $this->loadCartItems();
    }

    public function loadCartItems()
    {
        $this->cartItems = $this->order->orderItems()
            ->with('menuItem')
            ->where('status', 'pending')
            ->get();
    }

    public function increaseQuantity($orderItemId)
    {
        $item = OrderItem::findOrFail($orderItemId);
        $item->update([
            'quantity' => $item->quantity + 1,
            'line_total' => ($item->quantity + 1) * $item->unit_price,
        ]);

        $this->updateOrderTotal();
        $this->loadCartItems();
    }

    public function decreaseQuantity($orderItemId)
    {
        $item = OrderItem::findOrFail($orderItemId);

        if ($item->quantity > 1) {
            $item->update([
                'quantity' => $item->quantity - 1,
                'line_total' => ($item->quantity - 1) * $item->unit_price,
            ]);
        } else {
            $item->delete();
        }

        $this->updateOrderTotal();
        $this->loadCartItems();
    }

    public function removeItem($orderItemId)
    {
        OrderItem::findOrFail($orderItemId)->delete();
        $this->updateOrderTotal();
        $this->loadCartItems();

        $this->toast(
            type: 'success',
            title: 'Item Removed',
            description: 'Item removed from cart',
        );

        if ($this->cartItems->isEmpty()) {
            return redirect()->route('select-item');
        }
    }

    public function updateOrderTotal()
    {
        $subtotal = $this->order->orderItems()->where('status', 'pending')->sum('line_total');
        $this->order->update([
            'subtotal' => $subtotal,
            'total_amount' => $subtotal, // Add tax/discount logic later
        ]);
    }

    public function back()
    {
        return redirect()->route('select-item');
    }

    public function render()
    {
        return view('livewire.view-cart');
    }
}
