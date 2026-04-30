<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use App\Models\ShippingZone;
use App\Models\Courier;
use App\Models\Setting;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::all();
        $couriers = Courier::all();
        return view('admin.shipping.rates.index', compact('zones', 'couriers'));
    }

    public function create(Request $request)
    {
        $zones = ShippingZone::all();
        $couriers = Courier::all();
        if ($request->ajax()) {
            return view('admin.shipping.rates.partials.form', [
                'rate' => null,
                'zones' => $zones,
                'couriers' => $couriers
            ])->render();
        }
        return view('admin.shipping.rates.create', compact('zones', 'couriers'));
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = ShippingRate::with(['zone', 'courier']);

        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%")
                ->orWhereHas('zone', function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%");
                });
        }

        $totalRecords = ShippingRate::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'name',
            2 => 'shipping_zone_id',
            3 => 'rate_amount',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', $orderDir);
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('created_at', 'desc');
        }

        $rates = $query->offset($start)->limit($length)->get();

        $data = $rates->map(function ($rate) {
            $rateHtml = '<div class="text-sm font-medium text-gray-900 dark:text-primary-a0">' . htmlspecialchars($rate->name) . '</div>';
            $rateHtml .= '<div class="text-xs text-gray-500 dark:text-gray-400">' . ($rate->courier ? __('file.via') . ' ' . htmlspecialchars($rate->courier->name) : __('file.any_courier')) . (!$rate->is_active ? ' (' . __('file.inactive') . ')' : '') . '</div>';

            $zoneHtml = '<div class="text-sm text-gray-500 dark:text-gray-400">' . ($rate->zone ? htmlspecialchars($rate->zone->name) : '') . '</div>';

            $amountHtml = '<div class="text-sm font-bold text-gray-900 dark:text-primary-a0">' . Setting::formatPrice($rate->rate_amount) . '</div>';

            $conditionsHtml = '<div class="text-xs text-gray-500 space-y-1">';
            if ($rate->min_weight || $rate->max_weight)
                $conditionsHtml .= '<div>' . __('file.weight') . ': ' . ($rate->min_weight ?: 0) . 'kg - ' . ($rate->max_weight ?: '∞') . 'kg</div>';
            if ($rate->min_price || $rate->max_price)
                $conditionsHtml .= '<div>' . __('file.price') . ': ' . Setting::formatPrice($rate->min_price ?: 0) . ' - ' . Setting::formatPrice($rate->max_price ?: 0) . '</div>';
            if ($rate->free_shipping_threshold)
                $conditionsHtml .= '<div class="text-green-600 font-medium tracking-tight">' . __('file.free_over') . ' ' . Setting::formatPrice($rate->free_shipping_threshold) . '</div>';
            if (!$rate->min_weight && !$rate->max_weight && !$rate->min_price && !$rate->max_price && !$rate->free_shipping_threshold)
                $conditionsHtml .= '<div><em>' . __('file.no_conditions_flat_rate') . '</em></div>';
            $conditionsHtml .= '</div>';

            return [
                'id' => $rate->id,
                'rate_html' => $rateHtml,
                'zone_html' => $zoneHtml,
                'amount_html' => $amountHtml,
                'conditions_html' => $conditionsHtml,
                'raw_data' => $rate,
                'delete_url' => route('shipping.rates.destroy', $rate->id)
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
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'courier_id' => 'nullable|exists:couriers,id',
            'name' => 'required|string|max:255',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'rate_amount' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
        ]);

        $data['is_active'] = $request->has('is_active');
        ShippingRate::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_created_successfully')]);
        }

        return back()->with('success', __('file.item_created_successfully'));
    }

    public function edit(Request $request, ShippingRate $rate)
    {
        $zones = ShippingZone::all();
        $couriers = Courier::all();
        if ($request->ajax()) {
            return view('admin.shipping.rates.partials.form', [
                'rate' => $rate,
                'zones' => $zones,
                'couriers' => $couriers
            ])->render();
        }
        return view('admin.shipping.rates.edit', compact('rate', 'zones', 'couriers'));
    }

    public function update(Request $request, ShippingRate $rate)
    {
        $data = $request->validate([
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'courier_id' => 'nullable|exists:couriers,id',
            'name' => 'required|string|max:255',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'rate_amount' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
        ]);

        $data['is_active'] = $request->has('is_active');
        $rate->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_updated_successfully')]);
        }

        return back()->with('success', __('file.item_updated_successfully'));
    }

    public function destroy(ShippingRate $rate)
    {
        $rate->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_deleted_successfully')]);
        }
        return back()->with('success', __('file.item_deleted_successfully'));
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

        ShippingRate::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => __('file.selected_items_deleted_successfully')
        ]);
    }
}
