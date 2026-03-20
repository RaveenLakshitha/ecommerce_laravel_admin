<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.promotions.coupons.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = Coupon::query();

        if ($searchValue !== '') {
            $query->where('code', 'like', "%{$searchValue}%")
                ->orWhere('description', 'like', "%{$searchValue}%");
        }

        $totalRecords = Coupon::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            0 => 'is_active',
            1 => 'code',
            2 => 'value',
            3 => 'used_count',
            4 => 'starts_at',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('created_at', 'desc'); // Secondary sort fallback
        }

        $coupons = $query->offset($start)->limit($length)->get();

        $data = $coupons->map(function ($coupon) {
            $statusHtml = '';
            if ($coupon->is_active && (!$coupon->starts_at || now()->gte($coupon->starts_at)) && (!$coupon->expires_at || now()->lte($coupon->expires_at))) {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>';
            } elseif (!$coupon->is_active) {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">Inactive</span>';
            } else {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Scheduled / Expired</span>';
            }

            $codeHtml = '<div class="text-sm font-semibold text-gray-900 dark:text-primary-a0">' . htmlspecialchars($coupon->code) . '</div>';
            $codeHtml .= '<div class="text-xs text-gray-500">' . htmlspecialchars(ucfirst(str_replace('_', ' ', $coupon->applies_to))) . '</div>';

            $discountHtml = '<div class="text-sm text-gray-900 dark:text-primary-a0">';
            $discountHtml .= $coupon->type == 'percentage' ? rtrim(rtrim((string) $coupon->value, '0'), '.') . '%' : '$' . number_format($coupon->value, 2);
            $discountHtml .= '</div>';
            if ($coupon->min_order_amount) {
                $discountHtml .= '<div class="text-xs text-gray-500">Min: $' . number_format($coupon->min_order_amount, 2) . '</div>';
            }

            $usageHtml = '<div class="text-sm text-gray-900 dark:text-primary-a0">' . $coupon->used_count . ' / ' . ($coupon->usage_limit ?? '∞') . '</div>';

            $datesHtml = '<div class="text-sm text-gray-500">';
            if ($coupon->starts_at || $coupon->expires_at) {
                $startsAtStr = $coupon->starts_at ? $coupon->starts_at->format('M d, Y') : 'Always';
                $expiresAtStr = $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never';
                $datesHtml .= $startsAtStr . ' - ' . $expiresAtStr;
            } else {
                $datesHtml .= 'No Expiry';
            }
            $datesHtml .= '</div>';

            return [
                'id' => $coupon->id,
                'status_html' => $statusHtml,
                'code_html' => $codeHtml,
                'discount_html' => $discountHtml,
                'usage_html' => $usageHtml,
                'dates_html' => $datesHtml,
                'edit_url' => route('coupons.edit', $coupon->id),
                'delete_url' => route('coupons.destroy', $coupon->id),
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    public function create()
    {
        return view('admin.promotions.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'applies_to' => 'required|in:all,specific_products,specific_categories',
        ]);

        $data['is_active'] = $request->has('is_active');

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.promotions.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'applies_to' => 'required|in:all,specific_products,specific_categories',
        ]);

        $data['is_active'] = $request->has('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Coupon deleted successfully.']);
        }

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
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

        \App\Models\Coupon::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected coupons deleted successfully.'
        ]);
    }
}
