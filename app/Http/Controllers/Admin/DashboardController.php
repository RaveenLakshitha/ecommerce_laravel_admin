<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Variant;
use App\Models\Product;
use App\Models\Refund;
use App\Models\Review;
use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // KPI: Today's Sales
        $todaysSales = Order::whereDate('created_at', $today)->sum('total_amount');

        // KPI: Orders Today
        $ordersTodayCount = Order::whereDate('created_at', $today)->count();

        // KPI: Pending Orders
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        // KPI: Low Stock Items
        $lowStockCount = Variant::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                ->orWhere('stock_quantity', '<=', 5)
                                ->count();

        // KPI: COD Pending
        $codPendingCount = Order::where('payment_method', 'cod')
                                ->where('payment_status', 'pending')
                                ->count();

        // KPI: This Month Revenue
        $thisMonthRevenue = Order::whereMonth('created_at', $thisMonth)
                                 ->whereYear('created_at', $thisYear)
                                 ->sum('total_amount');

        // Recent Orders
        $recentOrders = Order::latest()->take(10)->get();

        // Low Stock Alerts
        $lowStockAlerts = Variant::with(['product', 'product.images'])
                                 ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                 ->orWhere('stock_quantity', '<=', 5)
                                 ->take(5)
                                 ->get();

        // Pending Tasks
        $pendingReturns = Refund::where('status', 'pending')->count();
        $pendingReviews = Review::pendingModeration()->count();
        $pendingShipments = Shipment::whereNull('shipped_at')->count(); // Assuming shipped_at null means ready/pending

        // Top Selling Products
        $topSellingProducts = Product::withCount('orderItems')
                                     ->with('images')
                                     ->orderByDesc('order_items_count')
                                     ->take(5)
                                     ->get();

        return view('admin.dashboard', compact(
            'todaysSales',
            'ordersTodayCount',
            'pendingOrdersCount',
            'lowStockCount',
            'codPendingCount',
            'thisMonthRevenue',
            'recentOrders',
            'lowStockAlerts',
            'pendingReturns',
            'pendingReviews',
            'pendingShipments',
            'topSellingProducts'
        ));
    }
}
