<div class="max-w-7xl mx-auto p-6 bg-neutral-50 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md">
    <!-- Reset Password Card -->
    <x-card shadow separator class="bg-neutral-50">
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-primary-600">Reset Password</h1>
        <p class="mt-1 text-sm text-gray-600">Enter your email and set a new password</p>
      </div>

      <x-form wire:submit.prevent="resetPassword">
        <div class="space-y-4">
          <x-input
            label="Email Address"
            wire:model="email"
            type="email"
            placeholder="Enter your email"
            icon="o-envelope"
            hint="Email associated with your account"
          />

          <x-input
            label="New Password"
            wire:model="password"
            type="password"
            placeholder="Enter a new password"
            icon="o-lock-closed"
            hint="At least 4 characters"
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
            label="Reset Password"
            type="submit"
            spinner
            class="w-full bg-primary-500 hover:bg-primary-600 text-white"
            icon="o-arrow-path"
          />
        </x-slot:actions>
      </x-form>

      @error('email')
        <x-alert class="mt-4" title="Reset Error" class="alert-error">
          {{ $message }}
        </x-alert>
      @enderror

      <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-500 font-medium">
          Back to Login
        </a>
      </div>
    </x-card>
  </div>
</div>
