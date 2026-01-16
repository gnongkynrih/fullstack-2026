<div class="mx-auto p-6 bg-neutral-50 min-h-screen flex justify-center">
  <div class="w-full max-w-md">
    <!-- Change Password Card -->
    <x-card shadow separator class="bg-white">
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-primary-600">Change Password</h1>
        <p class="mt-1 text-sm text-gray-600">Update your account password for better security</p>
      </div>

      @if (session()->has('status'))
        <x-alert class="mb-4" title="Success" class="alert-success">
          {{ session('status') }}
        </x-alert>
      @endif

      <x-form wire:submit.prevent="changePassword">
        <div class="space-y-4">
          <x-input
            label="Current Password"
            wire:model="current_password"
            type="password"
            placeholder="Enter your current password"
            icon="o-lock-closed"
            hint="Your existing password"
          />

          <x-input
            label="New Password"
            wire:model="password"
            type="password"
            placeholder="Enter a new password"
            icon="o-lock-closed"
            hint="At least 8 characters"
          />

          <x-input
            label="Confirm New Password"
            wire:model="password_confirmation"
            type="password"
            placeholder="Confirm your new password"
            icon="o-lock-closed"
            hint="Repeat your new password"
          />
        </div>

        <x-slot:actions class="mt-6">
          <x-button
            label="Change Password"
            type="submit"
            spinner
            class="w-full bg-primary-500 hover:bg-primary-600 text-white"
            icon="o-shield-check"
          />
        </x-slot:actions>
      </x-form>

      <div class="mt-4 text-center">
        <a href="{{ route('dashboard') }}" class="text-sm text-primary-600 hover:text-primary-500 font-medium">
          Back to Dashboard
        </a>
      </div>
    </x-card>
  </div>
</div>
