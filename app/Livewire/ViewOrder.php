<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\RestaurantTable;
use App\Models\Order;

class ViewOrder extends Component
{
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
            $order = Order::where('table_session_id', $this->selectedTable->id)
                ->where('status', 'open')
                ->with('orderItems.menuItem')
                ->first();
            $this->orderItems = $order ? $order->orderItems : [];
        } else {
            $this->orderItems = [];
        }
    }

    public function render()
    {
        return view('livewire.view-order');
    }
}
