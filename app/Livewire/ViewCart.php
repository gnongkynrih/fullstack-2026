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
            // ->with('orderItems.menuItem')
            ->first();

        
        if (!$this->order || $this->order->orderItems->isEmpty()) {
            return redirect()->route('select-item');
        }

        $this->loadCartItems();
    }

    public function loadCartItems()
    {
        //select o.*,i*,m.* from orders o inner join order_items i
        //on o.id = i.order_id 
        // inner join menu_items m on i.menu_item_id = m.id

        $this->cartItems = $this->order->orderItems()
            ->with('menuItem') //lazy loading
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
        $subtotal = $this->order->orderItems()
            ->where('status', 'pending')->sum('line_total');
        $this->order->update([
            'subtotal' => $subtotal,
            'total_amount' => $subtotal, // Add tax/discount logic later
        ]);
    }

    public function placeOrder(){
        $this->order->orderItems()->where('status','pending')->update([
            'status' => 'preparing',
        ]);

        // Debug authentication and send notification
        $user = auth()->user();
        \Log::info('PlaceOrder Debug', [
            'user_authenticated' => $user ? true : false,
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : null,
            'order_id' => $this->order->id,
        ]);

        if ($user) {
            try {
                $user->notify(new \App\Notifications\OrderPlacedNotification($this->order));
                \Log::info('Notification sent successfully', ['user_id' => $user->id, 'order_id' => $this->order->id]);
            } catch (\Exception $e) {
                \Log::error('Notification failed', [
                    'user_id' => $user->id,
                    'order_id' => $this->order->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            \Log::warning('No authenticated user for order placement', ['order_id' => $this->order->id]);
        }

        $this->toast(
            title:'Order placed',
            description:'Order placed successfully',
            type:'success'
        );
        return redirect()->route('select-table');
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
