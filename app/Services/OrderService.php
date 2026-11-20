<?php

namespace App\Services;

use App\Domain\Discounts\Discount;
use App\Domain\Inventory\StockManager;
use App\Domain\Payments\PaymentProcessorInterface;
use App\Models\AuditLog;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private StockManager $stockManager
    ) {}

    public function calculateSubtotal(array $items): float
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $menuItem = MenuItem::findOrFail($item['menu_item_id']);
            $subtotal += $menuItem->price * $item['quantity'];
        }

        return round($subtotal, 2);
    }

    public function placeOrder(
        int $userId,
        array $items,
        PaymentProcessorInterface $paymentProcessor,
        float $amountPaid,
        ?Discount $discount = null,
        ?string $referenceNumber = null,
        string $orderType = 'dine-in',
        ?string $tableNumber = null
    ): Order {
        return DB::transaction(function () use (
            $userId,
            $items,
            $paymentProcessor,
            $amountPaid,
            $discount,
            $referenceNumber,
            $orderType,
            $tableNumber
        ) {
            // 1. Calculate totals using Polymorphism (Discount)
            $subtotal = $this->calculateSubtotal($items);
            $discountAmount = $discount ? $discount->calculate($subtotal) : 0.00;
            $totalAmount = max(0, round($subtotal - $discountAmount, 2));

            // 2. Process payment using Polymorphism (PaymentProcessorInterface)
            $paymentResult = $paymentProcessor->process($totalAmount, $amountPaid, $referenceNumber);

            // 3. Create the Order record
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'order_type' => $orderType,
                'table_number' => $tableNumber,
                'status' => 'completed',
                'subtotal' => $subtotal,
                'discount_type' => $discount?->getLabel(),
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
            ]);

            // 4. Save each OrderItem and deduct stock via Encapsulation (StockManager)
            foreach ($items as $item) {
                $menuItem = MenuItem::lockForUpdate()->findOrFail($item['menu_item_id']);

                $this->stockManager->deduct($menuItem, $item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $menuItem->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $menuItem->price,
                    'subtotal' => round($menuItem->price * $item['quantity'], 2),
                ]);
            }

            // 5. Save the Payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentProcessor->getMethodName(),
                'status' => $paymentResult['status'],
                'amount_paid' => $paymentResult['amount_paid'],
                'change_amount' => $paymentResult['change_amount'],
                'reference_number' => $paymentResult['reference_number'],
            ]);

            // 6. Record in AuditLog
            AuditLog::create([
                'user_id' => $userId,
                'action' => 'order_created',
                'model_type' => Order::class,
                'model_id' => $order->id,
                'description' => "Created order {$order->order_number} totaling ₱{$totalAmount}",
            ]);

            return $order->load(['items.menuItem', 'payment']);
        });
    }
}
