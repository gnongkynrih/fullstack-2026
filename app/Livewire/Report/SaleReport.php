<?php

namespace App\Livewire\Report;

use Carbon\Carbon;
use App\Models\Order;
use Livewire\Component;

use App\Models\OrderPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SalesReportExport;
use Maatwebsite\Excel\Facades\Excel;

class SaleReport extends Component
{
    public $startDate;
    public $endDate;
    public $sales = [];
    public $totalRevenue = 0;
    public $totalOrders = 0;
    public $totalItems = 0;

    public function mount()
    {
        $this->startDate = Carbon::today()->format('Y-m-d');
        $this->endDate = Carbon::today()->format('Y-m-d');
        $this->generateReport();
    }

    public function generateReport()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $this->sales = OrderPayment::with(['order.orderItems.menuItem', 'order.tableSession.table', 'order.user'])
            ->where('payment_status', 'success')
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->get();
        // $this->sales = $query->get();

        // $this->sales = $payments->map(function ($payment) {
        //     return [
        //         'id' => $payment->id,
        //         'order_id' => $payment->order_id,
        //         'date' => $payment->created_at->format('Y-m-d H:i:s'),
        //         'table' => $payment->order->tableSession->table->name ?? 'N/A',
        //         'waiter' => $payment->order->user->name ?? 'N/A',
        //         'items_count' => $payment->order->orderItems->count(),
        //         'amount' => $payment->amount,
        //         'method' => ucfirst($payment->method),
        //     ];
        // })->toArray();

        $this->totalRevenue = $this->sales->sum('amount');
        //select count(*) from order_payments where payment_status = 'success' and created_at between '2026-01-01' and '2026-01-31'
        $this->totalOrders = $this->sales->count();
        $this->totalItems = $this->sales->sum(function ($p) {
            return $p->order->orderItems->sum('quantity');
        });
    }

    public function exportExcel()
    {
        $this->generateReport();
        
        $filename = 'sales_report_' . $this->startDate . '_to_' . $this->endDate . '.xlsx';
        
        return Excel::download(
            new SalesReportExport(
                $this->sales, 
                $this->startDate, 
                $this->endDate, 
                $this->totalRevenue, 
                $this->totalOrders, 
                $this->totalItems),
            $filename
        );
    }

    public function exportPdf()
    {
        $this->generateReport();
        
        $pdf = Pdf::loadView('reports.sales-pdf', [
            'sales' => $this->sales,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'totalRevenue' => $this->totalRevenue,
            'totalOrders' => $this->totalOrders,
            'totalItems' => $this->totalItems,
        ]);
        
        $filename = 'sales_report_' . $this->startDate . '_to_' . $this->endDate . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {
        return view('livewire.report.sale-report');
    }
}
