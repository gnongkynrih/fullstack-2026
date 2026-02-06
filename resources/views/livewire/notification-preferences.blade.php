<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600">
            <h1 class="text-2xl font-bold text-white">Notification Preferences</h1>
            <p class="text-blue-100 mt-1">Choose how you want to receive notifications</p>
        </div>

        <div class="p-6">
            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Notification Preferences -->
            <div class="space-y-6">
                @foreach($notificationTypes as $type => $config)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $config['label'] }}</h3>
                                <p class="text-gray-600 mt-1">{{ $config['description'] }}</p>
                            </div>

                            <!-- Enable/Disable Toggle -->
                            <div class="ml-4">
                                <x-switch
                                    wire:model.live="preferences.{{ $type }}.enabled"
                                    on-label="Enabled"
                                    off-label="Disabled"
                                    class="text-sm"
                                />
                            </div>
                        </div>

                        <!-- Channel Selection (only show if enabled) -->
                        @if($preferences[$type]['enabled'] ?? false)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Notification Channels:</h4>
                                <div class="flex flex-wrap gap-4">
                                    <!-- Database Channel -->
                                    <label class="inline-flex items-center">
                                        <input type="checkbox"
                                               wire:model.live="preferences.{{ $type }}.channels"
                                               value="database"
                                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <x-icon name="o-bell" class="h-4 w-4 inline mr-1" />
                                            In-App
                                        </span>
                                    </label>

                                    <!-- Email Channel -->
                                    <label class="inline-flex items-center">
                                        <input type="checkbox"
                                               wire:model.live="preferences.{{ $type }}.channels"
                                               value="mail"
                                               class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <x-icon name="o-envelope" class="h-4 w-4 inline mr-1" />
                                            Email
                                        </span>
                                    </label>

                                    <!-- SMS Channel (if you implement SMS) -->
                                    <label class="inline-flex items-center opacity-50" title="SMS notifications coming soon">
                                        <input type="checkbox"
                                               disabled
                                               class="rounded border-gray-300 text-primary-600 shadow-sm">
                                        <span class="ml-2 text-sm text-gray-500">
                                            <x-icon name="o-device-phone-mobile" class="h-4 w-4 inline mr-1" />
                                            SMS (Coming Soon)
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Actions -->
            <div class="mt-8 flex justify-between items-center pt-6 border-t border-gray-200">
                <button type="button"
                        wire:click="resetToDefaults"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Reset to Defaults
                </button>

                <div class="text-sm text-gray-500">
                    Changes are saved automatically
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listen for preference updates
    document.addEventListener('livewire:init', () => {
        Livewire.on('preference-updated', (event) => {
            // Show a brief success indicator
            console.log('Preference updated:', event.type);
        });
    });
</script>
@endpush
