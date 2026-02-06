<?php

namespace App\Livewire;

use App\Models\UserNotificationPreference;
use Livewire\Component;

class NotificationPreferences extends Component
{
    public $preferences = [];

    // Available notification types with descriptions
    public $notificationTypes = [
        'order_placed' => [
            'label' => 'Order Placed',
            'description' => 'Receive notifications when you place a new order',
            'default_channels' => ['database', 'email']
        ],
        'order_ready' => [
            'label' => 'Order Ready',
            'description' => 'Receive notifications when your order is ready for pickup/delivery',
            'default_channels' => ['database', 'email']
        ],
        'order_cancelled' => [
            'label' => 'Order Cancelled',
            'description' => 'Receive notifications when your order is cancelled',
            'default_channels' => ['database', 'email']
        ],
        'promotional' => [
            'label' => 'Promotional Offers',
            'description' => 'Receive notifications about special offers and promotions',
            'default_channels' => ['email']
        ],
        'account_updates' => [
            'label' => 'Account Updates',
            'description' => 'Receive notifications about account changes and security updates',
            'default_channels' => ['database', 'email']
        ],
    ];

    public function mount()
    {
        $this->loadPreferences();
    }

    public function loadPreferences()
    {
        $userPreferences = auth()->user()->notificationPreferences;

        foreach ($this->notificationTypes as $type => $config) {
            $pref = $userPreferences->where('notification_type', $type)->first();

            $this->preferences[$type] = [
                'enabled' => $pref ? $pref->enabled : true,
                'channels' => $pref ? $pref->channels : $config['default_channels'],
            ];
        }
    }

    public function updatedPreferences($value, $key)
    {
        // Extract notification type and field from the key
        [$type, $field] = explode('.', $key);

        if ($field === 'enabled' && !$value) {
            // If disabling, also disable all channels
            $this->preferences[$type]['channels'] = [];
        }

        $this->savePreference($type);
    }

    public function updateChannels($type, $channels)
    {
        $this->preferences[$type]['channels'] = $channels;
        $this->savePreference($type);
    }

    private function savePreference($type)
    {
        $pref = $this->preferences[$type];

        UserNotificationPreference::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'notification_type' => $type,
            ],
            [
                'channels' => $pref['channels'],
                'enabled' => $pref['enabled'],
            ]
        );

        $this->dispatch('preference-updated', type: $type);
    }

    public function resetToDefaults()
    {
        foreach ($this->notificationTypes as $type => $config) {
            $this->preferences[$type] = [
                'enabled' => true,
                'channels' => $config['default_channels'],
            ];
            $this->savePreference($type);
        }

        session()->flash('message', 'Notification preferences reset to defaults.');
    }

    public function render()
    {
        return view('livewire.notification-preferences');
    }
}
