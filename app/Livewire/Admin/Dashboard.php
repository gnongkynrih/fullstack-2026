<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\ReportService;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $selectedMonth;
    public $selectedYear;
    public $currentDate;

    public $monthOptions = [];
    public $yearOptions = [];

    public $weeklySales;
    public $dailySales;
    public $monthlySales;
    public $itemSales;
    public $categorySales;
    public $customerTraffic;

    public function mount()
    {
        $this->selectedMonth = request('month', date('m'));
        $this->selectedYear = request('year', date('Y'));
        $this->currentDate = request('date', Carbon::now()->format('Y-m-d'));

        // Generate month options
        $this->monthOptions = [];
        for ($i = 1; $i <= 12; $i++) {
            $this->monthOptions[] = [
                'value' => $i,
                'label' => date('F', mktime(0, 0, 0, $i, 1))
            ];
        }

        // Generate year options
        $this->yearOptions = [];
        $startYear = date('Y') - 2;
        $endYear = date('Y') + 1;
        for ($i = $startYear; $i <= $endYear; $i++) {
            $this->yearOptions[] = [
                'value' => $i,
                'label' => $i
            ];
        }

        $this->loadData();
    }

    public function updatedSelectedMonth()
    {
        \Log::info("Month changed to: {$this->selectedMonth}");
        $this->updateFilters();
    }

    public function updatedSelectedYear()
    {
        \Log::info("Year changed to: {$this->selectedYear}");
        $this->updateFilters();
    }

    public function updateFilters()
    {
        return redirect()->route('admin.dashboard', [
            'month' => $this->selectedMonth,
            'year' => $this->selectedYear
        ]);
    }

    public function loadData()
    {
        if ($this->selectedMonth && $this->selectedYear) {
            // Create Carbon instances for the selected month
            $startDate = Carbon::create($this->selectedYear, $this->selectedMonth)->startOfMonth()->format('Y-m-d');
            $endDate = Carbon::create($this->selectedYear, $this->selectedMonth)->endOfMonth()->format('Y-m-d');

            \Log::info("Loading dashboard data for {$this->selectedMonth}/{$this->selectedYear} - {$startDate} to {$endDate}");

            // Get weekly and daily sales data (these remain the same regardless of month selection)
            $this->weeklySales = ReportService::getDashboardWeeklySalesChartData($this->selectedMonth, $this->selectedYear);
            $this->dailySales = ReportService::getDashboardDailySales($this->currentDate);

            // Get monthly data filtered by the selected month
            $this->monthlySales = ReportService::getDashboardMonthlySales($startDate, $endDate);
            $this->itemSales = ReportService::getDashboardItemSales($startDate, $endDate);
            $this->categorySales = ReportService::getDashboardCategorySalesChartData($startDate, $endDate);
            $this->customerTraffic = ReportService::getCustomerTrafficChartData($startDate, $endDate);
            
            // Update the month display in monthlySales
            $this->monthlySales['month'] = Carbon::create($this->selectedYear, $this->selectedMonth)->format('F Y');

            \Log::info("Dashboard data loaded - Monthly: ₹{$this->monthlySales['total']}, Categories: " . count($this->categorySales) . ", Traffic: {$this->customerTraffic['total_orders']}");
        } else {
            \Log::info("Loading default dashboard data (current month)");

            // Default to current month
            $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');

            $this->weeklySales = ReportService::getDashboardWeeklySalesChartData();
            $this->dailySales = ReportService::getDashboardDailySales($this->currentDate);
            $this->monthlySales = ReportService::getDashboardMonthlySales($startOfMonth, $endOfMonth);
            $this->itemSales = ReportService::getDashboardItemSales($startOfMonth, $endOfMonth);
            $this->categorySales = ReportService::getDashboardCategorySalesChartData($startOfMonth, $endOfMonth);
            $this->customerTraffic = ReportService::getCustomerTrafficChartData($startOfMonth, $endOfMonth);
        }
    }

    public function previousDay()
    {
        $previousDate = Carbon::parse($this->currentDate)->subDay()->format('Y-m-d');
        return redirect()->route('admin.dashboard', [
            'month' => $this->selectedMonth,
            'year' => $this->selectedYear,
            'date' => $previousDate
        ]);
    }

    public function nextDay()
    {
        $nextDate = Carbon::parse($this->currentDate)->addDay();
        if ($nextDate->lte(Carbon::now())) {
            return redirect()->route('admin.dashboard', [
                'month' => $this->selectedMonth,
                'year' => $this->selectedYear,
                'date' => $nextDate->format('Y-m-d')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'weeklySales' => $this->weeklySales,
            'dailySales' => $this->dailySales,
            'monthlySales' => $this->monthlySales,
            'itemSales' => $this->itemSales,
            'categorySales' => $this->categorySales,
            'customerTraffic' => $this->customerTraffic,
        ]);
    }
}
