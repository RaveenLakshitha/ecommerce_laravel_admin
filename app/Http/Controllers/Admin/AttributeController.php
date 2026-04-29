<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        return view('admin.attributes.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = \App\Models\Attribute::withCount('values');
        
        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('slug', 'like', "%{$searchValue}%");
        }

        $totalRecords = \App\Models\Attribute::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            0 => 'id',
            1 => 'name',
            2 => 'type',
            3 => 'values_count',
            default => 'id',
        };

        $query->orderBy($sortColumn, $orderDir);
        $attributes = $query->offset($start)->limit($length)->get();

        $data = $attributes->map(function ($attribute) {
            $nameHtml = '<div class="text-sm font-semibold text-gray-900 dark:text-primary-a0">'.htmlspecialchars($attribute->name).'</div>';
            $nameHtml .= '<div class="text-xs text-gray-500">'.htmlspecialchars($attribute->slug).'</div>';

            $typeHtml = '<span class="px-2.5 py-1 rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 text-xs">'.htmlspecialchars($attribute->type).'</span>';

            return [
                'id' => $attribute->id,
                'name_html' => $nameHtml,
                'type_html' => $typeHtml,
                'values_count' => $attribute->values_count,
                'edit_url' => route('attributes.edit', $attribute->id),
                'delete_url' => route('attributes.destroy', $attribute->id),
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin.attributes.partials.form', ['attribute' => null])->render();
        }
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attributes,slug',
            'type' => 'required|string|in:select,color_swatch,image_swatch,radio',
            'sort_order' => 'required|integer|min:0',
        ]);

        Attribute::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.attribute_created_successfully')]);
        }

        return redirect()->route('attributes.index')->with('success', __('file.attribute_created_successfully'));
    }

    public function edit(Request $request, Attribute $attribute)
    {
        if ($request->ajax()) {
            return view('admin.attributes.partials.form', compact('attribute'))->render();
        }
        $attribute->load(['values' => function($query) {
            $query->orderBy('sort_order');
        }]);

        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attributes,slug,' . $attribute->id,
            'type' => 'required|string|in:select,color_swatch,image_swatch,radio',
            'sort_order' => 'required|integer|min:0',
        ]);

        $attribute->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.attribute_updated_successfully')]);
        }

        return redirect()->route('attributes.index')->with('success', __('file.attribute_updated_successfully'));
    }

    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.attribute_deleted_successfully')]);
        }

        return redirect()->route('attributes.index')->with('success', __('file.attribute_deleted_successfully'));
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

        \App\Models\Attribute::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => __('file.attributes_bulk_deleted_successfully')
        ]);
    }

    // --- Managing Attribute Values ---

    public function storeValue(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:attribute_values,slug,NULL,id,attribute_id,' . $attribute->id,
            'color_hex' => 'nullable|string|max:10',
            'sort_order' => 'required|integer|min:0',
        ]);

        $attribute->values()->create($validated);

        return back()->with('success', __('file.attribute_value_added_successfully'));
    }

    public function destroyValue(Attribute $attribute, AttributeValue $value)
    {
        // Ensure the value belongs to the attribute
        if ($value->attribute_id !== $attribute->id) {
            abort(404);
        }

        $value->delete();

        return back()->with('success', __('file.attribute_value_deleted_successfully'));
    }
}
