<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Generate and display the Sales Summary Report.
     */
    public function salesSummary(Request $request): View
    {
        // 1. Date Range Handling (Default: Current Month)
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        // 2. Base Query for Completed Orders in Date Range
        $query = Order::query()
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // 3. Analytical Aggregations
        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->sum('grand_total');
        $averageOrderValue = $totalOrders > 0 ? ($totalRevenue / $totalOrders) : 0;

        // Breakdown by Order Type (Dine-in vs Takeout)
        $orderTypeBreakdown = (clone $query)
            ->select('order_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(grand_total) as revenue'))
            ->groupBy('order_type')
            ->get();

        // Detailed Orders List
        $orders = (clone $query)
            ->with(['user', 'orderItems.menuItem'])
            ->latest()
            ->paginate(15)
            ->appends($request->query());

        // 4. Log the reporting access
        AuditLogger::log(
            'REPORT_ACCESSED',
            "Accessed Sales Summary Report for range {$startDate->toDateString()} to {$endDate->toDateString()}."
        );

        return view('reports.sales', [
            'orders'             => $orders,
            'totalRevenue'       => $totalRevenue,
            'totalOrders'        => $totalOrders,
            'averageOrderValue'  => $averageOrderValue,
            'orderTypeBreakdown' => $orderTypeBreakdown,
            'startDate'          => $startDate->toDateString(),
            'endDate'            => $endDate->toDateString(),
            'isPrint'            => $request->boolean('print'),
        ]);
    }

    /**
     * Generate and display the Top-Selling Menu Items Report.
     */
    public function topSelling(Request $request): View
    {
        // 1. Date Range Handling (Default: Last 30 Days)
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $limit = (int) $request->input('limit', 10);

        // 2. Analytical Aggregation: Join order_items and orders to find highest volume items
        $topItems = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select(
                'menu_items.id as menu_item_id',
                'menu_items.name as item_name',
                'order_items.unit_price',
                DB::raw('SUM(order_items.quantity) as total_quantity_sold'),
                DB::raw('SUM(order_items.subtotal) as total_sales_amount')
            )
            ->groupBy('menu_items.id', 'menu_items.name', 'order_items.unit_price')
            ->orderByDesc('total_quantity_sold')
            ->limit($limit)
            ->get();

        // 3. Log the reporting access
        AuditLogger::log(
            'REPORT_ACCESSED',
            "Accessed Top Selling Items Report (Limit: {$limit}) for range {$startDate->toDateString()} to {$endDate->toDateString()}."
        );

        return view('reports.top-selling', [
            'topItems'  => $topItems,
            'startDate' => $startDate->toDateString(),
            'endDate'   => $endDate->toDateString(),
            'limit'     => $limit,
            'isPrint'   => $request->boolean('print'),
        ]);
    }
}