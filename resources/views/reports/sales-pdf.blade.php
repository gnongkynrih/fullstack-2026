<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1a1a1a;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
        }
        .summary-item .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #e2e8f0;
            color: #1a1a1a;
            font-weight: bold;
            padding: 10px;
            text-align: left;
            border: 1px solid #cbd5e0;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background-color: #f7fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #e2e8f0;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-cash {
            background-color: #c6f6d5;
            color: #22543d;
        }
        .badge-online {
            background-color: #bee3f8;
            color: #2c5282;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body class="bg-red-400">
    <div class="header">
        <h1>Sales Report</h1>
        <p><strong>Period:</strong> {{ $startDate }} to {{ $endDate }}</p>
        <p><strong>Generated on:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Total Revenue</div>
                <div class="value">
                    <img src="{{ public_path('images/inr.jpg') }}" alt="Rupee" width="20" height="20">
                    {{ number_format($totalRevenue, 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Orders</div>
                <div class="value">{{ $totalOrders }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Items Sold</div>
                <div class="value">{{ $totalItems }}</div>
            </div>
        </div>
    </div>

    @if(count($sales) > 0)
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Order ID</th>
                    <th>Date & Time</th>
                    <th>Table</th>
                    <th>Waiter</th>
                    <th class="text-center">Items</th>
                    <th>Method</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>#{{ $sale['id'] }}</td>
                        <td>#{{ $sale['order_id'] }}</td>
                        <td>{{ $sale['date'] }}</td>
                        <td>{{ $sale['table'] }}</td>
                        <td>{{ $sale['waiter'] }}</td>
                        <td class="text-center">{{ $sale['items_count'] }}</td>
                        <td>
                            <span class="badge {{ $sale['method'] == 'Cash' ? 'badge-cash' : 'badge-online' }}">
                                {{ $sale['method'] }}
                            </span>
                        </td>
                        <td class="text-right">₹{{ number_format($sale['amount'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="7" class="text-right">Total:</td>
                    <td class="text-right">₹{{ number_format($totalRevenue, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="text-align: center; padding: 40px; color: #666;">
            No sales data available for the selected date range.
        </p>
    @endif

    <div class="footer">
        <p>This report was automatically generated by the Restaurant Management System</p>
    </div>
</body>
</html>
