<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PickupLocation;
use Illuminate\Http\Request;

class PickupLocationController extends Controller
{
    public function index()
    {
        return view('admin.shipping.pickups.index');
    }

    public function create(Request $request)
    {
        if ($request->ajax()) {
            return view('admin.shipping.pickups.partials.form', ['location' => null])->render();
        }
        return view('admin.shipping.pickups.create');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = PickupLocation::query();

        if ($searchValue !== '') {
            $query->where('name', 'like', "%{$searchValue}%")
                ->orWhere('city', 'like', "%{$searchValue}%")
                ->orWhere('state', 'like', "%{$searchValue}%");
        }

        $totalRecords = PickupLocation::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'name',
            2 => 'city',
            3 => 'is_active',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', $orderDir);
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('created_at', 'desc');
        }

        $locations = $query->offset($start)->limit($length)->get();

        $data = $locations->map(function ($loc) {
            $statusHtml = $loc->is_active
                ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">' . __('file.active') . '</span>'
                : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">' . __('file.inactive') . '</span>';

            $addressHtml = $loc->address_line_1 . ($loc->address_line_2 ? ', ' . $loc->address_line_2 : '') . '<br><span class="text-xs text-gray-500">' . $loc->city . ', ' . $loc->state . ' ' . $loc->postal_code . '</span>';

            $contactHtml = '<div class="text-xs">' . ($loc->phone ? '&#9742; ' . $loc->phone . '<br>' : '') . ($loc->email ? '&#9993; ' . $loc->email : '') . '</div>';

            return [
                'id' => $loc->id,
                'name' => trim($loc->name),
                'address_html' => $addressHtml,
                'contact_html' => $contactHtml,
                'status_html' => $statusHtml,
                'raw_data' => $loc,
                'delete_url' => route('shipping.pickups.destroy', $loc->id)
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
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:2',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $data['is_active'] = $request->has('is_active');
        PickupLocation::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_created_successfully')]);
        }

        return back()->with('success', __('file.item_created_successfully'));
    }

    public function edit(Request $request, PickupLocation $pickup)
    {
        if ($request->ajax()) {
            return view('admin.shipping.pickups.partials.form', ['location' => $pickup])->render();
        }
        return view('admin.shipping.pickups.edit', compact('pickup'));
    }

    public function update(Request $request, PickupLocation $pickup)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:2',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $data['is_active'] = $request->has('is_active');
        $pickup->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => __('file.item_updated_successfully')]);
        }

        return back()->with('success', __('file.item_updated_successfully'));
    }

    public function destroy(PickupLocation $pickup)
    {
        $pickup->delete();
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
            return response()->json(['success' => false, 'message' => __('file.no_items_selected')], 400);
        }

        PickupLocation::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => __('file.selected_items_deleted_successfully')
        ]);
    }
}
