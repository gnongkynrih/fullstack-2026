<?php

namespace App\Livewire;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class NotificationCenter extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        // Get user's notifications
        $this->notifications = auth()->user()->notifications()
            ->latest()
            ->take(10)
            ->get();

        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
            $this->loadNotifications(); // Refresh the list
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        if ($this->showDropdown) {
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notification-center');
    }
}
