# Sales Report Feature

## Overview
The Sales Report feature allows you to view, filter, and export sales data based on date ranges. It provides comprehensive insights into your restaurant's revenue, orders, and items sold.

## Features

### 1. Date Range Filtering
- Select start and end dates to filter sales data
- Default view shows today's sales
- Validates that end date is not before start date

### 2. Summary Dashboard
Displays three key metrics:
- **Total Revenue**: Sum of all completed payments
- **Total Orders**: Number of completed orders
- **Total Items Sold**: Total quantity of items sold

### 3. Detailed Sales Table
Shows individual transactions with:
- Payment ID
- Order ID
- Date & Time
- Table name
- Waiter name
- Number of items
- Payment method (Cash/Online)
- Amount

### 4. Export Options

#### Excel Export
- Generates `.xlsx` file with formatted data
- Includes report header with date range
- Auto-sized columns for readability
- Summary section at the bottom
- Filename format: `sales_report_YYYY-MM-DD_to_YYYY-MM-DD.xlsx`

#### PDF Export
- Generates professional PDF report
- Styled with proper formatting
- Includes summary cards
- Color-coded payment methods
- Filename format: `sales_report_YYYY-MM-DD_to_YYYY-MM-DD.pdf`

## Technical Details

### Files Created
1. **Component**: `/app/Livewire/Report/SaleReport.php`
2. **View**: `/resources/views/livewire/report/sale-report.blade.php`
3. **Excel Export**: `/app/Exports/SalesReportExport.php`
4. **PDF Template**: `/resources/views/reports/sales-pdf.blade.php`

### Dependencies Installed
- `maatwebsite/excel` (^3.1) - For Excel export functionality
- `barryvdh/laravel-dompdf` (^3.1) - For PDF generation

### Data Source
The report pulls data from the `order_payments` table where:
- `payment_status` = 'completed'
- `created_at` is within the selected date range

### Relationships Used
- OrderPayment → Order → OrderItems → MenuItem
- OrderPayment → Order → TableSession → RestaurantTable
- OrderPayment → Order → User

## Usage

### Accessing the Report
Navigate to the sales report route (configure in your routes file):
```php
Route::get('/reports/sales', SaleReport::class)->name('reports.sales');
```

### Generating a Report
1. Select start date
2. Select end date
3. Click "Generate Report"
4. View the results in the table below

### Exporting Data
- Click "Export Excel" for spreadsheet format
- Click "Export PDF" for printable format

## Notes
- Only completed payments are included in the report
- All amounts are displayed in Indian Rupees (₹)
- Reports are generated in real-time based on current database data
- No data is cached - always shows the latest information
