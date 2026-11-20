@extends('layouts.valex')

@section('content')
<div class="space-y-6">

    <!-- Header Breadcrumb & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sales Summary Report</h1>
            <p class="text-xs text-slate-500 mt-1">Financial performance analytics and printable audit summaries.</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center space-x-2 no-print">
            <button onclick="window.print()" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition flex items-center">
                <i class="bi bi-printer mr-1.5"></i> Print Official Report
            </button>
        </div>
    </div>

    <!-- DATE FILTER FORM -->
    <div class="valex-card p-5 no-print">
        <form method="GET" action="{{ route('reports.sales') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg transition shadow-sm flex items-center justify-center">
                    <i class="bi bi-funnel mr-1.5"></i> Apply Date Filter
                </button>
            </div>
        </form>
    </div>

    <!-- 3 KEY FINANCIAL SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="valex-card p-5 border-l-4 border-l-[#6c5ffc]">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Revenue Generated</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">₱{{ number_format($totalRevenue, 2) }}</h3>
            <span class="text-[11px] text-slate-500">Period: {{ $startDate }} to {{ $endDate }}</span>
        </div>

        <div class="valex-card p-5 border-l-4 border-l-blue-500">
            <p class="text-xs font-semibold text-slate-400 uppercase">Completed Transactions</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalOrders }} Orders</h3>
            <span class="text-[11px] text-slate-500">Excludes cancelled or voided orders</span>
        </div>

        <div class="valex-card p-5 border-l-4 border-l-emerald-500">
            <p class="text-xs font-semibold text-slate-400 uppercase">Average Order Value (AOV)</p>
            <h3 class="text-2xl font-bold text-slate-800 mt-1">₱{{ number_format($averageOrderValue, 2) }}</h3>
            <span class="text-[11px] text-slate-500">Revenue per completed transaction</span>
        </div>
    </div>

    <!-- DETAILED COMPLETED SALES TABLE -->
    <div class="valex-card p-6">
        <h2 class="text-base font-bold text-slate-800 mb-4">Completed Sales Breakdown</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Date & Time</th>
                        <th class="py-3 px-4">Staff / Cashier</th>
                        <th class="py-3 px-4">Order Type</th>
                        <th class="py-3 px-4">Items Count</th>
                        <th class="py-3 px-4">Subtotal</th>
                        <th class="py-3 px-4">Grand Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-semibold text-slate-800">#{{ $order->id }}</td>
                            <td class="py-3 px-4 text-xs text-slate-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td class="py-3 px-4 text-xs font-medium">{{ $order->user->name ?? 'Staff User' }}</td>
                            <td class="py-3 px-4 capitalize">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-600">
                                    {{ str_replace('_', ' ', $order->order_type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-xs">{{ $order->orderItems->sum('quantity') }} items</td>
                            <td class="py-3 px-4 text-xs text-slate-500">₱{{ number_format($order->subtotal, 2) }}</td>
                            <td class="py-3 px-4 font-bold text-[#6c5ffc]">₱{{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                No completed sales found for the selected date range.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 no-print">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection