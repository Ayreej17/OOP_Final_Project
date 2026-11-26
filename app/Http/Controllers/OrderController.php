<?php

namespace App\Http\Controllers;

use App\Domain\Discounts\FixedDiscount;
use App\Domain\Discounts\PercentageDiscount;
use App\Domain\Payments\CashPayment;
use App\Domain\Payments\GCashPayment;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Order;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function create()
    {
        $categories = Category::with(['menuItems' => function ($query) {
            $query->where('is_available', true)->orderBy('name');
        }])->get();

        $menuItems = MenuItem::with('category')
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        $recentOrders = Order::with(['user', 'orderItems.menuItem'])
            ->latest()
            ->take(10)
            ->get();

        return view('orders.pos', compact('categories', 'menuItems', 'recentOrders'));
    }

    public function store(Request $request, OrderService $orderService)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'order_type' => ['required', 'in:dine_in,takeout'],
            'table_number' => ['nullable', 'string', 'max:20'],
            'discount_kind' => ['nullable', 'in:none,percentage,fixed'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'discount_label' => ['nullable', 'string', 'max:50'],
            'payment_method' => ['required', 'in:cash,gcash'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'reference_number' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            // 1. Polymorphic Discount instantiation
            $discount = null;
            $discountKind = $validated['discount_kind'] ?? 'none';
            $discountValue = (float) ($validated['discount_value'] ?? 0);

            if ($discountKind === 'percentage' && $discountValue > 0) {
                $discount = new PercentageDiscount(
                    $discountValue,
                    $validated['discount_label'] ?: 'Percentage Discount'
                );
            } elseif ($discountKind === 'fixed' && $discountValue > 0) {
                $discount = new FixedDiscount(
                    $discountValue,
                    $validated['discount_label'] ?: 'Fixed Discount'
                );
            }

            // 2. Polymorphic PaymentProcessor instantiation
            $paymentProcessor = match ($validated['payment_method']) {
                'gcash' => new GCashPayment,
                default => new CashPayment,
            };

            // 3. Delegate to OrderService
            $order = $orderService->placeOrder(
                userId: Auth::id(),
                items: $validated['items'],
                paymentProcessor: $paymentProcessor,
                amountPaid: (float) $validated['amount_paid'],
                discount: $discount,
                referenceNumber: $validated['reference_number'] ?? null,
                orderType: $validated['order_type'],
                tableNumber: $validated['order_type'] === 'dine_in' ? ($validated['table_number'] ?? null) : null
            );

            return redirect()
                ->route('orders.pos')
                ->with('success', "Order {$order->order_number} placed! Grand Total: ₱".number_format($order->grand_total, 2));
        } catch (Exception $e) {
            return redirect()
                ->route('orders.pos')
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
