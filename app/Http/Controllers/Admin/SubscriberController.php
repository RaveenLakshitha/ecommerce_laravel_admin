<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index()
    {
        return view('admin.subscribers.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = Subscriber::query();

        if ($searchValue !== '') {
            $query->where('email', 'like', "%{$searchValue}%")
                ->orWhere('first_name', 'like', "%{$searchValue}%")
                ->orWhere('last_name', 'like', "%{$searchValue}%");
        }

        $totalRecords = Subscriber::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'email',
            2 => 'first_name',
            3 => 'source',
            4 => 'status',
            5 => 'subscribed_at',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', $orderDir);
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('created_at', 'desc');
        }

        $subscribers = $query->offset($start)->limit($length)->get();

        $data = $subscribers->map(function ($sub) {
            $statusHtml = '';
            if ($sub->status == 'subscribed') {
                $statusHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium uppercase bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">' . __('file.subscribed') . '</span>';
            } elseif ($sub->status == 'unsubscribed') {
                $statusHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium uppercase bg-gray-100 text-gray-800 dark:bg-surface-tonal-a20 dark:text-gray-300">' . __('file.unsubscribed') . '</span>';
            } else {
                $statusHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium uppercase bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">' . __('file.' . ($sub->status ?? 'unknown')) . '</span>';
            }
 
            return [
                'id' => $sub->id,
                'email' => $sub->email,
                'name' => trim($sub->first_name . ' ' . $sub->last_name),
                'source' => ucfirst($sub->source ?? __('file.unknown')),
                'status_html' => $statusHtml,
                'subscribed_at' => optional($sub->subscribed_at)->format('M d, Y') ?? '-',
                'delete_url' => route('subscribers.destroy', $sub->id)
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    public function destroy($id)
    {
        Subscriber::findOrFail($id)->delete();
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

        Subscriber::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => __('file.selected_items_deleted_successfully')
        ]);
    }
}
