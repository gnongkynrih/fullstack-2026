<div class="relative">
    <!-- Notification Bell Button -->
    <button type="button"
            wire:click="toggleDropdown"
            class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 rounded-md">
        <span class="sr-only">View notifications</span>
        <x-icon name="o-bell" class="h-6 w-6" />

        <!-- Unread count badge -->
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    @if($showDropdown)
        <div class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50"
             x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             @click.away="show = false; $wire.set('showDropdown', false)">

            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t-md">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">Notifications</h3>
                    @if($unreadCount > 0)
                        <button type="button"
                                wire:click="markAllAsRead"
                                class="text-xs text-primary-600 hover:text-primary-500">
                            Mark all read
                        </button>
                    @endif
                </div>
            </div>

            <!-- Notifications List -->
            <div class="max-h-96 overflow-y-auto">
                @if($notifications->count() > 0)
                    @foreach($notifications as $notification)
                        <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                            <div class="flex items-start space-x-3">
                                <!-- Notification Icon -->
                                <div class="flex-shrink-0">
                                    @if($notification->data['type'] === 'order_placed')
                                        <x-icon name="o-shopping-bag" class="h-5 w-5 text-green-500" />
                                    @elseif($notification->data['type'] === 'order_ready')
                                        <x-icon name="o-check-circle" class="h-5 w-5 text-blue-500" />
                                    @elseif($notification->data['type'] === 'order_cancelled')
                                        <x-icon name="o-x-circle" class="h-5 w-5 text-red-500" />
                                    @else
                                        <x-icon name="o-bell" class="h-5 w-5 text-gray-500" />
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0 flex space-x-1">
                                    @if(!$notification->read_at)
                                        <button type="button"
                                                wire:click="markAsRead('{{ $notification->id }}')"
                                                class="text-xs text-primary-600 hover:text-primary-500">
                                            Mark read
                                        </button>
                                    @endif
                                    <button type="button"
                                            wire:click="deleteNotification('{{ $notification->id }}')"
                                            class="text-xs text-red-600 hover:text-red-500">
                                        <x-icon name="o-trash" class="h-3 w-3" />
                                    </button>
                                </div>
                            </div>

                            <!-- Action Button (if applicable) -->
                            @if(isset($notification->data['action_url']))
                                <div class="mt-3">
                                    <a href="{{ $notification->data['action_url'] }}"
                                       class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-primary-700 bg-primary-100 hover:bg-primary-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        View Details
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="px-4 py-8 text-center">
                        <x-icon name="o-bell" class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                        <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            @if($notifications->count() > 0)
                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 rounded-b-md">
                    <a href="{{ route('notifications.all') }}"
                       class="text-sm font-medium text-primary-600 hover:text-primary-500">
                        View all notifications
                    </a>
                </div>
            @endif
        </div>
    @endif
</div>
