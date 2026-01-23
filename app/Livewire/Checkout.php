<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\TableSession;
use App\Models\RestaurantTable;
use Livewire\Component;
use Mary\Traits\Toast;

class Checkout extends Component
{
    use Toast;

    public $tables = [];
    public $selectedTable = null;
    public $order;
    public $tableSession;
    public $orderItems = [];
    public $subtotal = 0;
    public $tax = 0;
    public $discount = 0;
    public $totalAmount = 0;
    public $paymentMethod = 'upi';
    public $showPaymentModal = false;

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
        $this->loadTableOrder();
    }

    public function loadTableOrder()
    {
        if ($this->selectedTable) {
            $this->tableSession = TableSession::where('restaurant_table_id', $this->selectedTable->id)
                ->where('status', 'open')
                ->first();
            
            if ($this->tableSession) {
                $this->order = Order::where('table_session_id', $this->tableSession->id)
                    ->where('status', 'open')
                    ->with('orderItems.menuItem')
                    ->first();
                
                if ($this->order) {
                    $this->loadOrderSummary();
                }
            }
        }
    }

    public function loadOrderSummary()
    {
        //select * from users where id in (1,2,3)
        //select * from useres where id = 1 or id =2 or id =3
        //  $items = $this->order->orderItems()
        //       ->where('status', 'preparing')
        //       ->orWhere('status', 'prepared')
        //       ->orWhere('status', 'served')
        //       ->with('menuItem')
        //       ->get();
        
        $items = $this->order->orderItems()
            ->whereIn('status', ['preparing', 'prepared', 'served'])
            ->with('menuItem')
            ->get();

        $this->orderItems = $items->groupBy('menu_item_id')->map(function ($group) {
            $first = $group->first();
            return (object) [
                'menuItem' => $first->menuItem,
                'quantity' => $group->sum('quantity'),
                'unit_price' => $first->unit_price,
                'line_total' => $group->sum('line_total'),
            ];
        })->values();

        $this->subtotal = $this->orderItems->sum('line_total');
        $this->tax = $this->subtotal * 0.05; // 5% tax
        $this->totalAmount = $this->subtotal + $this->tax - $this->discount;
    }

    public function processPayment()
    {
        try {
            \DB::beginTransaction();

            $this->order->update([
                'status' => 'completed',
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax,
                'discount_amount' => $this->discount,
                'total_amount' => $this->totalAmount,
                'payment_method' => $this->paymentMethod,
                'completed_at' => now(),
            ]);

            $this->tableSession->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            RestaurantTable::find($this->tableSession->restaurant_table_id)
                ->update(['status' => 'available']);

            \DB::commit();

            session()->forget(['table_session_id', 'table_name']);

            $this->toast(
                title: 'Payment Successful',
                description: 'Thank you! Table has been closed.',
                type: 'success'
            );

            return redirect()->route('select-table');
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->toast(
                title: 'Payment Failed',
                description: 'An error occurred while processing payment',
                type: 'error'
            );
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
