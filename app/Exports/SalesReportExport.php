<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesReportExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithCustomStartCell, WithEvents
{
    protected $sales;
    protected $startDate;
    protected $endDate;
    protected $totalRevenue;
    protected $totalOrders;
    protected $totalItems;

    public function __construct($sales, $startDate, $endDate, $totalRevenue, $totalOrders, $totalItems)
    {
        $this->sales = collect($sales);
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalRevenue = $totalRevenue;
        $this->totalOrders = $totalOrders;
        $this->totalItems = $totalItems;
    }

    public function collection()
    {
        return $this->sales->map(function ($sale) {
            return [
                'payment_id' => $sale['id'],
                'order_id' => $sale['order_id'],
                'date' => $sale['date'],
                'table' => $sale['table'],
                'waiter' => $sale['waiter'],
                'items_count' => $sale['items_count'],
                'method' => $sale['method'],
                'amount' => $sale['amount'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Payment ID',
            'Order ID',
            'Date & Time',
            'Table',
            'Waiter',
            'Items',
            'Payment Method',
            'Amount (₹)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['bold' => true, 'size' => 12]],
            3 => ['font' => ['bold' => true, 'size' => 12]],
            5 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E2E8F0']]],
        ];
    }

    public function title(): string
    {
        return 'Sales Report';
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $sheet->setCellValue('A1', 'Sales Report');
                $sheet->setCellValue('A2', 'Period: ' . $this->startDate . ' to ' . $this->endDate);
                $sheet->setCellValue('A3', 'Generated on: ' . now()->format('Y-m-d H:i:s'));
                
                $lastRow = $sheet->getHighestRow();
                $sheet->setCellValue('A' . ($lastRow + 2), 'Summary');
                $sheet->setCellValue('A' . ($lastRow + 3), 'Total Orders:');
                $sheet->setCellValue('B' . ($lastRow + 3), $this->totalOrders);
                $sheet->setCellValue('A' . ($lastRow + 4), 'Total Items Sold:');
                $sheet->setCellValue('B' . ($lastRow + 4), $this->totalItems);
                $sheet->setCellValue('A' . ($lastRow + 5), 'Total Revenue:');
                $sheet->setCellValue('B' . ($lastRow + 5), '₹' . number_format($this->totalRevenue, 2));
                
                $sheet->getStyle('A' . ($lastRow + 2))->getFont()->setBold(true)->setSize(12);
                $sheet->getStyle('A' . ($lastRow + 3) . ':A' . ($lastRow + 5))->getFont()->setBold(true);
                
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
