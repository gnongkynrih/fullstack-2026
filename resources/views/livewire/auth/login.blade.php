<div class="max-w-7xl mx-auto p-6  min-h-screen flex items-center justify-center">
  <div class="w-full max-w-md">
    <!-- Login Card -->
    <x-card shadow separator class="bg-neutral-50">
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-primary-600">Welcome Back</h1>
        <p class="mt-1 text-sm text-gray-600">Sign in to access your restaurant management dashboard</p>
      </div>

      <x-form wire:submit.prevent="login">
        <div class="space-y-4">
          <x-input
            label="Email Address"
            wire:model="email"
            type="email"
            placeholder="Enter your email"
            icon="o-envelope"
            hint="We'll never share your email"
          />

          <x-input
            label="Password"
            wire:model="password"
            type="password"
            placeholder="Enter your password"
            icon="o-lock-closed"
          />

          <div class="flex items-center justify-between">
            <label class="flex items-center">
              <input type="checkbox" wire:model="remember" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
              <span class="ml-2 text-sm text-gray-700">Remember me</span>
            </label>

            <a href="#" class="text-sm text-primary-600 hover:text-primary-500 font-medium">
              Forgot password?
            </a>
          </div>
        </div>

        <x-slot:actions class="mt-6">
          <x-button
            label="Sign In"
            type="submit"
            spinner
            class="w-full bg-primary-500 hover:bg-primary-600 text-white"
            icon="o-arrow-right-circle"
          />
        </x-slot:actions>
      </x-form>
    </x-card>
  </div>
</div>
