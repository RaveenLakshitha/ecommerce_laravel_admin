<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Note;
use App\Models\Tag;
use App\Models\Setting;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = Customer::withCount('orders');

        if ($searchValue !== '') {
            $query->where(function ($q) use ($searchValue) {
                $q->where('first_name', 'like', "%{$searchValue}%")
                    ->orWhere('last_name', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%");
            });
        }

        $totalRecords = Customer::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            0 => 'first_name',
            1 => 'email',
            2 => 'orders_count',
            3 => 'total_spent',
            4 => 'status',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('created_at', 'desc'); // Secondary sort fallback
        }

        $customers = $query->offset($start)->limit($length)->get();

        $data = $customers->map(function ($customer) {
            $nameHtml = '<div class="text-sm font-medium text-gray-900 dark:text-primary-a0">' . htmlspecialchars($customer->first_name . ' ' . $customer->last_name) . '</div>';

            $statusHtml = '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400 uppercase">' . htmlspecialchars($customer->status) . '</span>';

            return [
                'id' => $customer->id,
                'name_html' => $nameHtml,
                'email' => htmlspecialchars($customer->email),
                'orders_count' => $customer->orders_count,
                'total_spent_html' => '<div class="text-sm font-medium text-gray-900 dark:text-primary-a0">' . Setting::formatPrice($customer->total_spent) . '</div>',
                'status_html' => $statusHtml,
                'show_url' => route('customers.show', $customer->id),
                'delete_url' => route('customers.destroy', $customer->id),
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    public function show($id)
    {
        $customer = Customer::with(['orders.items', 'notes.author', 'tags'])
            ->findOrFail($id);

        // Assuming Address exists
        $addresses = \App\Models\Address::where('user_id', $customer->user_id)->get();
        // Fallback or empty if not structured that way

        $allTags = Tag::all();

        return view('admin.customers.show', compact('customer', 'addresses', 'allTags'));
    }

    public function addNote(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);
        $customer = Customer::findOrFail($id);

        $customer->notes()->create([
            'content' => $request->content,
            'author_id' => auth('admin')->id() ?? auth()->id(),
            'is_internal' => true,
        ]);

        return back()->with('success', 'Note added successfully');
    }

    public function syncTags(Request $request, $id)
    {
        $request->validate(['tags' => 'array', 'tags.*' => 'exists:tags,id']);
        $customer = Customer::findOrFail($id);

        $customer->tags()->sync($request->tags ?? []);

        return back()->with('success', 'Customer tags updated');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Customer deleted successfully.']);
        }
        return back()->with('success', 'Customer deleted successfully.');
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

        Customer::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected customers deleted successfully.'
        ]);
    }
}
