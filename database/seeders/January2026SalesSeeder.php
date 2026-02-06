<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPayment;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use App\Models\TableSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class January2026SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all menu items and tables
        $menuItems = MenuItem::all();
        $tables = RestaurantTable::where('status', '!=', 'maintenance')->get();
        $user = User::first(); // Get first user (admin)

        if ($menuItems->isEmpty() || $tables->isEmpty()) {
            $this->command->error('No menu items or tables found. Please seed those first.');
            return;
        }

        $this->command->info('Seeding 1000 sales for January 2026...');

        // Start date: January 1, 2026
        $startDate = Carbon::create(2026, 1, 1, 8, 0, 0); // 8 AM
        $endDate = Carbon::create(2026, 1, 31, 23, 0, 0); // 11 PM

        $totalOrders = 1000;
        $createdOrders = 0;

        DB::beginTransaction();

        try {
            // Distribute orders throughout the month with realistic patterns
            for ($i = 0; $i < $totalOrders; $i++) {
                // Create random timestamp within January 2026
                $randomTimestamp = $this->getRandomTimestamp($startDate, $endDate);

                // Peak hours distribution (more orders during lunch and dinner)
                $hour = $randomTimestamp->hour;
                if (($hour >= 12 && $hour <= 14) || ($hour >= 19 && $hour <= 21)) {
                    // Lunch (12-2 PM) and Dinner (7-9 PM) - higher chance
                    $orderProbability = 0.8;
                } elseif (($hour >= 11 && $hour <= 15) || ($hour >= 18 && $hour <= 22)) {
                    // Extended meal times - medium chance
                    $orderProbability = 0.6;
                } else {
                    // Off-peak hours - lower chance
                    $orderProbability = 0.3;
                }

                // Skip some orders based on probability
                if (rand(0, 100) / 100 > $orderProbability) {
                    continue;
                }

                // Select random table
                $table = $tables->random();

                // Create table session for this order
                $tableSession = TableSession::create([
                    'restaurant_table_id' => $table->id,
                    'user_id' => $user->id,
                    'opened_at' => $randomTimestamp,
                    'closed_at' => $randomTimestamp->copy()->addHours(rand(1, 3)), // Session lasts 1-3 hours
                    'guest_count' => rand(1, 6),
                    'status' => 'completed',
                    'created_at' => $randomTimestamp,
                    'updated_at' => $randomTimestamp,
                ]);

                // Create order
                $order = Order::create([
                    'table_session_id' => $tableSession->id,
                    'user_id' => $user->id,
                    'status' => 'completed',
                    'subtotal' => 0, // Will calculate
                    'discount_amount' => 0,
                    'tax_amount' => 0, // Will calculate
                    'total_amount' => 0, // Will calculate
                    'created_at' => $randomTimestamp,
                    'updated_at' => $randomTimestamp,
                ]);

                // Generate order items (1-5 items per order)
                $numItems = rand(1, 5);
                $subtotal = 0;
                $orderItems = [];

                for ($j = 0; $j < $numItems; $j++) {
                    $menuItem = $menuItems->random();
                    $quantity = rand(1, 3);

                    $itemTotal = $menuItem->price * $quantity;
                    $subtotal += $itemTotal;

                    $orderItems[] = [
                        'order_id' => $order->id,
                        'menu_item_id' => $menuItem->id,
                        'quantity' => $quantity,
                        'unit_price' => $menuItem->price,
                        'line_total' => $itemTotal,
                        'status' => 'completed',
                        'notes' => null,
                        'created_at' => $randomTimestamp,
                        'updated_at' => $randomTimestamp,
                    ];
                }

                // Calculate tax (18% GST)
                $taxAmount = $subtotal * 0.18;
                $totalAmount = $subtotal + $taxAmount;

                // Update order with calculated amounts
                $order->subtotal = $subtotal;
                $order->tax_amount = $taxAmount;
                $order->total_amount = $totalAmount;
                $order->save();

                // Create order payment
                $paymentMethods = ['cash', 'card', 'upi', 'wallet'];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

                OrderPayment::create([
                    'order_id' => $order->id,
                    'amount' => $totalAmount,
                    'method' => $paymentMethod,
                    'payment_status' => 'completed',
                    'razorpay_paymentid' => $paymentMethod === 'card' ? 'pay_' . strtoupper(substr(md5(uniqid()), 0, 14)) : null,
                    'razorpay_orderid' => $paymentMethod === 'card' ? 'order_' . strtoupper(substr(md5(uniqid()), 0, 14)) : null,
                    'created_at' => $randomTimestamp,
                    'updated_at' => $randomTimestamp,
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    OrderItem::create($item);
                }

                $createdOrders++;

                // Progress indicator
                if ($createdOrders % 100 === 0) {
                    $this->command->info("Created {$createdOrders} orders...");
                }
            }

            DB::commit();

            $this->command->info("✅ Successfully created {$createdOrders} orders for January 2026!");
            $this->command->info("📊 Total sales value: ₹" . number_format(Order::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount'), 2));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding data: ' . $e->getMessage());
        }
    }

    /**
     * Generate a random timestamp within the given date range
     */
    private function getRandomTimestamp(Carbon $startDate, Carbon $endDate): Carbon
    {
        $startTimestamp = $startDate->timestamp;
        $endTimestamp = $endDate->timestamp;

        $randomTimestamp = rand($startTimestamp, $endTimestamp);

        return Carbon::createFromTimestamp($randomTimestamp);
    }
}
