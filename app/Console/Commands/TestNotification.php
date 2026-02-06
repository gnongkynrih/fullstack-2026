<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderPlacedNotification;
use Illuminate\Console\Command;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notification {user_id?} {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the notification system by sending a sample notification';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔔 Testing Notification System...');

        // Get user (first user or specified user)
        $userId = $this->argument('user_id');
        $user = $userId ? User::find($userId) : User::first();

        if (!$user) {
            $this->error('❌ No user found!');
            return 1;
        }

        $this->info("👤 User: {$user->name} (ID: {$user->id})");

        // Get order (latest order or specified order)
        $orderId = $this->argument('order_id');
        $order = $orderId ? Order::find($orderId) : Order::latest()->first();

        if (!$order) {
            $this->error('❌ No order found!');
            return 1;
        }

        $this->info("📋 Order: #{$order->id} (Total: ₹{$order->total_amount})");

        // Check user preferences
        $preferences = $user->notificationPreferences()
            ->where('notification_type', 'order_placed')
            ->where('enabled', true)
            ->first();

        if ($preferences) {
            $this->info("⚙️  Preferences: " . implode(', ', $preferences->channels));
        } else {
            $this->info("⚙️  Preferences: Using defaults (database, mail)");
        }

        // Send notification
        $this->info('📤 Sending notification...');
        $user->notify(new OrderPlacedNotification($order));

        // Check if notification was created
        $notificationCount = $user->notifications()->count();
        $unreadCount = $user->unreadNotifications()->count();

        $this->info("✅ Notification sent!");
        $this->info("📊 Total notifications: {$notificationCount}");
        $this->info("📬 Unread notifications: {$unreadCount}");

        // Show latest notification details
        $latestNotification = $user->notifications()->latest()->first();
        if ($latestNotification) {
            $this->info("📝 Latest notification:");
            $this->line("   Type: " . ($latestNotification->data['type'] ?? 'unknown'));
            $this->line("   Title: " . ($latestNotification->data['title'] ?? 'N/A'));
            $this->line("   Read: " . ($latestNotification->read_at ? 'Yes' : 'No'));
            $this->line("   Created: " . $latestNotification->created_at->diffForHumans());
        }

        $this->info('🎉 Notification system test completed!');

        return 0;
    }
}
