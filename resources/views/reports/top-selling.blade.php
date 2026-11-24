@extends('layouts.valex')

@section('content')
<div class="space-y-6">

    <!-- Header Breadcrumb & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Top-Selling Menu Items</h1>
            <p class="text-xs text-slate-500 mt-1">Dishes ranked by sales volume and generated revenue.</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center space-x-2 no-print">
            <button onclick="window.print()" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition flex items-center">
                <i class="bi bi-printer mr-1.5"></i> Print Leaderboard
            </button>
        </div>
    </div>

    <!-- DATE & LIMIT FILTER FORM -->
    <div class="valex-card p-5 no-print">
        <form method="GET" action="{{ route('reports.top-selling') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
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
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Rank Limit</label>
                <select name="limit" class="w-full bg-slate-50 border border-slate-200 text-xs rounded-lg px-3 py-2 text-slate-700 focus:outline-none focus:border-[#6c5ffc]">
                    <option value="5" {{ $limit == 5 ? 'selected' : '' }}>Top 5 Items</option>
                    <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10 Items</option>
                    <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20 Items</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-[#6c5ffc] hover:bg-[#5646fc] text-white text-xs font-bold rounded-lg transition shadow-sm flex items-center justify-center">
                    <i class="bi bi-filter mr-1.5"></i> Update Rankings
                </button>
            </div>
        </form>
    </div>

    <!-- LEADERBOARD DATA TABLE -->
    <div class="valex-card p-6">
        <h2 class="text-base font-bold text-slate-800 mb-4">Dish Sales Performance Rankings</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Rank</th>
                        <th class="py-3 px-4">Menu Item</th>
                        <th class="py-3 px-4">Unit Price</th>
                        <th class="py-3 px-4">Total Qty Sold</th>
                        <th class="py-3 px-4">Gross Revenue</th>
                        <th class="py-3 px-4">Popularity Bar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $maxQty = $topItems->max('total_quantity_sold') ?: 1; @endphp
                    @forelse($topItems as $index => $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4">
                                @if($index === 0)
                                    <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-800 font-bold text-xs inline-flex items-center justify-center shadow-sm">🥇</span>
                                @elseif($index === 1)
                                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-bold text-xs inline-flex items-center justify-center shadow-sm">🥈</span>
                                @elseif($index === 2)
                                    <span class="w-6 h-6 rounded-full bg-amber-200 text-amber-900 font-bold text-xs inline-flex items-center justify-center shadow-sm">🥉</span>
                                @else
                                    <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 font-semibold text-xs inline-flex items-center justify-center">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $item->item_name }}</td>
                            <td class="py-3 px-4 text-xs text-slate-500">₱{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-4 font-bold text-emerald-600">{{ $item->total_quantity_sold }} orders</td>
                            <td class="py-3 px-4 font-bold text-[#6c5ffc]">₱{{ number_format($item->total_sales_amount, 2) }}</td>
                            <td class="py-3 px-4 w-44">
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-[#6c5ffc] h-2 rounded-full" style="width: {{ ($item->total_quantity_sold / $maxQty) * 100 }}%"></div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                No sales recorded for the selected period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection