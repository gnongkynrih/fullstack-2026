<div class="max-w-6xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-purple-600">
            <h1 class="text-2xl font-bold text-white">All Notifications</h1>
            <p class="text-blue-100 mt-1">Manage all your notifications in one place</p>
        </div>

        <div class="p-6">
            <!-- Success Message -->
            @if (session()->has('message'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Filters and Actions -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <!-- Filter Tabs -->
                <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                    <a href="{{ route('notifications.all', ['filter' => 'all']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $filter === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        All ({{ auth()->user()->notifications()->count() }})
                    </a>
                    <a href="{{ route('notifications.all', ['filter' => 'unread']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $filter === 'unread' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Unread ({{ auth()->user()->unreadNotifications()->count() }})
                    </a>
                    <a href="{{ route('notifications.all', ['filter' => 'read']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-md {{ $filter === 'read' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Read ({{ auth()->user()->notifications()->whereNotNull('read_at')->count() }})
                    </a>
                </div>

                <!-- Bulk Actions -->
                <div class="flex space-x-2">
                    <button type="button"
                            wire:click="markAllAsRead"
                            class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Mark All Read
                    </button>
                    <button type="button"
                            wire:click="deleteAllRead"
                            class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Delete Read
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="space-y-4">
                @if($notifications->count() > 0)
                    @foreach($notifications as $notification)
                        <div class="border border-gray-200 rounded-lg p-4 {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50 border-blue-200' }}">
                            <div class="flex items-start justify-between">
                                <!-- Notification Content -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0">
                                            @if($notification->data['type'] === 'order_placed')
                                                <x-icon name="o-shopping-bag" class="h-6 w-6 text-green-500" />
                                            @elseif($notification->data['type'] === 'order_ready')
                                                <x-icon name="o-check-circle" class="h-6 w-6 text-blue-500" />
                                            @elseif($notification->data['type'] === 'order_cancelled')
                                                <x-icon name="o-x-circle" class="h-6 w-6 text-red-500" />
                                            @else
                                                <x-icon name="o-bell" class="h-6 w-6 text-gray-500" />
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </h3>
                                            <p class="text-gray-600 mt-1">
                                                {{ $notification->data['message'] ?? '' }}
                                            </p>
                                            <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                                <span>{{ $notification->created_at->format('M j, Y g:i A') }}</span>
                                                @if($notification->read_at)
                                                    <span class="text-green-600">✓ Read</span>
                                                @else
                                                    <span class="text-blue-600">● Unread</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    @if(isset($notification->data['action_url']))
                                        <div class="mt-4">
                                            <a href="{{ $notification->data['action_url'] }}"
                                               class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                View Details
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2 ml-4">
                                    @if(!$notification->read_at)
                                        <button type="button"
                                                wire:click="markAsRead('{{ $notification->id }}')"
                                                class="text-blue-600 hover:text-blue-500 text-sm">
                                            Mark Read
                                        </button>
                                    @else
                                        <button type="button"
                                                wire:click="markAsUnread('{{ $notification->id }}')"
                                                class="text-gray-600 hover:text-gray-500 text-sm">
                                            Mark Unread
                                        </button>
                                    @endif

                                    <button type="button"
                                            wire:click="deleteNotification('{{ $notification->id }}')"
                                            class="text-red-600 hover:text-red-500 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <x-icon name="o-bell" class="mx-auto h-24 w-24 text-gray-400" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No notifications</h3>
                        <p class="mt-2 text-gray-500">
                            @if($filter === 'unread')
                                You don't have any unread notifications.
                            @elseif($filter === 'read')
                                You don't have any read notifications.
                            @else
                                You haven't received any notifications yet.
                            @endif
                        </p>
                        @if($filter !== 'all')
                            <div class="mt-4">
                                <a href="{{ route('notifications.all') }}"
                                   class="text-blue-600 hover:text-blue-500">
                                    View all notifications
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
