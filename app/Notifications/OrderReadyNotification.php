<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderReadyNotification extends Notification implements ShouldQueue
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
            ->where('notification_type', 'order_ready')
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
            ->subject('Your Order is Ready - #' . $this->order->id)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your order is now ready.')
            ->line('**Order ID:** #' . $this->order->id)
            ->line('**Total Amount:** ₹' . number_format($this->order->total_amount, 2))
            ->action('Pickup/Delivery Instructions', route('view-order', ['order' => $this->order->id]))
            ->line('Please collect your order within the next 30 minutes.')
            ->salutation('Enjoy your meal!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Order Ready for Pickup',
            'message' => 'Your order #' . $this->order->id . ' is ready for pickup/delivery.',
            'order_id' => $this->order->id,
            'total_amount' => $this->order->total_amount,
            'type' => 'order_ready',
            'action_url' => route('order.details', $this->order->id),
        ];
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms(object $notifiable): string
    {
        return "Your order #" . $this->order->id . " is ready for pickup. Total: ₹" . number_format($this->order->total_amount, 2);
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
