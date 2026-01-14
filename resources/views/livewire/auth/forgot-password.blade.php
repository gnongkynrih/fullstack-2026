<div class="max-w-7xl mx-auto p-6 bg-neutral-50 min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md">
    <!-- Forgot Password Card -->
    <x-card shadow separator class="bg-white">
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-primary-600">Forgot Password</h1>
        <p class="mt-1 text-sm text-gray-600">Enter your email address and we'll send you a link to reset your password</p>
      </div>

      @if (session()->has('status'))
        <x-alert class="mb-4" title="Check Your Email" class="alert-success">
          {{ session('status') }}
        </x-alert>
      @endif

      <x-form wire:submit.prevent="sendResetLink">
        <div class="space-y-4">
          <x-input
            label="Email Address"
            wire:model="email"
            type="email"
            placeholder="Enter your email"
            icon="o-envelope"
            hint="We'll send the reset link to this email"
          />
        </div>

        <x-slot:actions class="mt-6">
          <x-button
            label="Send Reset Link"
            type="submit"
            spinner
            class="w-full bg-primary-500 hover:bg-primary-600 text-white"
            icon="o-paper-airplane"
          />
        </x-slot:actions>
      </x-form>

      <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-sm text-primary-600 hover:text-primary-500 font-medium">
          Back to Login
        </a>
      </div>
    </x-card>
  </div>
</div>
