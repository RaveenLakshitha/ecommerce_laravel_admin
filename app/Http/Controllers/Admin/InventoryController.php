<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variant;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display the inventory overview.
     */
    public function index()
    {
        return view('admin.inventory.index');
    }

    /**
     * Datatable JSON response for variants stock.
     */
    public function datatable(Request $request)
    {
        $query = Variant::with('product')->select('variants.*');

        return datatables()->of($query)
            ->addColumn('product_html', function ($row) {
                if ($row->product) {
                    $hasVariants = $row->product->variants->count() > 1;
                    $nameText = $row->product->name . ($hasVariants ? ' (' . $row->sku . ')' : '');
                    return '<a href="' . route('products.edit', $row->product->id) . '" class="text-indigo-600 hover:underline">' . $nameText . '</a>';
                }
                return '<span class="text-gray-500">Deleted Product</span>';
            })
            ->addColumn('sku_html', function ($row) {
                return $row->sku ?: '<span class="text-gray-400">N/A</span>';
            })
            ->addColumn('available_html', function ($row) {
                return $row->available_quantity;
            })
            ->addColumn('status_html', function ($row) {
                if ($row->available_quantity <= 0) {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Out of Stock</span>';
                } elseif ($row->isLowStock()) {
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Low Stock</span>';
                }
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">In Stock</span>';
            })
            ->addColumn('actions_html', function ($row) {
                $adjustBtn = '<button onclick="openAdjustModal(' . $row->id . ', \'' . htmlspecialchars($row->product?->name . ' - ' . $row->sku) . '\', ' . $row->stock_quantity . ')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 p-1.5 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20" title="Adjust"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>';
                $historyBtn = '<a href="' . route('inventory.history', $row->id) . '" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 p-1.5 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20" title="History"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>';
                $deleteBtn = '<button type="button" onclick="confirmDelete(\'' . route('inventory.destroy', $row->id) . '\')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>';
                return '<div class="flex items-center justify-end gap-3 transition-opacity">' . $adjustBtn . $historyBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['product_html', 'sku_html', 'status_html', 'actions_html'])
            ->make(true);
    }

    /**
     * Handle manual stock adjustment.
     */
    public function adjust(Request $request, Variant $variant)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $quantityChange = $request->adjustment_type === 'add' ? $request->quantity : -$request->quantity;

        // Ensure we don't drop below 0 by subtraction
        if ($quantityChange < 0 && $variant->stock_quantity + $quantityChange < 0) {
            return back()->with('error', 'Cannot subtract more stock than what is currently available.');
        }

        DB::transaction(function () use ($variant, $quantityChange, $request) {
            // Update variant stock directly
            $variant->increment('stock_quantity', $quantityChange);

            // Log the transaction
            InventoryTransaction::create([
                'variant_id' => $variant->id,
                'quantity_change' => $quantityChange,
                'type' => 'adjustment',
                'notes' => $request->notes,
                'performed_by' => auth()->id() ?? auth('admin')->id(),
            ]);
        });

        return back()->with('success', 'Stock adjusted successfully.');
    }

    /**
     * View history of stock changes for a variant.
     */
    public function history(Variant $variant)
    {
        $variant->load('product');
        $transactions = $variant->transactions()->with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.inventory.history', compact('variant', 'transactions'));
    }

    public function destroy(Variant $variant)
    {
        $variant->delete();
        return response()->json(['success' => true, 'message' => 'Variant deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:variants,id']);
        Variant::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected variants deleted successfully.']);
    }
}
