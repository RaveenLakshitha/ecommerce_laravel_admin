<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        return view('admin.shipping.zones.index');
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin.shipping.zones.partials.form', ['zone' => null])->render();
        }
        return view('admin.shipping.zones.create');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = ShippingZone::withCount('rates');

        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%")
                ->orWhere('country_code', 'like', "%{$searchValue}%")
                ->orWhere('region', 'like', "%{$searchValue}%");
        }

        $totalRecords = ShippingZone::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'country_code',
            2 => 'name',
            3 => 'is_active',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', $orderDir);
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('created_at', 'desc');
        }

        $zones = $query->offset($start)->limit($length)->get();

        $data = $zones->map(function ($zone) {
            $statusHtml = $zone->is_active
                ? '<span class="text-green-600 font-medium">Active</span>'
                : '<span class="text-red-500 font-medium">Inactive</span>';

            $countryHtml = '<div class="flex items-center space-x-4"><div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold dark:bg-indigo-900/30 dark:text-indigo-400">' . htmlspecialchars($zone->country_code) . '</div></div>';

            $zoneHtml = '<div class="text-sm font-medium text-gray-900 dark:text-primary-a0">' . htmlspecialchars($zone->name) . '</div><div class="text-xs text-gray-500 flex items-center gap-2 mt-1"><span>' . htmlspecialchars($zone->region ?: 'All Regions') . '</span><span>&bull;</span><span>' . $zone->rates_count . ' Rates configured</span></div>';

            return [
                'id' => $zone->id,
                'country_html' => $countryHtml,
                'zone_html' => $zoneHtml,
                'status_html' => $statusHtml,
                'raw_data' => $zone,
                'delete_url' => route('shipping.zones.destroy', $zone->id)
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
            'country_code' => 'required|string|max:2',
            'region' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        ShippingZone::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Shipping Zone created successfully.']);
        }

        return back()->with('success', 'Shipping Zone created successfully.');
    }

    public function edit(Request $request, ShippingZone $zone)
    {
        if ($request->ajax()) {
            return view('admin.shipping.zones.partials.form', compact('zone'))->render();
        }
        return view('admin.shipping.zones.edit', compact('zone'));
    }

    public function update(Request $request, ShippingZone $zone)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country_code' => 'required|string|max:2',
            'region' => 'nullable|string|max:255',
        ]);

        $data['is_active'] = $request->has('is_active');
        $zone->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Shipping Zone updated successfully.']);
        }

        return back()->with('success', 'Shipping Zone updated successfully.');
    }

    public function destroy(ShippingZone $zone)
    {
        $zone->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Shipping Zone deleted successfully.']);
        }
        return back()->with('success', 'Shipping Zone deleted successfully.');
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

        ShippingZone::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected zones deleted successfully.'
        ]);
    }
}
