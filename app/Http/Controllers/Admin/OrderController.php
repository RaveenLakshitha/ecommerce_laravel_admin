<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\Setting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        return view('admin.orders.index');
    }

    /**
     * Display the comprehensive Order Manager / Kanban board screen.
     */
    public function manager(Request $request)
    {
        $orders = Order::with(['items.variant.product', 'customer', 'shippingAddress'])
            ->orderBy('placed_at', 'desc')
            ->take(100)
            ->get();

        return view('admin.orders.manager', compact('orders'));
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'desc');
        $search = trim($request->input('search.value', ''));

        $statusFilter = $request->input('status');
        $paymentStatusFilter = $request->input('payment_status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = Order::query();

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($paymentStatusFilter) {
            $query->where('payment_status', $paymentStatusFilter);
        }

        if ($dateFrom) {
            $query->whereDate('placed_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('placed_at', '<=', $dateTo);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $totalRecords = Order::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            0 => 'order_number',
            1 => 'placed_at',
            2 => 'customer_name',
            5 => 'total_amount',
            default => 'created_at',
        };

        $query->orderBy($sortColumn, $orderDir);
        $orders = $query->offset($start)->limit($length)->get();

        $data = $orders->map(function ($order) {
            $statusColors = [
                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                'shipped' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                'delivered' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                'returned' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
            ];
            $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
            $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $colorClass . '">' . __('file.' . $order->status) . '</span>';
            
            $payColors = [
                'pending' => 'text-yellow-600 dark:text-yellow-400',
                'paid' => 'text-green-600 dark:text-green-400',
                'failed' => 'text-red-600 dark:text-red-400',
                'refunded' => 'text-gray-500',
                'partially_refunded' => 'text-orange-500'
            ];
            $pClass = $payColors[$order->payment_status] ?? 'text-gray-500';
            $payStatus = __('file.' . $order->payment_status);

            $paymentHtml = '
                <div class="font-medium ' . $pClass . '">' . $payStatus . '</div>
                <div class="text-xs text-gray-500 uppercase">' . $order->payment_method . '</div>
            ';

            $customerHtml = '
                <div class="text-sm font-medium text-gray-900 dark:text-white">' . $order->customer_name . '</div>
                <div class="text-xs text-gray-500">' . $order->customer_email . '</div>
            ';

            $orderNumberHtml = '<a href="' . route('orders.show', $order->id) . '" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 hover:underline">' . $order->order_number . '</a>';

            $dateHtml = $order->placed_at ? $order->placed_at->format('M d, Y h:i A') : $order->created_at->format('M d, Y h:i A');

            return [
                'id' => $order->id,
                'order_number_html' => $orderNumberHtml,
                'date_html' => $dateHtml,
                'customer_html' => $customerHtml,
                'status_html' => $statusHtml,
                'payment_html' => $paymentHtml,
                'total_amount_html' => '<div class="font-medium text-gray-900 dark:text-white">' . Setting::formatPrice($order->total_amount) . '</div>',
                'show_url' => route('orders.show', $order->id),
                'invoice_url' => route('orders.invoice', $order->id),
                'delete_url' => route('orders.destroy', $order->id),
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['items', 'customer', 'shippingAddress', 'billingAddress', 'refunds', 'transactions']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,returned',
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', __('file.order_status_updated_successfully'));
    }

    /**
     * Generate / Print the invoice or packing slip.
     */
    public function printInvoice(Order $order)
    {
        $order->load(['items', 'customer', 'shippingAddress', 'billingAddress']);

        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Process a refund for the order.
     */
    public function processRefund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . ($order->total_amount - $order->refunded_amount),
            'reason' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Create the Refund record
        OrderRefund::create([
            'order_id' => $order->id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'status' => 'processed',
            'refunded_at' => now(),
            'notes' => $request->notes,
            'performed_by' => auth()->id() ?? null,
        ]);

        // Update Order's payment_status based on total refunded amount
        $totalRefunded = $order->refunds()->sum('amount') + $request->amount;
        if ($totalRefunded >= $order->total_amount) {
            $order->update(['payment_status' => 'refunded']);
        } else {
            $order->update(['payment_status' => 'partially_refunded']);
        }

        return redirect()->back()->with('success', __('file.refund_processed_successfully'));
    }

    /**
     * Update internal notes for the order.
     */
    public function addNote(Request $request, Order $order)
    {
        $request->validate([
            'internal_notes' => 'required|string',
        ]);

        $order->update([
            'internal_notes' => $request->internal_notes
        ]);

        return redirect()->back()->with('success', __('file.internal_notes_updated_successfully'));
    }

    /**
     * Remove the specified order softly.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.order_deleted_successfully') ?? 'Order deleted successfully.']);
        }

        return back()->with('success', __('file.order_deleted_successfully') ?? 'Order deleted successfully.');
    }

    /**
     * Bulk delete selected orders softly.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids ?? '')));
        }

        if (!is_array($ids) || empty($ids)) {
            $msg = __('file.no_items_selected') ?? 'No orders selected for deletion.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        $validator = \Illuminate\Support\Facades\Validator::make(['ids' => $ids], [
            'ids'   => 'required|array',
            'ids.*' => 'exists:orders,id'
        ]);

        if ($validator->fails()) {
            $msg = __('file.validation_failed') ?? 'Validation failed for selected orders.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg, 'errors' => $validator->errors()], 422);
            }
            return back()->with('error', $msg);
        }

        Order::whereIn('id', $ids)->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('file.orders_bulk_deleted_successfully') ?? 'Selected orders deleted successfully.'
            ]);
        }

        return back()->with('success', __('file.orders_bulk_deleted_successfully') ?? 'Selected orders deleted successfully.');
    }
}
