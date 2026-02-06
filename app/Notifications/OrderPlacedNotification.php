<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification // implements ShouldQueue - temporarily removed
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Get user's notification preferences
        $preferences = $notifiable->notificationPreferences()
            ->where('notification_type', 'order_placed')
            ->where('enabled', true)
            ->first();

        if ($preferences) {
            return $preferences->channels;
        }

        // Default channels if no preferences set
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Confirmation - #' . $this->order->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for your order! Here are the details:')
            ->line('**Order ID:** #' . $this->order->id)
            ->line('**Total Amount:** ₹' . number_format($this->order->total_amount, 2))
            ->line('**Order Date:** ' . $this->order->created_at->format('M j, Y g:i A'))
            ->action('View Order Details', route('view-order', ['order' => $this->order->id]))
            ->line('We will notify you when your order is ready for pickup/delivery.')
            ->salutation('Thank you for choosing our restaurant!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Order Placed Successfully',
            'message' => 'Your order #' . $this->order->id . ' has been placed successfully.',
            'order_id' => $this->order->id,
            'total_amount' => $this->order->total_amount,
            'type' => 'order_placed',
            'action_url' => route('view-order', ['order' => $this->order->id]),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'total_amount' => $this->order->total_amount,
        ];
    }
}
