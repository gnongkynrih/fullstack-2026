<?php

namespace App\Livewire;

use App\Models\Order;
use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\RestaurantTable;

class ViewOrder extends Component
{
    use Toast;
    public $tables = [];
    public $selectedTable = null;
    public $orderItems = [];

    protected $listeners = ['refreshOrders' => 'loadOccupiedTables'];
    protected $pollingIntervalInSeconds = 10;

    public function mount()
    {
        $this->loadOccupiedTables();
    }

    public function loadOccupiedTables()
    {
        $this->tables = RestaurantTable::where('status', 'occupied')->get();
    }

    public function selectTable($tableId)
    {
        $this->selectedTable = RestaurantTable::find($tableId);
        $this->loadOrderItems();
    }

    public function loadOrderItems()
    {
        if ($this->selectedTable) {
            $tableSession = \App\Models\TableSession::where('restaurant_table_id', $this->selectedTable->id)
                ->where('status', 'open')
                ->first();
            
            if ($tableSession) {
                $order = Order::where('table_session_id', $tableSession->id)
                    ->where('status', 'open')
                    ->with('orderItems.menuItem')
                    ->first();
                $this->orderItems = $order ? $order->orderItems : [];
            } else {
                $this->orderItems = [];
            }
        } else {
            $this->orderItems = [];
        }
    }

    public function updateItemStatus($itemId, $status)
    {
        try {
            $orderItem = \App\Models\OrderItem::find($itemId);
            
            if (!$orderItem) {
                $this->toast(
                    title: 'Error',
                    description: 'Order item not found',
                    type: 'error'
                );
                return;
            }

            $orderItem->update(['status' => $status]);
            
            $this->loadOrderItems();
            
            $statusText = ucfirst($status);
            $this->toast(
                title: 'Status Updated',
                description: "Item marked as {$statusText}",
                type: 'success'
            );
        } catch (\Exception $e) {
            $this->toast(
                title: 'Error',
                description: 'Failed to update status',
                type: 'error'
            );
        }
    }

    public function returnItem($itemId)
    {
        try {
            $orderItem = \App\Models\OrderItem::find($itemId);
            
            if (!$orderItem) {
                $this->toast(
                    title: 'Error',
                    description: 'Order item not found',
                    type: 'error'
                );
                return;
            }

            $orderItem->update(['status' => 'returned']);
            
            $this->loadOrderItems();
            
            $this->toast(
                title: 'Item Returned',
                description: "{$orderItem->menuItem->name} marked as returned",
                type: 'warning'
            );
        } catch (\Exception $e) {
            $this->toast(
                title: 'Error',
                description: 'Failed to process return',
                type: 'error'
            );
        }
    }

    public function replaceItem($itemId)
    {
        try {
            \DB::beginTransaction();
            
            $orderItem = \App\Models\OrderItem::find($itemId);
            
            if (!$orderItem) {
                $this->toast(
                    title: 'Error',
                    description: 'Order item not found',
                    type: 'error'
                );
                return;
            }

            $orderItem->update(['status' => 'returned']);
            
            $newOrderItem = \App\Models\OrderItem::create([
                'order_id' => $orderItem->order_id,
                'menu_item_id' => $orderItem->menu_item_id,
                'quantity' => $orderItem->quantity,
                'unit_price' => $orderItem->unit_price,
                'line_total' => $orderItem->line_total,
                'status' => 'preparing',
            ]);
            
            \DB::commit();
            
            $this->loadOrderItems();
            
            $this->toast(
                title: 'Item Replaced',
                description: "New {$orderItem->menuItem->name} is being prepared",
                type: 'success'
            );
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->toast(
                title: 'Error',
                description: 'Failed to process replacement',
                type: 'error'
            );
        }
    }

    public function render()
    {
        return view('livewire.view-order');
    }
}
