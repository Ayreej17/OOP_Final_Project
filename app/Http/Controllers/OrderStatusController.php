<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller managing Order Lifecycle State Transitions.
 *
 * Demonstrates State Pattern safeguards and OOP encapsulation:
 * Transitions are strictly validated against allowable business state progressions:
 *   pending -> preparing -> completed
 *   pending -> cancelled
 *   preparing -> cancelled
 * Completed or already cancelled orders cannot be modified.
 */
class OrderStatusController extends Controller
{
    /**
     * Valid state transitions map.
     */
    private const ALLOWED_TRANSITIONS = [
        'pending' => ['preparing', 'cancelled'],
        'preparing' => ['completed', 'cancelled'],
        'completed' => [], // Terminal state
        'cancelled' => [], // Terminal state
    ];

    /**
     * Display the Order Status Tracking Board (Kanban view).
     */
    public function index(): View
    {
        // Fetch active orders with items and table relations, grouped by status
        $orders = Order::with(['user', 'orderItems.menuItem'])
            ->latest()
            ->get();

        $pendingOrders = $orders->where('status', 'pending');
        $preparingOrders = $orders->where('status', 'preparing');
        $completedOrders = $orders->where('status', 'completed')->take(10);
        $cancelledOrders = $orders->where('status', 'cancelled')->take(10);

        return view('orders.status-board', [
            'pendingOrders' => $pendingOrders,
            'preparingOrders' => $preparingOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders,
        ]);
    }

    /**
     * Advance or transition the order status with validation safeguards.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,preparing,completed,cancelled',
        ]);

        $currentStatus = $order->status;
        $targetStatus = $request->input('status');

        // Check if transition is valid
        if (! $this->canTransition($currentStatus, $targetStatus)) {
            return back()->with('error', "Illegal State Transition: Cannot change order #{$order->id} from '{$currentStatus}' to '{$targetStatus}'.");
        }

        // Apply state transition
        $order->status = $targetStatus;
        $order->save();

        // Centralized Audit Logging
        AuditLogger::log(
            'ORDER_STATUS_CHANGED',
            "Order #{$order->id} status changed from '{$currentStatus}' to '{$targetStatus}'."
        );

        return back()->with('success', "Order #{$order->id} status successfully updated to '{$targetStatus}'.");
    }

    /**
     * Business logic rule: determine whether a status transition is permitted.
     */
    private function canTransition(string $current, string $target): bool
    {
        if ($current === $target) {
            return false;
        }

        $allowed = self::ALLOWED_TRANSITIONS[$current] ?? [];

        return in_array($target, $allowed, true);
    }
}
