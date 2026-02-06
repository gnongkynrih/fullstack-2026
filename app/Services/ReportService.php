<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Carbon\Carbon;

class ReportService
{
    public static function getDashboardWeeklySales($month = null, $year = null)
    {
        if ($month && $year) {
            // Show last 7 days of the selected month
            $selectedMonth = Carbon::create($year, $month);
            $endDate = $selectedMonth->copy()->endOfMonth();
            $startDate = $endDate->copy()->subDays(6);
        } else {
            // Default to last 7 days from today
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(6);
        }

        $weeklySales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $days = [];
        $total = 0;
        $totalOrders = 0;

        for ($i = 6; $i >= 0; $i--) {
            $date = $endDate->copy()->subDays($i);
            $dateKey = $date->format('Y-m-d');
            $formattedDate = $date->format('M j');

            if (isset($weeklySales[$dateKey])) {
                $days[$formattedDate] = $weeklySales[$dateKey]->total;
                $total += $weeklySales[$dateKey]->total;
                $totalOrders += $weeklySales[$dateKey]->orders;
            } else {
                $days[$formattedDate] = 0;
            }
        }

        return [
            'days' => $days,
            'total' => $total,
            'orders' => $totalOrders,
            'date_range' => [
                'start' => $startDate->format('M j, Y'),
                'end' => $endDate->format('M j, Y'),
                'formatted' => $startDate->format('M j') . ' - ' . $endDate->format('M j, Y')
            ]
        ];
    }

    public static function getDashboardDailySales($date)
    {
        $date = Carbon::parse($date);

        $dailySales = Order::where('status', 'completed')
            ->whereDate('created_at', $date)
            ->selectRaw('SUM(total_amount) as total, COUNT(*) as orders')
            ->first();

        return [
            'total' => $dailySales->total ?? 0,
            'orders' => $dailySales->orders ?? 0,
            'date_range' => [
                'formatted' => $date->format('d M Y')
            ]
        ];
    }

    public static function getDashboardMonthlySales($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $monthlySales = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('SUM(total_amount) as total, COUNT(*) as orders')
            ->first();

        $month = $startDate->format('F Y');

        return [
            'total' => $monthlySales->total ?? 0,
            'orders' => $monthlySales->orders ?? 0,
            'month' => $month,
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'formatted' => $startDate->format('M j') . ' - ' . $endDate->format('M j, Y')
            ]
        ];
    }

    public static function getDashboardItemSales($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $itemSales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('menu_items.name, SUM(order_items.quantity) as order_count, SUM(order_items.line_total) as sales')
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderBy('sales', 'desc')
            ->limit(10)
            ->get();

        $dateRange = $startDate->format('M j') . ' - ' . $endDate->format('M j, Y');

        return [
            'items' => $itemSales,
            'date_range' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
                'formatted' => $dateRange
            ]
        ];
    }

    public static function getDashboardCategorySales($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $categorySales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('menu_categories', 'menu_items.menu_category_id', '=', 'menu_categories.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('menu_categories.name, SUM(order_items.line_total) as total, SUM(order_items.quantity) as order_count')
            ->groupBy('menu_categories.id', 'menu_categories.name')
            ->orderBy('total', 'desc')
            ->get()
            ->keyBy('name')
            ->toArray();

        return $categorySales;
    }

    public static function getCustomerTraffic($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Get order counts by hour
        $hourlyOrders = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $totalOrders = $hourlyOrders->sum('count');
        $daysInRange = $startDate->diffInDays($endDate) + 1;

        $hourly = [];
        $totalAverage = 0;

        for ($hour = 0; $hour < 24; $hour++) {
            $count = $hourlyOrders[$hour]->count ?? 0;
            $average = $daysInRange > 0 ? $count / $daysInRange : 0;
            $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;

            $hourLabel = sprintf('%02d:00', $hour);

            $hourly[$hourLabel] = [
                'count' => $count,
                'average' => round($average, 1),
                'percentage' => round($percentage, 1)
            ];

            $totalAverage += $average;
        }

        return [
            'hourly' => $hourly,
            'total_orders' => $totalOrders,
            'days_in_range' => $daysInRange,
            'average_daily' => round($totalAverage, 1)
        ];
    }

    // Chart-specific methods that return collections for @json(->pluck()) usage
    public static function getDashboardWeeklySalesChartData($month = null, $year = null)
    {
        $data = self::getDashboardWeeklySales($month, $year);
        return collect($data['days'])->map(function($amount, $date) {
            return [
                'date' => $date,
                'amount' => $amount
            ];
        })->values();
    }

    public static function getDashboardCategorySalesChartData($startDate, $endDate)
    {
        $data = self::getDashboardCategorySales($startDate, $endDate);
        return collect($data)->map(function($categoryData, $categoryName) {
            return [
                'name' => $categoryName,
                'total' => $categoryData['total']
            ];
        })->values();
    }

    public static function getCustomerTrafficChartData($startDate, $endDate)
    {
        $data = self::getCustomerTraffic($startDate, $endDate);
        return [
            'hourly' => collect($data['hourly'])->map(function($hourData, $hour) {
                return [
                    'hour' => $hour,
                    'percentage' => $hourData['percentage']
                ];
            })->values(),
            'total_orders' => $data['total_orders'],
            'days_in_range' => $data['days_in_range'],
            'average_daily' => $data['average_daily']
        ];
    }
}
