<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Sales Report</h1>
        <p class="text-gray-600">View and export sales data by date range</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <x-datetime wire:model="startDate"  label="Start Date"/>
                <x-datetime wire:model="endDate"  label="End Date"/>
                <x-button  wire:click="generateReport" label="Generate Report" class="mt-9 bg-primary-500 text-white"/>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-stat
            title="Total Revenue"
            value="{{ number_format($totalRevenue, 2) }}"
            icon="o-arrow-trending-up"
            class="bg-purple-500 text-white"
        />
        

        <div class="bg-gradient-to-bl from-pink-500 to-pink-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Orders</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalOrders }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-md p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Items Sold</p>
                    <p class="text-3xl font-bold mt-2">{{ $totalItems }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <x-icon name="o-academic-cap" class="w-8 h-8"/>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Sales Details</h2>
            <div class="flex gap-2">
                <button wire:click="exportExcel" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Excel
                </button>
                <button wire:click="exportPdf" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>

        @if(count($sales) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waiter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th> 
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sale->razorpay_paymentid }}</td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->created_at }}</td>
                               <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sale->order->tableSession->table->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sale->order->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sale->order->orderItems->count() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $sale['method'] == 'Cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $sale['method'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">₹{{ number_format($sale['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total:</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">₹{{ number_format($totalRevenue, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No sales data</h3>
                <p class="mt-1 text-sm text-gray-500">No completed orders found for the selected date range.</p>
            </div>
        @endif
    </div>
</div>
