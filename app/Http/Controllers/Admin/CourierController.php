<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourierController extends Controller
{
    public function index()
    {
        return view('admin.shipping.couriers.index');
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin.shipping.couriers.partials.form', ['courier' => null])->render();
        }
        return view('admin.shipping.couriers.create');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $searchValue = trim($request->input('search.value', ''));

        $query = Courier::query();

        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%");
        }

        $totalRecords = Courier::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'name',
            2 => 'is_active',
            default => 'sort_order',
        };

        if ($sortColumn === 'sort_order') {
            $query->orderBy('sort_order', $orderDir);
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('sort_order', 'asc');
        }

        $couriers = $query->offset($start)->limit($length)->get();

        $data = $couriers->map(function ($courier) {
            $statusHtml = $courier->is_active
                ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">' . __('file.active') . '</span>'
                : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-surface-tonal-a20 dark:text-gray-300">' . __('file.inactive') . '</span>';

            $features = [];
            if ($courier->supports_tracking)
                $features[] = __('file.Tracking');
            if ($courier->supports_label_generation)
                $features[] = __('file.Labels');
            if ($courier->supports_cod)
                $features[] = __('file.COD Support');
            $featuresHtml = '<div class="flex gap-1 flex-wrap">' . implode('', array_map(fn($f) => '<span class="px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600 dark:bg-surface-tonal-a20 dark:text-gray-400">' . $f . '</span>', $features)) . '</div>';

            return [
                'id' => $courier->id,
                'name_html' => '<div class="flex items-center"><div class="flex-shrink-0 h-10 w-10 bg-indigo-100 text-indigo-600 rounded flex items-center justify-center font-bold dark:bg-indigo-900/40 dark:text-indigo-400">' . substr($courier->name, 0, 1) . '</div><div class="ml-4"><div class="text-sm font-medium text-gray-900 dark:text-primary-a0">' . htmlspecialchars($courier->name) . '</div>' . ($courier->default_for_cod ? '<div class="text-xs text-indigo-500">' . __('file.Default COD') . '</div>' : '') . '</div></div>',
                'status_html' => $statusHtml,
                'features_html' => $featuresHtml,
                'edit_url' => route('shipping.couriers.edit', $courier->id),
                'delete_url' => route('shipping.couriers.destroy', $courier->id)
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_url' => 'nullable|url',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
            'supports_tracking' => 'boolean',
            'supports_label_generation' => 'boolean',
            'supports_cod' => 'boolean',
            'default_for_cod' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);

        // Handle boolean checkboxes
        $data['supports_tracking'] = $request->has('supports_tracking');
        $data['supports_label_generation'] = $request->has('supports_label_generation');
        $data['supports_cod'] = $request->has('supports_cod');
        $data['default_for_cod'] = $request->has('default_for_cod');
        $data['is_active'] = $request->has('is_active');

        if ($data['default_for_cod']) {
            Courier::where('id', '>', 0)->update(['default_for_cod' => false]);
        }

        Courier::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_created_successfully')]);
        }

        return redirect()->route('shipping.couriers.index')->with('success', __('file.item_created_successfully'));
    }

    public function edit(Request $request, Courier $courier)
    {
        if ($request->ajax()) {
            return view('admin.shipping.couriers.partials.form', compact('courier'))->render();
        }
        return view('admin.shipping.couriers.edit', compact('courier'));
    }

    public function update(Request $request, Courier $courier)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_url' => 'nullable|url',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $data['supports_tracking'] = $request->has('supports_tracking');
        $data['supports_label_generation'] = $request->has('supports_label_generation');
        $data['supports_cod'] = $request->has('supports_cod');
        $data['default_for_cod'] = $request->has('default_for_cod');
        $data['is_active'] = $request->has('is_active');

        if ($data['default_for_cod']) {
            Courier::where('id', '!=', $courier->id)->update(['default_for_cod' => false]);
        }

        $courier->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_updated_successfully')]);
        }

        return redirect()->route('shipping.couriers.index')->with('success', __('file.item_updated_successfully'));
    }

    public function destroy(Courier $courier)
    {
        $courier->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_deleted_successfully')]);
        }
        return redirect()->route('shipping.couriers.index')->with('success', __('file.item_deleted_successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids ?? '')));
        }

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['success' => false, 'message' => __('file.no_items_selected')], 400);
        }

        Courier::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => __('file.selected_items_deleted_successfully')
        ]);
    }
}
