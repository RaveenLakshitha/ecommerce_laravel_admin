<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\Variant;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Financial Report — revenue, transactions, refunds, top revenue products.
     */
    public function financial(Request $request)
    {
        $range   = $request->input('range', '30');
        $from    = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->subDays((int) $range)->startOfDay();
        $to      = $request->filled('to')   ? Carbon::parse($request->input('to'))->endOfDay()   : now()->endOfDay();

        // ── KPI Totals ───────────────────────────────────────────────────────
        $totalRevenue   = Order::whereBetween('created_at', [$from, $to])
                               ->whereNotIn('status', ['cancelled'])
                               ->sum('total_amount');

        $totalOrders    = Order::whereBetween('created_at', [$from, $to])->count();
        $totalRefunds   = Refund::whereBetween('created_at', [$from, $to])->where('status', 'approved')->sum('amount');
        $netRevenue     = $totalRevenue - $totalRefunds;

        $avgOrderValue  = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // ── Revenue by Day (chart data) ──────────────────────────────────────
        $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders')
                              ->whereBetween('created_at', [$from, $to])
                              ->whereNotIn('status', ['cancelled'])
                              ->groupBy('date')
                              ->orderBy('date')
                              ->get();

        // ── Revenue by Payment Method ────────────────────────────────────────
        $revenueByMethod = Order::selectRaw('payment_method, SUM(total_amount) as revenue, COUNT(*) as count')
                                 ->whereBetween('created_at', [$from, $to])
                                 ->whereNotIn('status', ['cancelled'])
                                 ->groupBy('payment_method')
                                 ->get();

        // ── Orders by Status ────────────────────────────────────────────────
        $ordersByStatus = Order::selectRaw('status, COUNT(*) as count')
                               ->whereBetween('created_at', [$from, $to])
                               ->groupBy('status')
                               ->get();

        // ── Recent Transactions ──────────────────────────────────────────────
        $recentTransactions = PaymentTransaction::with('order')
                                               ->whereBetween('created_at', [$from, $to])
                                               ->orderByDesc('created_at')
                                               ->limit(10)
                                               ->get();

        // ── Recent Refunds ───────────────────────────────────────────────────
        $recentRefunds = Refund::with('order')
                               ->whereBetween('created_at', [$from, $to])
                               ->orderByDesc('created_at')
                               ->limit(10)
                               ->get();

        return view('admin.reports.financial', compact(
            'totalRevenue', 'totalOrders', 'totalRefunds', 'netRevenue',
            'avgOrderValue', 'dailyRevenue', 'revenueByMethod', 'ordersByStatus',
            'recentTransactions', 'recentRefunds', 'from', 'to', 'range'
        ));
    }

    /**
     * Inventory Report — stock levels, low-stock, valuation, movement.
     */
    public function inventory(Request $request)
    {
        // ── KPI Totals ───────────────────────────────────────────────────────
        $totalVariants      = Variant::has('product')->count();
        $inStockCount       = Variant::has('product')->where('stock_quantity', '>', 0)->count();
        $outOfStockCount    = Variant::has('product')->where('stock_quantity', '<=', 0)->count();
        $lowStockCount      = Variant::has('product')->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                            ->where('stock_quantity', '>', 0)
                                            ->count();

        // Inventory valuation (stock_quantity × price)
        $totalInventoryValue = Variant::has('product')->selectRaw('SUM(stock_quantity * price) as value')->value('value') ?? 0;

        // ── Low Stock Items ──────────────────────────────────────────────────
        $lowStockItems = Variant::has('product')->with('product')
                                       ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                       ->where('stock_quantity', '>', 0)
                                       ->orderBy('stock_quantity')
                                       ->limit(20)
                                       ->get();

        // ── Out of Stock ─────────────────────────────────────────────────────
        $outOfStockItems = Variant::has('product')->with('product')
                                         ->where('stock_quantity', '<=', 0)
                                         ->orderByDesc('updated_at')
                                         ->limit(20)
                                         ->get();

        // ── Top Stocked Products ─────────────────────────────────────────────
        $topStockedItems = Variant::has('product')->with('product')
                                          ->where('stock_quantity', '>', 0)
                                          ->orderByDesc('stock_quantity')
                                          ->limit(10)
                                          ->get();

        // ── Stock by Status (counts) ─────────────────────────────────────────
        $stockDistribution = [
            'in_stock'   => $inStockCount,
            'low_stock'  => $lowStockCount,
            'out_stock'  => $outOfStockCount,
        ];

        return view('admin.reports.inventory', compact(
            'totalVariants', 'inStockCount', 'outOfStockCount', 'lowStockCount',
            'totalInventoryValue', 'lowStockItems', 'outOfStockItems', 'topStockedItems', 'stockDistribution'
        ));
    }
}
