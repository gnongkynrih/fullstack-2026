<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'notification_type',
        'channels',
        'enabled'
    ];

    protected $casts = [
        'channels' => 'array',
        'enabled' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for common notification types
    public static function getDefaultPreferences()
    {
        return [
            'order_placed' => ['database', 'email'],
            'order_ready' => ['database', 'sms'],
            'order_cancelled' => ['database', 'email'],
            'promotional' => ['email'],
            'account_updates' => ['database', 'email'],
        ];
    }

    public function isEnabledForChannel(string $channel): bool
    {
        return $this->enabled && in_array($channel, $this->channels);
    }
}
