@extends('layouts.valex')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-2 border-b border-slate-200/80">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">POS Order Terminal</h1>
            <p class="text-xs text-slate-500 mt-1">Select menu items, apply discounts, and process customer payments.</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center space-x-2">
            <a href="{{ route('orders.status-board') }}" class="px-3.5 py-2 text-xs font-semibold rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 shadow-sm transition flex items-center">
                <i class="bi bi-kanban mr-1.5 text-[#6c5ffc]"></i> Kitchen Status Board
            </a>
        </div>
    </div>

    <!-- Main POS Grid: Menu (Left 7 cols) + Cart & Checkout (Right 5 cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- LEFT: MENU CATALOG -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Category Filter Pills -->
            <div class="valex-card p-3 flex flex-wrap gap-2">
                <button type="button" onclick="filterCategory('all')" data-cat-btn="all"
                        class="cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#6c5ffc] text-white transition">
                    All Items ({{ $menuItems->count() }})
                </button>
                @foreach($categories as $category)
                    <button type="button" onclick="filterCategory('{{ $category->id }}')" data-cat-btn="{{ $category->id }}"
                            class="cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                        {{ $category->name }} ({{ $category->menuItems->count() }})
                    </button>
                @endforeach
            </div>

            <!-- Menu Items Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="menu-grid">
                @forelse($menuItems as $item)
                    @php $outOfStock = $item->stock <= 0; @endphp
                    <div data-category="{{ $item->category_id }}"
                         class="menu-card valex-card p-4 flex flex-col justify-between transition {{ $outOfStock ? 'opacity-60 bg-slate-50' : 'hover:border-[#6c5ffc] hover:shadow-md' }}">
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <span class="text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded bg-purple-50 text-[#6c5ffc]">
                                    {{ $item->category->name ?? 'Menu' }}
                                </span>
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $item->stock > 5 ? 'bg-emerald-50 text-emerald-700' : ($item->stock > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                    {{ $outOfStock ? 'Out of Stock' : "Stock: {$item->stock}" }}
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-800 text-sm mt-2">{{ $item->name }}</h3>
                            @if($item->description)
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $item->description }}</p>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-base font-bold text-slate-800">₱{{ number_format($item->price, 2) }}</span>
                            <button type="button"
                                    {{ $outOfStock ? 'disabled' : '' }}
                                    onclick="addToCart({{ $item->id }}, @js($item->name), {{ (float) $item->price }}, {{ (int) $item->stock }})"
                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center transition {{ $outOfStock ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-[#6c5ffc] hover:bg-[#5646fc] text-white shadow-sm' }}">
                                <i class="bi bi-plus-lg mr-1"></i> Add
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full valex-card p-8 text-center text-sm text-slate-500">
                        No available menu items found. Please seed or add menu items first.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT: ORDER CART & CHECKOUT FORM -->
        <div class="lg:col-span-5">
            <form method="POST" action="{{ route('orders.store') }}" id="pos-form" class="valex-card p-5 space-y-4">
                @csrf

                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="font-bold text-slate-800 text-base flex items-center">
                        <i class="bi bi-cart3 text-[#6c5ffc] mr-2"></i> Current Order
                    </h2>
                    <button type="button" onclick="clearCart()" class="text-xs text-rose-500 hover:text-rose-700 font-medium">
                        Clear Cart
                    </button>
                </div>

                <!-- Order Type & Table Number -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Order Type</label>
                        <select name="order_type" id="order_type" onchange="toggleTableInput()"
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 focus:border-[#6c5ffc] focus:bg-white focus:outline-none">
                            <option value="dine_in">Dine-In</option>
                            <option value="takeout">Takeout</option>
                        </select>
                    </div>
                    <div id="table-field">
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase mb-1">Table Number</label>
                        <input type="text" name="table_number" placeholder="e.g. T-01"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:border-[#6c5ffc] focus:bg-white focus:outline-none">
                    </div>
                </div>

                <!-- Cart Items List -->
                <div id="cart-items-container" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                    <p id="empty-cart-msg" class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-lg">
                        Click "Add" on a menu item to start an order.
                    </p>
                </div>

                <!-- Hidden inputs injected dynamically for Laravel validation -->
                <div id="cart-hidden-inputs"></div>

                <!-- Discount Selection (Maps to Discount OOP Hierarchy) -->
                <div class="pt-3 border-t border-slate-100 space-y-2">
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase">Discount (OOP Strategy)</label>
                    <div class="grid grid-cols-3 gap-2">
                        <select name="discount_kind" id="discount_kind" onchange="recalculate()"
                                class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-xs font-medium text-slate-700 focus:border-[#6c5ffc] focus:outline-none">
                            <option value="none">No Discount</option>
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed (₱)</option>
                        </select>
                        <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" value="0" oninput="recalculate()"
                               placeholder="Value"
                               class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-xs text-slate-700 focus:border-[#6c5ffc] focus:outline-none">
                        <input type="text" name="discount_label" id="discount_label" placeholder="e.g. Senior/PWD"
                               class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-xs text-slate-700 focus:border-[#6c5ffc] focus:outline-none">
                    </div>
                </div>

                <!-- Totals Breakdown -->
                <div class="bg-slate-50 rounded-lg p-3.5 space-y-1.5 text-xs border border-slate-200/70">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span id="summary-subtotal" class="font-semibold">₱0.00</span>
                    </div>
                    <div class="flex justify-between text-emerald-600">
                        <span>Discount</span>
                        <span id="summary-discount" class="font-semibold">-₱0.00</span>
                    </div>
                    <div class="flex justify-between text-slate-900 font-bold text-sm pt-1.5 border-t border-slate-200">
                        <span>Grand Total</span>
                        <span id="summary-total" class="text-[#6c5ffc]">₱0.00</span>
                    </div>
                </div>

                <!-- Payment Method (Maps to PaymentProcessorInterface) -->
                <div class="space-y-2.5 pt-1">
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase">Payment Method</label>
                    <div class="grid grid-cols-2 gap-2">
                        <select name="payment_method" id="payment_method" onchange="togglePaymentFields(); recalculate();"
                                class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-medium text-slate-700 focus:border-[#6c5ffc] focus:outline-none">
                            <option value="cash">Cash Payment</option>
                            <option value="gcash">GCash E-Wallet</option>
                        </select>
                        <input type="number" step="0.01" min="0" name="amount_paid" id="amount_paid" required oninput="recalculate()"
                               placeholder="Amount Paid (₱)"
                               class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-semibold text-slate-800 focus:border-[#6c5ffc] focus:outline-none">
                    </div>

                    <div id="gcash-ref-field" class="hidden">
                        <input type="text" name="reference_number" id="reference_number" placeholder="GCash Reference Number (Required)"
                               class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:border-[#6c5ffc] focus:outline-none">
                    </div>

                    <div id="cash-change-box" class="flex justify-between items-center text-xs px-3 py-2 rounded-lg bg-purple-50 text-[#6c5ffc] font-semibold">
                        <span>Estimated Change:</span>
                        <span id="summary-change">₱0.00</span>
                    </div>
                </div>

                <button type="submit" id="submit-order-btn" disabled
                        class="w-full py-2.5 px-4 rounded-lg bg-[#6c5ffc] hover:bg-[#5646fc] disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-semibold text-sm shadow-md transition flex items-center justify-center">
                    <i class="bi bi-check2-circle mr-2 text-base"></i> Place & Process Order
                </button>
            </form>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="valex-card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 text-sm">Recent Orders</h2>
            <a href="{{ route('orders.status-board') }}" class="text-xs font-semibold text-[#6c5ffc] hover:underline">
                Manage Statuses on Kitchen Board →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-[11px] border-b border-slate-200">
                        <th class="py-3 px-4">Order #</th>
                        <th class="py-3 px-4">Type / Table</th>
                        <th class="py-3 px-4">Items</th>
                        <th class="py-3 px-4">Discount</th>
                        <th class="py-3 px-4">Grand Total</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Cashier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-4 font-bold text-slate-800">{{ $order->order_number }}</td>
                            <td class="py-3 px-4 capitalize">
                                {{ str_replace('_', ' ', $order->order_type) }}
                                @if($order->table_number)
                                    <span class="text-slate-400">({{ $order->table_number }})</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                @foreach($order->orderItems as $oi)
                                    <div>{{ $oi->quantity }}x {{ $oi->menuItem->name ?? 'Item' }}</div>
                                @endforeach
                            </td>
                            <td class="py-3 px-4 text-emerald-600">
                                {{ $order->discount > 0 ? '-₱' . number_format($order->discount, 2) : '—' }}
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800">₱{{ number_format($order->grand_total, 2) }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold capitalize
                                    {{ $order->status === 'completed' ? 'bg-emerald-50 text-emerald-700' : ($order->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">{{ $order->user->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">No orders placed yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let cart = [];

    function filterCategory(categoryId) {
        document.querySelectorAll('.cat-btn').forEach(btn => {
            const active = btn.dataset.catBtn === String(categoryId);
            btn.className = active
                ? 'cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#6c5ffc] text-white transition'
                : 'cat-btn px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition';
        });

        document.querySelectorAll('.menu-card').forEach(card => {
            card.style.display = (categoryId === 'all' || card.dataset.category === String(categoryId)) ? 'flex' : 'none';
        });
    }

    function addToCart(id, name, price, maxStock) {
        const existing = cart.find(item => item.id === id);
        if (existing) {
            if (existing.quantity >= maxStock) {
                alert(`Maximum available stock (${maxStock}) reached for ${name}.`);
                return;
            }
            existing.quantity += 1;
        } else {
            cart.push({ id, name, price, quantity: 1, maxStock });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const index = cart.findIndex(item => item.id === id);
        if (index === -1) return;

        const nextQty = cart[index].quantity + delta;
        if (nextQty <= 0) {
            cart.splice(index, 1);
        } else if (nextQty <= cart[index].maxStock) {
            cart[index].quantity = nextQty;
        }
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const hiddenInputs = document.getElementById('cart-hidden-inputs');
        const submitBtn = document.getElementById('submit-order-btn');

        hiddenInputs.innerHTML = '';

        if (cart.length === 0) {
            container.innerHTML = `<p class="text-xs text-slate-400 text-center py-6 border border-dashed border-slate-200 rounded-lg">Click "Add" on a menu item to start an order.</p>`;
            submitBtn.disabled = true;
            recalculate();
            return;
        }

        submitBtn.disabled = false;
        container.innerHTML = cart.map((item, idx) => {
            hiddenInputs.innerHTML += `
                <input type="hidden" name="items[${idx}][menu_item_id]" value="${item.id}">
                <input type="hidden" name="items[${idx}][quantity]" value="${item.quantity}">
            `;

            return `
                <div class="flex items-center justify-between bg-slate-50 border border-slate-200/80 rounded-lg px-3 py-2 text-xs">
                    <div class="pr-2">
                        <p class="font-semibold text-slate-800">${item.name}</p>
                        <p class="text-[11px] text-slate-500">₱${item.price.toFixed(2)} each</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="updateQty(${item.id}, -1)" class="w-6 h-6 rounded bg-white border border-slate-200 font-bold text-slate-600 hover:bg-slate-100">-</button>
                        <span class="font-bold text-slate-800 w-5 text-center">${item.quantity}</span>
                        <button type="button" onclick="updateQty(${item.id}, 1)" class="w-6 h-6 rounded bg-white border border-slate-200 font-bold text-slate-600 hover:bg-slate-100">+</button>
                    </div>
                </div>
            `;
        }).join('');

        recalculate();
    }

    function recalculate() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const kind = document.getElementById('discount_kind').value;
        const val = parseFloat(document.getElementById('discount_value').value) || 0;

        let discount = 0;
        if (kind === 'percentage') {
            discount = subtotal * (val / 100);
        } else if (kind === 'fixed') {
            discount = Math.min(val, subtotal);
        }

        const total = Math.max(0, subtotal - discount);
        const paid = parseFloat(document.getElementById('amount_paid').value) || 0;
        const change = Math.max(0, paid - total);

        document.getElementById('summary-subtotal').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('summary-discount').textContent = `-₱${discount.toFixed(2)}`;
        document.getElementById('summary-total').textContent = `₱${total.toFixed(2)}`;
        document.getElementById('summary-change').textContent = `₱${change.toFixed(2)}`;
    }

    function toggleTableInput() {
        const isDineIn = document.getElementById('order_type').value === 'dine_in';
        document.getElementById('table-field').style.display = isDineIn ? 'block' : 'none';
    }

    function togglePaymentFields() {
        const method = document.getElementById('payment_method').value;
        document.getElementById('gcash-ref-field').classList.toggle('hidden', method !== 'gcash');
        document.getElementById('cash-change-box').classList.toggle('hidden', method !== 'cash');
    }
</script>
@endpush
@endsection
