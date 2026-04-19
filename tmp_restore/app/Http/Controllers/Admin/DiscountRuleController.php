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
                $statusHtml = '<span class="rule-status-badge status-active">Active</span>';
            } elseif (!$rule->is_active) {
                $statusHtml = '<span class="rule-status-badge status-inactive">Inactive</span>';
            } else {
                $statusHtml = '<span class="rule-status-badge status-inactive">Scheduled / Expired</span>';
            }
            if ($rule->is_flash_sale) {
                $statusHtml .= '<span class="ml-2 rule-status-badge status-flash">Flash Sale</span>';
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
                'duplicate_url' => route('discount-rules.duplicate', $rule->id),
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

    public function duplicate(DiscountRule $discountRule)
    {
        $newRule = $discountRule->replicate();
        $newRule->name = $discountRule->name . ' (Copy)';
        $newRule->is_active = false; // Always disable copies by default
        $newRule->save();

        // Duplicate relations
        if ($discountRule->applies_to === 'products') {
            $newRule->products()->sync($discountRule->products->pluck('id'));
        } elseif ($discountRule->applies_to === 'categories') {
            $newRule->categories()->sync($discountRule->categories->pluck('id'));
        } elseif ($discountRule->applies_to === 'collections') {
            $newRule->collections()->sync($discountRule->collections->pluck('id'));
        }

        return response()->json([
            'success' => true,
            'message' => 'Rule duplicated successfully! You can now edit the copy.',
            'redirect' => route('discount-rules.edit', $newRule->id)
        ]);
    }

    public function create()
    {
        $products = \App\Models\Product::all();
        $categories = \App\Models\Category::all();
        $collections = \App\Models\Collection::all();
        return view('admin.promotions.discount_rules.create', compact('products', 'categories', 'collections'));
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
            'product_ids' => 'array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'collection_ids' => 'array',
            'collection_ids.*' => 'exists:collections,id',
            'priority' => 'required|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'banners' => 'nullable|array',
            'banners.*.title' => 'nullable|string|max:255',
            'banners.*.description' => 'nullable|string',
            'banners.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banners.*.existing_image' => 'nullable|string',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_flash_sale'] = $request->has('is_flash_sale');

        // Handle Banners
        $banners = [];
        if ($request->has('banners')) {
            foreach ($request->banners as $index => $bannerData) {
                $bannerEntry = [
                    'title' => $bannerData['title'] ?? '',
                    'description' => $bannerData['description'] ?? '',
                ];

                if ($request->hasFile("banners.{$index}.image")) {
                    $path = $request->file("banners.{$index}.image")->store('banners', 'public');
                    $bannerEntry['image'] = $path;
                }
                
                if (isset($bannerEntry['image'])) {
                    $banners[] = $bannerEntry;
                }
            }
        }
        $data['banner_images'] = $banners;

        $discountRule = DiscountRule::create($data);
        $this->syncRelations($discountRule, $request);

        return redirect()->route('discount-rules.index')->with('success', 'Discount rule created successfully.');
    }

    public function edit(DiscountRule $discountRule)
    {
        $products = \App\Models\Product::all();
        $categories = \App\Models\Category::all();
        $collections = \App\Models\Collection::all();
        return view('admin.promotions.discount_rules.edit', compact('discountRule', 'products', 'categories', 'collections'));
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
            'product_ids' => 'array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id',
            'collection_ids' => 'array',
            'collection_ids.*' => 'exists:collections,id',
            'priority' => 'required|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'banners' => 'nullable|array',
            'banners.*.title' => 'nullable|string|max:255',
            'banners.*.description' => 'nullable|string',
            'banners.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banners.*.existing_image' => 'nullable|string',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['is_flash_sale'] = $request->has('is_flash_sale');

        // Handle Banners
        $newBanners = [];
        $currentBanners = $discountRule->banner_images ?? [];
        $existingPaths = [];

        if ($request->has('banners')) {
            foreach ($request->banners as $index => $bannerData) {
                $bannerEntry = [
                    'title' => $bannerData['title'] ?? '',
                    'description' => $bannerData['description'] ?? '',
                ];

                if ($request->hasFile("banners.{$index}.image")) {
                    $path = $request->file("banners.{$index}.image")->store('banners', 'public');
                    $bannerEntry['image'] = $path;
                } elseif (!empty($bannerData['existing_image'])) {
                    $bannerEntry['image'] = $bannerData['existing_image'];
                    $existingPaths[] = $bannerData['existing_image'];
                }

                if (isset($bannerEntry['image'])) {
                    $newBanners[] = $bannerEntry;
                }
            }
        }

        // Cleanup old images that are no longer used
        foreach ($currentBanners as $oldBanner) {
            if (isset($oldBanner['image']) && !in_array($oldBanner['image'], $existingPaths)) {
                if (!collect($newBanners)->contains('image', $oldBanner['image'])) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldBanner['image']);
                }
            }
        }

        $data['banner_images'] = $newBanners;

        $discountRule->update($data);
        $this->syncRelations($discountRule, $request);

        return redirect()->route('discount-rules.index')->with('success', 'Discount rule updated successfully.');
    }

    protected function syncRelations(DiscountRule $discountRule, Request $request)
    {
        if ($request->applies_to === 'products') {
            $discountRule->products()->sync($request->product_ids ?? []);
        } else {
            $discountRule->products()->detach();
        }

        if ($request->applies_to === 'categories') {
            $discountRule->categories()->sync($request->category_ids ?? []);
        } else {
            $discountRule->categories()->detach();
        }

        if ($request->applies_to === 'collections') {
            $discountRule->collections()->sync($request->collection_ids ?? []);
        } else {
            $discountRule->collections()->detach();
        }
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
