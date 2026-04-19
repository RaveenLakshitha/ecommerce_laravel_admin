<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Courier;
use App\Models\PickupLocation;
use App\Models\ShipmentTrackingEvent;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        $couriers = Courier::all();
        $pickups = PickupLocation::all();
        return view('admin.shipping.shipments.index', compact('couriers', 'pickups'));
    }

    public function datatable(Request $request)
    {
        $query = Shipment::with(['order', 'courier', 'pickupLocation'])->select('shipments.*');

        return datatables()->of($query)
            ->addColumn('order_id_html', function ($row) {
                if ($row->order) {
                    return '<a href="' . route('orders.show', $row->order->id) . '" class="text-indigo-600 hover:text-indigo-900 font-medium">#' . str_pad($row->order->id, 5, '0', STR_PAD_LEFT) . '</a>';
                }
                return 'N/A';
            })
            ->addColumn('courier_html', function ($row) {
                return $row->courier ? $row->courier->name : ($row->pickupLocation ? 'In-Store Pickup' : 'N/A');
            })
            ->addColumn('status_html', function ($row) {
                $statusColors = [
                    'pending' => 'bg-gray-100 text-gray-800',
                    'shipped' => 'bg-blue-100 text-blue-800',
                    'out_for_delivery' => 'bg-yellow-100 text-yellow-800',
                    'delivered' => 'bg-green-100 text-green-800',
                    'failed' => 'bg-red-100 text-red-800',
                    'returned' => 'bg-orange-100 text-orange-800',
                ];
                $color = $statusColors[$row->status] ?? 'bg-gray-100 text-gray-800';
                $text = ucfirst(str_replace('_', ' ', $row->status));
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' . $text . '</span>';
            })
            ->addColumn('actions_html', function ($row) {
                return '<div class="flex items-center justify-end gap-3 transition-opacity"><a href="' . route('shipping.shipments.show', $row->id) . '" class="text-teal-600 hover:text-teal-900 dark:text-teal-400 p-1.5 rounded-lg hover:bg-teal-50 dark:hover:bg-teal-900/20" title="Manage Shipment"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a><button type="button" onclick="confirmDelete(\'' . route('shipping.shipments.destroy', $row->id) . '\')" class="text-red-500 hover:text-red-700 dark:text-red-400 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></div>';
            })
            ->rawColumns(['order_id_html', 'status_html', 'actions_html'])
            ->make(true);
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['order.customer', 'courier', 'trackingEvents', 'pickupLocation']);
        $couriers = Courier::where('is_active', true)->get();
        return view('admin.shipping.shipments.show', compact('shipment', 'couriers'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'courier_id' => 'nullable|exists:couriers,id',
            'tracking_number' => 'nullable|string|max:255',
            'status' => 'required|string|in:pending,shipped,out_for_delivery,delivered,failed,returned',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $shipment->status;

        if ($data['status'] === 'shipped' && !$shipment->shipped_at) {
            $data['shipped_at'] = now();
        }
        if ($data['status'] === 'delivered' && !$shipment->delivered_at) {
            $data['delivered_at'] = now();
        }

        $shipment->update($data);

        // Auto log status change event if it changed
        if ($oldStatus !== $data['status']) {
            ShipmentTrackingEvent::create([
                'shipment_id' => $shipment->id,
                'status' => $data['status'],
                'description' => 'Status automatically updated by Admin: ' . auth()->user()->name,
            ]);
        }

        return back()->with('success', 'Shipment updated successfully.');
    }

    public function addTracking(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'status' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);

        $data['shipment_id'] = $shipment->id;
        ShipmentTrackingEvent::create($data);

        return back()->with('success', 'Tracking event logged manually.');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return response()->json(['success' => true, 'message' => 'Shipment deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:shipments,id']);
        Shipment::whereIn('id', $request->ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected shipments deleted successfully.']);
    }
}
