{{-- Top Categories Chart Section --}}
<!-- Top Categories Chart -->
<x-card title="Top Selling Categories" subtitle="{{ $monthlySales['month'] ?? \Carbon\Carbon::now()->format('F Y') }}" shadow separator>
    <div class="h-80">
        <canvas id="topCategoriesChart"></canvas>
    </div>
</x-card>

@push('scripts')
<script>
function initializeTopCategoriesChart() {
    let topCategoriesCtx = document.getElementById('topCategoriesChart');
    if (topCategoriesCtx) {
        console.log('Top categories canvas found');
        const categoryLabels = @json($categorySales->pluck('name'));
        const categoryData = @json($categorySales->pluck('total'));
        console.log('Category labels:', categoryLabels);
        console.log('Category data:', categoryData);

        charts.topCategories = new Chart(topCategoriesCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: categoryData,
                    backgroundColor: 'rgba(79, 70, 229, 0.7)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 1
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
