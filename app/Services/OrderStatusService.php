<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderStatusService
{
    private const TRANSITIONS = [
        'pending' => ['preparing', 'cancelled'],
        'preparing' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    public function changeStatus(Order $order, string $newStatus): Order
    {
        $current = $order->status;
        $allowed = self::TRANSITIONS[$current] ?? [];

        if (! in_array($newStatus, $allowed, true)) {
            throw new InvalidArgumentException(
                "Invalid order status transition: {$current} to {$newStatus}"
            );
        }

        DB::transaction(function () use ($order, $current, $newStatus) {
            $order->status = $newStatus;
            $order->save();

            app(AuditLogger::class)->log(
                'order_status_changed',
                "Order {$order->id}: {$current} -> {$newStatus}"
            );
        });

        return $order;
    }
}
