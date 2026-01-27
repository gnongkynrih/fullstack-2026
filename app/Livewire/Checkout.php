<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\TableSession;
use App\Models\RestaurantTable;
use App\Models\OrderPayment;
use Livewire\Component;
use Mary\Traits\Toast;
use Razorpay\Api\Api;

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
    public $razorpayOrderId = null;
    public $razorpayKey = null;

    public function mount()
    {
        $this->loadOccupiedTables();
        $this->razorpayKey = config('services.razorpay.key');
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

    public function createRazorpayOrder()
    {
        try {
            //get the api keys
            // $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api = new Api(env('RAZOR_PAY_KEY'), env('RAZOR_PAY_SECRET_KEY'));
            
            // Amount in paise (multiply by 100)
            $amountInPaise = (int)($this->totalAmount * 100);
            
            //create an order
            $razorpayOrder = $api->order->create([
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => 'order_' . $this->order->id,
                //notes is optional
                // 'notes' => [
                //     'order_id' => $this->order->id,
                //     'table_name' => $this->selectedTable->name,
                // ]
            ]);
            //store the razor pay order id
            $this->razorpayOrderId = $razorpayOrder['id'];
            
            // Create payment record with pending status
            OrderPayment::create([
                'order_id' => $this->order->id,
                'amount' => $this->totalAmount,
                'method' => $this->paymentMethod,
                'payment_status' => 'pending',
                'razorpay_orderid' => $this->razorpayOrderId,
            ]);
            

            \Log::info('Payment record created with order ID :' . $this->razorpayOrderId);
            
            // Dispatch browser event to open Razorpay checkout
            $this->dispatch('openRazorpay', [
                'orderId' => $this->razorpayOrderId,
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'name' => 'Restaurant POS',
                'description' => 'Payment for Table ' . $this->selectedTable->name,
            ]);
        } catch (\Exception $e) {
            \Log::error('Razorpay order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->toast(
                title: 'Error',
                description: 'Failed to create payment order: ' . $e->getMessage(),
                type: 'error'
            );
        }
    }

    public function verifyPayment($paymentId, $orderId, $signature)
    {
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            
            // Verify signature
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];
            
            $api->utility->verifyPaymentSignature($attributes);
            
            // Payment verified, process the order
            $this->processPayment($paymentId, $orderId, $signature);
            
        } catch (\Exception $e) {
            $this->toast(
                title: 'Payment Verification Failed',
                description: 'Payment could not be verified: ' . $e->getMessage(),
                type: 'error'
            );
        }
    }

    public function processPayment($paymentId, $orderId, $signature)
    {
        try {
            \DB::beginTransaction();

            // Update order
            $this->order->update([
                'status' => 'completed',
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax,
                'discount_amount' => $this->discount,
                'total_amount' => $this->totalAmount,
                'completed_at' => now(),
            ]);

            // Update existing payment record to success
            OrderPayment::where('razorpay_orderid', $orderId)
                ->update([
                    'payment_status' => 'success',
                    'razorpay_paymentid' => $paymentId,
                ]);

            // Close table session
            $this->tableSession->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            // Make table available
            RestaurantTable::find($this->tableSession->restaurant_table_id)
                ->update(['status' => 'available']);

            \DB::commit();

            session()->forget(['table_session_id', 'table_name']);

            $this->toast(
                title: 'Payment Successful',
                description: 'Thank you! Table has been closed.',
                type: 'success'
            );

            return redirect()->route('staff-home');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->toast(
                title: 'Payment Failed',
                description: 'An error occurred while processing payment: ' . $e->getMessage(),
                type: 'error'
            );
        }
    }

    public function processCashPayment()
    {
        try {
            \DB::beginTransaction();

            // Update order
            $this->order->update([
                'status' => 'completed',
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax,
                'discount_amount' => $this->discount,
                'total_amount' => $this->totalAmount,
                'completed_at' => now(),
            ]);

            // Create payment record
            OrderPayment::create([
                'order_id' => $this->order->id,
                'amount' => $this->totalAmount,
                'method' => 'cash',
                'payment_status' => 'success',
            ]);

            // Close table session
            $this->tableSession->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            // Make table available
            RestaurantTable::find($this->tableSession->restaurant_table_id)
                ->update(['status' => 'available']);

            \DB::commit();

            session()->forget(['table_session_id', 'table_name']);

            $this->toast(
                title: 'Payment Successful',
                description: 'Cash payment recorded. Table has been closed.',
                type: 'success'
            );

            return redirect()->route('staff-home');
        } catch (\Exception $e) {
            \DB::rollBack();
            $this->toast(
                title: 'Payment Failed',
                description: 'An error occurred while processing payment: ' . $e->getMessage(),
                type: 'error'
            );
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
