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
    public function __construct()
    {
        $this->middleware('permission:dashboard.view');
    }
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;
        $todaysSales = Order::whereDate('created_at', $today)->sum('total_amount');
        $ordersTodayCount = Order::whereDate('created_at', $today)->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $lowStockCount = Variant::whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                ->orWhere('stock_quantity', '<=', 5)
                                ->count();
        $codPendingCount = Order::where('payment_method', 'cod')
                                ->where('payment_status', 'pending')
                                ->count();
        $thisMonthRevenue = Order::whereMonth('created_at', $thisMonth)
                                 ->whereYear('created_at', $thisYear)
                                 ->sum('total_amount');
        $recentOrders = Order::latest()->take(10)->get();
        $lowStockAlerts = Variant::with(['product', 'product.images'])
                                 ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                 ->orWhere('stock_quantity', '<=', 5)
                                 ->take(5)
                                 ->get();
        $pendingReturns = Refund::where('status', 'pending')->count();
        $pendingReviews = Review::pendingModeration()->count();
        $pendingShipments = Shipment::whereNull('shipped_at')->count(); 
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
