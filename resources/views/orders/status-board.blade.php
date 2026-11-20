@extends('layouts.valex')

@section('content')
<div class="space-y-6">

    <!-- Header Breadcrumb & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Kitchen Order Status Board</h1>
            <p class="text-xs text-slate-500 mt-1">Live order tracking with State Machine safeguards.</p>
        </div>
        <div class="mt-3 sm:mt-0">
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-ping"></span> Live Kitchen Queue
            </span>
        </div>
    </div>

    <!-- KANBAN BOARD COLUMNS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

        <!-- COLUMN 1: PENDING ORDERS -->
        <div class="bg-slate-100/70 p-4 rounded-xl border border-slate-200 flex flex-col h-[750px]">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wider">Pending Orders</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                    {{ $pendingOrders->count() }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1">
                @forelse($pendingOrders as $order)
                    <div class="valex-card p-4 hover:border-amber-300 transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm text-slate-800">#{{ $order->id }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600 uppercase">
                                {{ str_replace('_', ' ', $order->order_type) }}
                            </span>
                        </div>
                        @if($order->table_number)
                            <p class="text-xs text-slate-500 font-mono mb-2"><i class="bi bi-geo-alt"></i> {{ $order->table_number }}</p>
                        @endif

                        <div class="border-t border-slate-100 pt-2 mb-3">
                            <ul class="text-xs text-slate-600 space-y-1">
                                @foreach($order->orderItems as $item)
                                    <li class="flex justify-between">
                                        <span>{{ $item->quantity }}x {{ $item->menuItem->name ?? 'Dish' }}</span>
                                        <span class="font-semibold text-slate-500">₱{{ number_format($item->subtotal, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-800">₱{{ number_format($order->grand_total, 2) }}</span>
                            <div class="flex space-x-1">
                                <!-- Transition: Pending -> Preparing -->
                                <form method="POST" action="{{ route('orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold rounded bg-blue-600 hover:bg-blue-700 text-white shadow-sm transition">
                                        Cook &rarr;
                                    </button>
                                </form>
                                <!-- Transition: Pending -> Cancelled -->
                                <form method="POST" action="{{ route('orders.update-status', $order) }}" onsubmit="return confirm('Cancel Order #{{ $order->id }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="px-2 py-1 text-xs font-semibold rounded bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs">No pending orders.</div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN 2: PREPARING ORDERS -->
        <div class="bg-slate-100/70 p-4 rounded-xl border border-slate-200 flex flex-col h-[750px]">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wider">Preparing</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                    {{ $preparingOrders->count() }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1">
                @forelse($preparingOrders as $order)
                    <div class="valex-card p-4 hover:border-blue-300 transition">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm text-slate-800">#{{ $order->id }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-50 text-blue-700 uppercase">
                                In Kitchen
                            </span>
                        </div>
                        @if($order->table_number)
                            <p class="text-xs text-slate-500 font-mono mb-2"><i class="bi bi-geo-alt"></i> {{ $order->table_number }}</p>
                        @endif

                        <div class="border-t border-slate-100 pt-2 mb-3">
                            <ul class="text-xs text-slate-600 space-y-1">
                                @foreach($order->orderItems as $item)
                                    <li class="flex justify-between">
                                        <span>{{ $item->quantity }}x {{ $item->menuItem->name ?? 'Dish' }}</span>
                                        <span class="font-semibold text-slate-500">₱{{ number_format($item->subtotal, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <span class="text-xs font-bold text-slate-800">₱{{ number_format($order->grand_total, 2) }}</span>
                            <div class="flex space-x-1">
                                <!-- Transition: Preparing -> Completed -->
                                <form method="POST" action="{{ route('orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="px-2.5 py-1 text-xs font-semibold rounded bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm transition">
                                        Serve &rarr;
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('orders.update-status', $order) }}" onsubmit="return confirm('Cancel order?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="px-2 py-1 text-xs font-semibold rounded bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs">No orders in kitchen.</div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN 3: COMPLETED ORDERS -->
        <div class="bg-slate-100/70 p-4 rounded-xl border border-slate-200 flex flex-col h-[750px]">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wider">Completed</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                    {{ $completedOrders->count() }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1 opacity-90">
                @forelse($completedOrders as $order)
                    <div class="valex-card p-4 border-emerald-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-sm text-slate-800">#{{ $order->id }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-700">Served</span>
                        </div>
                        <p class="text-xs text-slate-400">{{ $order->updated_at->diffForHumans() }}</p>
                        <p class="text-xs font-bold text-emerald-700 mt-2">₱{{ number_format($order->grand_total, 2) }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs">No completed orders yet.</div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN 4: CANCELLED ORDERS -->
        <div class="bg-slate-100/70 p-4 rounded-xl border border-slate-200 flex flex-col h-[750px]">
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-200">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wider">Cancelled</h3>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                    {{ $cancelledOrders->count() }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-3 pr-1 opacity-80">
                @forelse($cancelledOrders as $order)
                    <div class="valex-card p-4 border-rose-100">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-sm text-slate-800">#{{ $order->id }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-rose-50 text-rose-700">Voided</span>
                        </div>
                        <p class="text-xs text-slate-400">{{ $order->updated_at->diffForHumans() }}</p>
                        <p class="text-xs text-slate-500 mt-1 line-through">₱{{ number_format($order->grand_total, 2) }}</p>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-400 text-xs">No cancelled orders.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection