<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\Setting;

class RefundController extends Controller
{
    /**
     * Display a listing of refunds.
     */
    public function index()
    {
        return view('admin.refunds.index');
    }

    /**
     * Datatable JSON response.
     */
    public function datatable(Request $request)
    {
        $query = Refund::with(['order', 'transaction', 'approver']);

        return datatables()->of($query)
            ->addColumn('refund_id_html', function ($row) {
                return '<a href="' . route('refunds.show', $row->id) . '" class="text-indigo-600 hover:underline">#' . str_pad($row->id, 5, '0', STR_PAD_LEFT) . '</a>';
            })
            ->addColumn('order_number_html', function ($row) {
                if ($row->order) {
                    return '<a href="' . route('orders.show', $row->order->id) . '" class="text-indigo-600 hover:underline">#' . $row->order->order_number . '</a>';
                }
                return '<span class="text-gray-500">N/A</span>';
            })
            ->addColumn('amount_html', function ($row) {
                return Setting::formatPrice($row->amount);
            })
            ->addColumn('status_html', function ($row) {
                $colors = [
                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                ];
                $color = $colors[$row->status] ?? 'bg-gray-100 text-gray-800';
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('date_html', function ($row) {
                return $row->created_at->format('M d, Y h:i A');
            })
            ->addColumn('show_url', function ($row) {
                return route('refunds.show', $row->id);
            })
            ->addColumn('delete_url', function ($row) {
                return route('refunds.destroy', $row->id);
            })
            ->rawColumns(['refund_id_html', 'order_number_html', 'status_html'])
            ->make(true);
    }

    /**
     * Display the specified refund.
     */
    public function show(Refund $refund)
    {
        $refund->load(['order', 'transaction', 'approver']);
        return view('admin.refunds.show', compact('refund'));
    }

    public function destroy(Refund $refund)
    {
        $refund->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Refund deleted successfully.']);
        }
        return back()->with('success', 'Refund deleted successfully.');
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

        Refund::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected refunds deleted successfully.'
        ]);
    }
}
