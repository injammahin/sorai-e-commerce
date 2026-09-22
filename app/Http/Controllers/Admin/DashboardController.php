<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $filters = $request->validate([
            'period' => ['nullable', 'integer', 'in:3,6,12'],
        ]);

        $period = (int) ($filters['period'] ?? 6);
        $now = now();

        $todayStart = $now->copy()->startOfDay();
        $monthStart = $now->copy()->startOfMonth();
        $previousMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $previousMonthEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();
        $threeMonthStart = $now->copy()->subMonths(2)->startOfMonth();
        $sixMonthStart = $now->copy()->subMonths(5)->startOfMonth();

        $salesAggregate = Order::query()
            ->where('payment_status', 'paid')
            ->selectRaw('COALESCE(SUM(total), 0) AS total_revenue')
            ->selectRaw('COUNT(*) AS paid_orders')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN COALESCE(paid_at, created_at) BETWEEN ? AND ? THEN total ELSE 0 END), 0) AS today_revenue',
                [$todayStart, $now]
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN COALESCE(paid_at, created_at) BETWEEN ? AND ? THEN total ELSE 0 END), 0) AS month_revenue',
                [$monthStart, $now]
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN COALESCE(paid_at, created_at) BETWEEN ? AND ? THEN total ELSE 0 END), 0) AS previous_month_revenue',
                [$previousMonthStart, $previousMonthEnd]
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN COALESCE(paid_at, created_at) BETWEEN ? AND ? THEN total ELSE 0 END), 0) AS three_month_revenue',
                [$threeMonthStart, $now]
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN COALESCE(paid_at, created_at) BETWEEN ? AND ? THEN total ELSE 0 END), 0) AS six_month_revenue',
                [$sixMonthStart, $now]
            )
            ->first();

        $totalPaidRevenue = (float) $salesAggregate->total_revenue;
        $paidOrderCount = (int) $salesAggregate->paid_orders;
        $todayRevenue = (float) $salesAggregate->today_revenue;
        $monthRevenue = (float) $salesAggregate->month_revenue;
        $previousMonthRevenue = (float) $salesAggregate->previous_month_revenue;
        $threeMonthRevenue = (float) $salesAggregate->three_month_revenue;
        $sixMonthRevenue = (float) $salesAggregate->six_month_revenue;

        $orderAggregate = Order::query()
            ->selectRaw('COUNT(*) AS total_orders')
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN status NOT IN ('cancelled', 'refunded') THEN total ELSE 0 END), 0) AS gross_order_value"
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS today_orders',
                [$todayStart, $now]
            )
            ->selectRaw(
                "COALESCE(SUM(CASE WHEN status IN ('pending', 'confirmed', 'processing') THEN 1 ELSE 0 END), 0) AS orders_to_fulfil"
            )
            ->first();

        $customerAggregate = User::query()
            ->where('role', 'customer')
            ->selectRaw('COUNT(*) AS customers')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS new_customers',
                [$monthStart, $now]
            )
            ->first();

        $monthGrowth = $previousMonthRevenue > 0
            ? round(
                (($monthRevenue - $previousMonthRevenue)
                    / $previousMonthRevenue) * 100,
                1
            )
            : ($monthRevenue > 0 ? null : 0.0);

        $stats = [
            'total_revenue' => $totalPaidRevenue,
            'gross_order_value' => (float) $orderAggregate->gross_order_value,
            'month_revenue' => $monthRevenue,
            'three_month_revenue' => $threeMonthRevenue,
            'six_month_revenue' => $sixMonthRevenue,
            'average_order_value' => $paidOrderCount > 0
                ? $totalPaidRevenue / $paidOrderCount
                : 0,
            'orders' => (int) $orderAggregate->total_orders,
            'paid_orders' => $paidOrderCount,
            'month_growth' => $monthGrowth,
        ];

        $operations = [
            'today_revenue' => $todayRevenue,
            'today_orders' => (int) $orderAggregate->today_orders,
            'orders_to_fulfil' => (int) $orderAggregate->orders_to_fulfil,
            'new_customers' => (int) $customerAggregate->new_customers,
            'customers' => (int) $customerAggregate->customers,
            'new_messages' => ContactMessage::query()
                ->where('status', 'new')
                ->count(),
            'pending_reviews' => Review::query()
                ->where('status', 'pending')
                ->count(),
        ];

        $inventoryRow = Product::query()
            ->where('is_active', true)
            ->selectRaw('COUNT(*) AS total_products')
            ->selectRaw('COALESCE(SUM(stock), 0) AS stock_units')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END), 0) AS out_of_stock'
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN stock > 0 AND stock <= low_stock_threshold THEN 1 ELSE 0 END), 0) AS low_stock'
            )
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN stock > low_stock_threshold THEN 1 ELSE 0 END), 0) AS healthy_stock'
            )
            ->selectRaw(
                'COALESCE(SUM(stock * price), 0) AS retail_stock_value'
            )
            ->first();

        $inventory = [
            'total_products' => (int) $inventoryRow->total_products,
            'stock_units' => (int) $inventoryRow->stock_units,
            'out_of_stock' => (int) $inventoryRow->out_of_stock,
            'low_stock' => (int) $inventoryRow->low_stock,
            'healthy_stock' => (int) $inventoryRow->healthy_stock,
            'retail_stock_value' => (float) $inventoryRow->retail_stock_value,
        ];

        $statuses = [
            'pending',
            'confirmed',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
            'refunded',
        ];

        $rawStatusCounts = Order::query()
            ->select('status', DB::raw('COUNT(*) AS total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $orderStatuses = collect($statuses)
            ->mapWithKeys(
                fn (string $status) => [
                    $status => (int) ($rawStatusCounts[$status] ?? 0),
                ]
            );

        $chartStart = $now
            ->copy()
            ->subMonths($period - 1)
            ->startOfMonth();

        $monthlyRows = Order::query()
            ->where('payment_status', 'paid')
            ->where(function ($query) use ($chartStart, $now) {
                $query
                    ->whereBetween('paid_at', [$chartStart, $now])
                    ->orWhere(function ($query) use ($chartStart, $now) {
                        $query
                            ->whereNull('paid_at')
                            ->whereBetween('created_at', [$chartStart, $now]);
                    });
            })
            ->selectRaw(
                "DATE_FORMAT(COALESCE(paid_at, created_at), '%Y-%m') AS sales_month"
            )
            ->selectRaw('COALESCE(SUM(total), 0) AS revenue')
            ->selectRaw('COUNT(*) AS orders_count')
            ->groupBy('sales_month')
            ->orderBy('sales_month')
            ->get()
            ->keyBy('sales_month');

        $salesChart = collect(range($period - 1, 0))
            ->map(function (int $monthsAgo) use ($now, $monthlyRows) {
                $month = $now
                    ->copy()
                    ->subMonths($monthsAgo)
                    ->startOfMonth();

                $key = $month->format('Y-m');
                $row = $monthlyRows->get($key);

                return [
                    'key' => $key,
                    'label' => $month->format('M'),
                    'full_label' => $month->format('F Y'),
                    'revenue' => (float) ($row->revenue ?? 0),
                    'orders' => (int) ($row->orders_count ?? 0),
                ];
            });

        $bestMonth = $salesChart
            ->sortByDesc('revenue')
            ->first();

        $chartSummary = [
            'revenue' => (float) $salesChart->sum('revenue'),
            'orders' => (int) $salesChart->sum('orders'),
            'monthly_average' => (float) $salesChart->avg('revenue'),
            'best_month' => ($bestMonth['revenue'] ?? 0) > 0
                ? $bestMonth
                : null,
        ];

        $recentOrders = Order::query()
            ->with('user:id,name,email')
            ->withCount('items')
            ->latest()
            ->limit(8)
            ->get();

        $lowStockProducts = Product::query()
            ->where('is_active', true)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->with('primaryImage')
            ->orderByRaw('CASE WHEN stock = 0 THEN 0 ELSE 1 END')
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(6)
            ->get();

        $topProducts = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->select('order_items.product_id')
            ->selectRaw('MAX(order_items.product_name) AS product_name')
            ->selectRaw('MAX(order_items.sku) AS sku')
            ->selectRaw('MAX(order_items.image) AS image')
            ->selectRaw('SUM(order_items.quantity) AS units_sold')
            ->selectRaw('SUM(order_items.total) AS revenue')
            ->groupBy('order_items.product_id')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'period',
            'stats',
            'operations',
            'inventory',
            'orderStatuses',
            'salesChart',
            'chartSummary',
            'recentOrders',
            'lowStockProducts',
            'topProducts'
        ));
    }

}
