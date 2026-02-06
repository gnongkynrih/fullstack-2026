<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $filter = 'all'; // all, read, unread

    protected $queryString = [
        'filter' => ['except' => 'all'],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
            $this->dispatch('notification-read', notificationId: $notificationId);
        }
    }

    public function markAsUnread($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification && $notification->read_at) {
            $notification->update(['read_at' => null]);
            $this->dispatch('notification-unread', notificationId: $notificationId);
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->dispatch('all-notifications-read');
        session()->flash('message', 'All notifications marked as read.');
    }

    public function deleteNotification($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
            $this->dispatch('notification-deleted', notificationId: $notificationId);
            session()->flash('message', 'Notification deleted.');
        }
    }

    public function deleteAllRead()
    {
        auth()->user()->notifications()->whereNotNull('read_at')->delete();
        $this->dispatch('read-notifications-deleted');
        session()->flash('message', 'All read notifications deleted.');
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }

    public function getNotificationsProperty()
    {
        $query = auth()->user()->notifications()->latest();

        switch ($this->filter) {
            case 'read':
                $query->whereNotNull('read_at');
                break;
            case 'unread':
                $query->whereNull('read_at');
                break;
            // 'all' - no filter
        }

        return $query->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.notification-list', [
            'notifications' => $this->notifications,
        ]);
    }
}
