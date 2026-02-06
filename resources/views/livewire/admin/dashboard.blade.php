<div class="max-w-7xl mx-auto p-6 bg-neutral-50">
    <p class="text-2xl font-bold mb-6 text-primary-600">Dashboard</p>

    <!-- Month Picker -->
    <x-card title="Filter Options" subtitle="Select month and year for reports" shadow separator class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-select
                label="Month"
                wire:model="selectedMonth"
                wire:change="updateFilters"
                placeholder="Select Month"
                :options="$monthOptions"
                option-value="value"
                option-label="label">
            </x-select>

            <x-select
                label="Year"
                wire:model="selectedYear"
                wire:change="updateFilters"
                placeholder="Select Year"
                :options="$yearOptions"
                option-value="value"
                option-label="label">
            </x-select>
        </div>
    </x-card>

    <!-- Sales Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Daily Sales Card -->
        <x-card title="Daily Sales" subtitle="{{ $dailySales['date_range']['formatted'] ?? \Carbon\Carbon::now()->format('d M Y') }}" shadow separator>
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="text-3xl font-bold text-primary-600 mb-2">
                        ₹{{ isset($dailySales['total']) ? number_format($dailySales['total'], 2) : '0.00' }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ isset($dailySales['orders']) ? $dailySales['orders'] : '0' }} orders
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <x-button
                        wire:click="previousDay"
                        icon="o-chevron-left"
                        class="btn-ghost btn-sm"
                    />
                    <x-button
                        wire:click="nextDay"
                        icon="o-chevron-right"
                        class="btn-ghost btn-sm"
                    />
                </div>
            </div>
        </x-card>

        <!-- Weekly Sales Card -->
        <x-card title="Weekly Sales" subtitle="{{ $weeklySales['date_range']['formatted'] ?? 'Last 7 days' }}" shadow separator>
            <div class="text-3xl font-bold text-secondary-600 mb-2">
                ₹{{ isset($weeklySales['total']) ? number_format($weeklySales['total'], 2) : '0.00' }}
            </div>
            <div class="text-sm text-gray-500">
                {{ isset($weeklySales['orders']) ? $weeklySales['orders'] : '0' }} orders this week
            </div>
        </x-card>

        <!-- Monthly Sales Card -->
        <x-card title="Monthly Sales" subtitle="{{ $monthlySales['month'] ?? \Carbon\Carbon::now()->format('F Y') }}" shadow separator>
            <div class="text-3xl font-bold text-accent-600 mb-2">
                ₹{{ isset($monthlySales['total']) ? number_format($monthlySales['total'], 2) : '0.00' }}
            </div>
            <div class="text-sm text-gray-500">
                {{ isset($monthlySales['orders']) ? $monthlySales['orders'] : '0' }} orders this month
            </div>
        </x-card>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        @include('livewire.admin.dashboard-partial._weekly_sales_chart')

        @include('livewire.admin.dashboard-partial._customer_traffic_chart')
    </div>

    <!-- Categories Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        @include('livewire.admin.dashboard-partial._top_categories_chart')

        <!-- Top Items Table -->
        <x-card title="Top Selling Items" subtitle="{{ $itemSales['date_range']['formatted'] ?? 'Last 30 days' }}" shadow separator>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th class="text-left">Item</th>
                            <th class="text-left">Quantity Sold</th>
                            <th class="text-left">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($itemSales['items']) && count($itemSales['items']) > 0)
                            @foreach($itemSales['items'] as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>{{ $item['order_count'] }}</td>
                                    <td>₹{{ number_format($item['sales'], 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center text-gray-500">No item data available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let charts = {};

    function initializeCharts() {
        // Destroy existing charts
        Object.values(charts).forEach(chart => {
            if (chart) chart.destroy();
        });
        charts = {};

        console.log('Initializing dashboard charts...');

        // Initialize each chart from their respective partials
        initializeWeeklySalesChart();
        initializeTopCategoriesChart();
        initializeCustomerTrafficChart();

        console.log('Dashboard charts initialized successfully');
    }

     // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initializeCharts, 100);
    });

    // Initialize after Livewire loads
    document.addEventListener('livewire:navigated', function() {
        setTimeout(initializeCharts, 100);
    });

    // Fallback: Initialize after window load
    window.addEventListener('load', function() {
        setTimeout(initializeCharts, 150);
    });
</script>
@endpush