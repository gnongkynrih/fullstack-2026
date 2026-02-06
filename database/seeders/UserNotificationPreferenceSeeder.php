<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserNotificationPreference;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserNotificationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all existing users
        $users = User::all();

        foreach ($users as $user) {
            // Create default notification preferences for each user
            $defaultPreferences = UserNotificationPreference::getDefaultPreferences();

            foreach ($defaultPreferences as $type => $channels) {
                UserNotificationPreference::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'notification_type' => $type,
                    ],
                    [
                        'channels' => $channels,
                        'enabled' => true,
                    ]
                );
            }
        }
    }
}
