{{-- Customer Traffic Chart Section --}}
<!-- Customer Traffic Chart -->
<x-card title="Customer Traffic" subtitle="{{ $monthlySales['month'] ?? \Carbon\Carbon::now()->format('F Y') }} · {{ $customerTraffic['days_in_range'] ?? 0 }} days · {{ number_format($customerTraffic['average_daily'] ?? 0, 1) }} customers/day" shadow separator>
    <div class="h-80">
        <canvas id="customerTrafficChart"></canvas>
    </div>
</x-card>

@push('scripts')
<script>
function initializeCustomerTrafficChart() {
    let customerTrafficCtx = document.getElementById('customerTrafficChart');
    if (customerTrafficCtx) {
        console.log('Customer traffic canvas found');
        const trafficLabels = @json($customerTraffic['hourly']->pluck('hour'));
        const trafficData = @json($customerTraffic['hourly']->pluck('percentage'));
        const trafficColors = [
            'rgba(255, 99, 132, 0.7)',   // Red
            'rgba(54, 162, 235, 0.7)',   // Blue
            'rgba(255, 206, 86, 0.7)',   // Yellow
            'rgba(75, 192, 192, 0.7)',   // Green
            'rgba(153, 102, 255, 0.7)',  // Purple
            'rgba(255, 159, 64, 0.7)',   // Orange
            'rgba(255, 99, 71, 0.7)',    // Tomato
            'rgba(50, 205, 50, 0.7)',    // Lime Green
            'rgba(0, 191, 255, 0.7)',    // Deep Sky Blue
            'rgba(255, 165, 0, 0.7)',    // Orange
            'rgba(186, 85, 211, 0.7)',   // Medium Orchid
            'rgba(220, 20, 60, 0.7)'     // Crimson
        ];

        charts.customerTraffic = new Chart(customerTrafficCtx, {
            type: 'pie',
            data: {
                labels: trafficLabels,
                datasets: [{
                    data: trafficData,
                    backgroundColor: trafficColors.slice(0, @json(count($customerTraffic['hourly']))),
                    borderColor: 'rgba(255, 255, 255, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Customer Traffic Distribution (India Timezone GMT+5:30)',
                        font: {
                            size: 14
                        }
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 8,
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value}%`;
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
