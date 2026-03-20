<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountRule;
use Illuminate\Http\Request;

class DiscountRuleController extends Controller
{
    public function index()
    {
        return view('admin.promotions.discount_rules.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = DiscountRule::query();

        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%");
        }

        $totalRecords = DiscountRule::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            0 => 'is_active',
            1 => 'name',
            2 => 'type',
            3 => 'priority',
            4 => 'starts_at',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('priority', 'desc');
        }

        $rules = $query->offset($start)->limit($length)->get();

        $data = $rules->map(function ($rule) {
            $statusHtml = '';
            if ($rule->is_active && (!$rule->starts_at || now()->gte($rule->starts_at)) && (!$rule->expires_at || now()->lte($rule->expires_at))) {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Active</span>';
            } elseif (!$rule->is_active) {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a30 dark:text-gray-300">Inactive</span>';
            } else {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Scheduled / Expired</span>';
            }
            if ($rule->is_flash_sale) {
                $statusHtml .= '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">Flash Sale</span>';
            }

            $nameHtml = '<div class="text-sm font-semibold text-gray-900 dark:text-primary-a0">' . htmlspecialchars($rule->name) . '</div>';
            $nameHtml .= '<div class="text-xs text-gray-500">' . htmlspecialchars(ucfirst($rule->applies_to)) . '</div>';

            $typeHtml = '<div class="text-sm text-gray-900 dark:text-primary-a0">';
            if ($rule->type === 'percentage') {
                $typeHtml .= rtrim(rtrim((string) $rule->value, '0'), '.') . '% Off';
            } elseif ($rule->type === 'fixed') {
                $typeHtml .= '$' . number_format($rule->value, 2) . ' Off';
            } elseif ($rule->type === 'bogo') {
                $typeHtml .= 'BOGO (Buy ' . $rule->buy_quantity . ', Get ' . $rule->get_quantity . ')';
            } else {
                $typeHtml .= 'Buy ' . $rule->buy_quantity . ', Get ' . $rule->get_quantity . ' (' . htmlspecialchars($rule->type) . ')';
            }
            $typeHtml .= '</div>';

            $datesHtml = '<div class="text-sm text-gray-500">';
            if ($rule->starts_at || $rule->expires_at) {
                $startsAtStr = $rule->starts_at ? $rule->starts_at->format('M d, Y H:i') : 'Always';
                $expiresAtStr = $rule->expires_at ? $rule->expires_at->format('M d, Y H:i') : 'Never';
                $datesHtml .= '<div>' . $startsAtStr . ' - <br>' . $expiresAtStr . '</div>';
            } else {
                $datesHtml .= 'No Expiry';
            }
            $datesHtml .= '</div>';

            return [
                'id' => $rule->id,
                'status_html' => $statusHtml,
                'name_html' => $nameHtml,
                'type_html' => $typeHtml,
                'priority' => '<div class="text-sm text-gray-500">' . $rule->priority . '</div>',
                'dates_html' => $datesHtml,
                'edit_url' => route('discount-rules.edit', $rule->id),
                'delete_url' => route('discount-rules.destroy', $rule->id),
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
        return view('admin.promotions.discount_rules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,bogo,buy_x_get_y',
            'value' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'applies_to' => 'required|in:all,products,categories,collections',
            'priority' => 'required|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_flash_sale'] = $request->has('is_flash_sale');

        DiscountRule::create($data);

        return redirect()->route('admin.discount-rules.index')->with('success', 'Discount rule created successfully.');
    }

    public function edit(DiscountRule $discountRule)
    {
        return view('admin.promotions.discount_rules.edit', compact('discountRule'));
    }

    public function update(Request $request, DiscountRule $discountRule)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,bogo,buy_x_get_y',
            'value' => 'nullable|numeric|min:0',
            'buy_quantity' => 'nullable|integer|min:1',
            'get_quantity' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'applies_to' => 'required|in:all,products,categories,collections',
            'priority' => 'required|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_flash_sale'] = $request->has('is_flash_sale');

        $discountRule->update($data);

        return redirect()->route('admin.discount-rules.index')->with('success', 'Discount rule updated successfully.');
    }

    public function destroy(DiscountRule $discountRule)
    {
        $discountRule->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Discount rule deleted successfully.']);
        }

        return redirect()->route('admin.discount-rules.index')->with('success', 'Discount rule deleted successfully.');
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

        \App\Models\DiscountRule::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected discount rules deleted successfully.'
        ]);
    }
}
