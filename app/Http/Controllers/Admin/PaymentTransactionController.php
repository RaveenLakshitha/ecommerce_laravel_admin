<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentTransaction;
use App\Models\Order;

class PaymentTransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     */
    public function index()
    {
        return view('admin.transactions.index');
    }

    /**
     * Return datatable JSON response.
     */
    public function datatable(Request $request)
    {
        $query = PaymentTransaction::with(['order', 'customer', 'paymentMethod']);

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('gateway') && $request->gateway !== '') {
            $query->where('gateway', $request->gateway);
        }

        return datatables()->of($query)
            ->addColumn('order_number_html', function ($row) {
                if ($row->order) {
                    return '<a href="' . route('orders.show', $row->order->id) . '" class="text-indigo-600 hover:underline">#' . $row->order->order_number . '</a>';
                }
                return '<span class="text-gray-500">N/A</span>';
            })
            ->addColumn('customer_html', function ($row) {
                if ($row->customer) {
                    return $row->customer->first_name . ' ' . $row->customer->last_name;
                }
                return '<span class="text-gray-500">Guest</span>';
            })
            ->addColumn('amount_html', function ($row) {
                return $row->currency . ' ' . number_format($row->amount, 2);
            })
            ->addColumn('status_html', function ($row) {
                $colors = [
                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                    'captured' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                    'partially_refunded' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                    'refunded' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                ];
                $color = $colors[$row->status] ?? 'bg-gray-100 text-gray-800';
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . ucfirst(str_replace('_', ' ', $row->status)) . '</span>';
            })
            ->addColumn('gateway_html', function ($row) {
                $gateways = [
                    'cod' => 'Cash on Delivery',
                    'stripe' => 'Stripe',
                    'payhere' => 'PayHere',
                    'paypal' => 'PayPal',
                    'bank' => 'Bank Transfer',
                    'manual' => 'Manual'
                ];
                return $gateways[$row->gateway] ?? ucfirst($row->gateway);
            })
            ->addColumn('date_html', function ($row) {
                return $row->created_at->format('M d, Y h:i A');
            })
            ->addColumn('show_url', function ($row) {
                return route('transactions.show', $row->id);
            })
            ->addColumn('delete_url', function ($row) {
                return route('transactions.destroy', $row->id);
            })
            ->rawColumns(['order_number_html', 'status_html', 'customer_html'])
            ->make(true);
    }

    /**
     * Display the specified transaction.
     */
    public function show(PaymentTransaction $transaction)
    {
        $transaction->load(['order', 'customer', 'paymentMethod']);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Mark a pending transaction as paid (especially useful for COD or manual transfers).
     */
    public function markAsPaid(Request $request, PaymentTransaction $transaction)
    {
        if ($transaction->status !== 'pending' && $transaction->status !== 'failed') {
            return back()->with('error', 'Only pending or failed transactions can be marked as paid manually.');
        }

        $transaction->update([
            'status' => 'completed',
            'notes' => $request->input('notes', $transaction->notes . "\nMarked as paid manually by Admin."),
        ]);

        if ($transaction->order) {
            $transaction->order->update([
                'payment_status' => 'paid',
                'payment_date' => now(),
            ]);
        }

        return back()->with('success', 'Transaction has been successfully marked as paid.');
    }

    public function destroy(PaymentTransaction $transaction)
    {
        $transaction->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Transaction deleted successfully.']);
        }
        return back()->with('success', 'Transaction deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids ?? '')));
        }

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No items selected.'], 400);
        }

        PaymentTransaction::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected transactions deleted successfully.'
        ]);
    }
}
