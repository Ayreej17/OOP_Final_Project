@extends('layouts.valex')

@section('content')
<div class="space-y-6">

    <!-- Header Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Executive Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Real-time overview of sales, active orders, and kitchen operations.</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center space-x-2">
            <a href="{{ route('orders.status-board') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-[#6c5ffc] hover:bg-[#5646fc] text-white shadow-sm transition flex items-center">
                <i class="bi bi-kanban mr-1.5"></i> Kitchen Board
            </a>
            <a href="{{ route('reports.sales') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 shadow-sm transition flex items-center">
                <i class="bi bi-download mr-1.5"></i> Sales Report
            </a>
        </div>
    </div>

    <!-- 4 VALEX KPI METRIC CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Total Revenue -->
        <div class="valex-card p-5 hover:border-purple-200 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">
                        ₱{{ number_format(\App\Models\Order::where('status', 'completed')->sum('grand_total'), 2) }}
                    </h3>
                    <span class="inline-flex items-center text-[11px] font-medium text-emerald-600 mt-2">
                        <i class="bi bi-arrow-up-right mr-1"></i> Completed Orders
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-[#6c5ffc] flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Orders -->
        <div class="valex-card p-5 hover:border-blue-200 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Orders</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">
                        {{ \App\Models\Order::count() }}
                    </h3>
                    <span class="inline-flex items-center text-[11px] font-medium text-blue-600 mt-2">
                        <i class="bi bi-receipt mr-1"></i> All Time Transactions
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Active Kitchen Orders -->
        <div class="valex-card p-5 hover:border-amber-200 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kitchen Queue</p>
                    <h3 class="text-2xl font-bold text-amber-600 mt-1">
                        {{ \App\Models\Order::whereIn('status', ['pending', 'preparing'])->count() }}
                    </h3>
                    <span class="inline-flex items-center text-[11px] font-medium text-amber-600 mt-2">
                        <i class="bi bi-hourglass-split mr-1"></i> In Progress / Pending
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-fire"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Audit Logs Recorded -->
        <div class="valex-card p-5 hover:border-emerald-200 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Audit Trail</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">
                        {{ \App\Models\AuditLog::count() }}
                    </h3>
                    <span class="inline-flex items-center text-[11px] font-medium text-slate-500 mt-2">
                        <i class="bi bi-shield-check mr-1 text-emerald-500"></i> Logged Events
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS & QUICK MODULE ACTIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Interactive Revenue ApexChart -->
        <div class="valex-card p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Weekly Revenue Dynamics</h2>
                    <p class="text-xs text-slate-400">Aggregated sales performance for the last 7 days</p>
                </div>
                <span class="px-2.5 py-1 text-xs rounded-full bg-purple-50 text-[#6c5ffc] font-semibold border border-purple-100">Live Analytics</span>
            </div>
            <div id="valexRevenueChart" class="h-64"></div>
        </div>

        <!-- Quick Launch Module Center -->
        <div class="valex-card p-6 flex flex-col justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-800 mb-1">Quick Actions</h2>
                <p class="text-xs text-slate-400 mb-4">Shortcut navigation for your assigned features</p>

                <div class="space-y-3">
                    <a href="{{ route('orders.status-board') }}" class="p-3 rounded-lg border border-slate-200 hover:border-purple-300 hover:bg-purple-50/30 flex items-center transition group">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-lg mr-3 group-hover:scale-105 transition">
                            <i class="bi bi-kanban"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Kitchen Status Board</p>
                            <p class="text-[11px] text-slate-500">Advance order states</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.sales') }}" class="p-3 rounded-lg border border-slate-200 hover:border-purple-300 hover:bg-purple-50/30 flex items-center transition group">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 text-[#6c5ffc] flex items-center justify-center text-lg mr-3 group-hover:scale-105 transition">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Sales Summary Report</p>
                            <p class="text-[11px] text-slate-500">Date filters & printouts</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.top-selling') }}" class="p-3 rounded-lg border border-slate-200 hover:border-purple-300 hover:bg-purple-50/30 flex items-center transition group">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-lg mr-3 group-hover:scale-105 transition">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Top-Selling Dishes</p>
                            <p class="text-[11px] text-slate-500">Item sales rankings</p>
                        </div>
                    </a>
                    
					@if(auth()->user()?->isAdmin())
                    <a href="{{ route('audit.index') }}" class="p-3 rounded-lg border border-slate-200 hover:border-purple-300 hover:bg-purple-50/30 flex items-center transition group">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mr-3 group-hover:scale-105 transition">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">System Audit Trail</p>
                            <p class="text-[11px] text-slate-500">Trace actions & timestamps</p>
                        </div>
                    </a>
      					@endif
      					              
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT ORDERS DATA TABLE -->
    <div class="valex-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-base font-bold text-slate-800">Recent Restaurant Orders</h2>
                <p class="text-xs text-slate-400">Latest active transactions recorded by staff</p>
            </div>
            <a href="{{ route('orders.status-board') }}" class="text-xs font-semibold text-[#6c5ffc] hover:underline">
                View All on Board &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Type / Table</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Grand Total</th>
                        <th class="py-3 px-4">Placed At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(\App\Models\Order::latest()->take(5)->get() as $order)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 font-semibold text-slate-800">#{{ $order->id }}</td>
                            <td class="py-3 px-4 capitalize">
                                {{ str_replace('_', ' ', $order->order_type) }}
                                @if($order->table_number)
                                    <span class="text-xs text-slate-400 font-mono ml-1">({{ $order->table_number }})</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($order->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-amber-50 text-amber-700 border border-amber-200 font-semibold">Pending</span>
                                @elseif($order->status === 'preparing')
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-semibold">Preparing</span>
                                @elseif($order->status === 'completed')
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-semibold">Completed</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs rounded-full bg-rose-50 text-rose-700 border border-rose-200 font-semibold">Cancelled</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800">₱{{ number_format($order->grand_total, 2) }}</td>
                            <td class="py-3 px-4 text-xs text-slate-400">{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                No orders recorded yet. Run the seeder to view sample data!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            chart: {
                type: 'area',
                height: 250,
                toolbar: { show: false },
                sparkline: { enabled: false }
            },
            colors: ['#6c5ffc'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2.5 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            series: [{
                name: 'Sales (₱)',
                data: [1200, 2400, 1800, 3200, 2800, 4100, 3900]
            }],
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                labels: { style: { colors: '#94a3b8', fontSize: '11px' } }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px' },
                    formatter: function (val) { return '₱' + val; }
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            }
        };

        var chart = new ApexCharts(document.querySelector("#valexRevenueChart"), options);
        chart.render();
    });
</script>
@endpush
