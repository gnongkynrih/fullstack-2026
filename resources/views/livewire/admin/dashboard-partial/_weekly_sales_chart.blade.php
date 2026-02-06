{{-- Weekly Sales Chart Section --}}
<!-- Weekly Sales Chart -->
<div class="lg:col-span-2">
    <x-card title="Weekly Sales Trend" subtitle="{{ $weeklySales['date_range']['formatted'] ?? 'Last 7 days' }}" shadow separator>
        <div class="h-80">
            <canvas id="weeklySalesChart"></canvas>
        </div>
    </x-card>
</div>

@push('scripts')
<script>
function initializeWeeklySalesChart() {
    let weeklySalesCtx = document.getElementById('weeklySalesChart');
    if (weeklySalesCtx) {
        console.log('Weekly sales canvas found');
        const weeklyLabels = @json($weeklySales->pluck('date'));
        const weeklyData = @json($weeklySales->pluck('amount'));
        console.log('Weekly labels:', weeklyLabels);
        console.log('Weekly data:', weeklyData);

        charts.weeklySales = new Chart(weeklySalesCtx, {
            type: 'line',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    label: 'Sales (₹)',
                    data: weeklyData,
                    backgroundColor: 'rgba(99, 102, 241, 0.2)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            }
                        }
                    }
                }
            }
        });
    }
}
</script>
@endpush
