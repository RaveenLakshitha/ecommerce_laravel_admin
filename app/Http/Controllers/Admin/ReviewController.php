<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        return view('admin.reviews.index');
    }

    public function datatable(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $orderIdx = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $searchValue = trim($request->input('search.value', ''));

        $query = Review::with(['customer', 'product']);

        if ($searchValue !== '') {
            $query->where('title', 'like', "%{$searchValue}%")
                ->orWhere('content', 'like', "%{$searchValue}%")
                ->orWhereHas('customer', function ($q) use ($searchValue) {
                    $q->where('first_name', 'like', "%{$searchValue}%")
                        ->orWhere('last_name', 'like', "%{$searchValue}%");
                })
                ->orWhereHas('product', function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%");
                });
        }

        $totalRecords = Review::count();
        $filteredRecords = (clone $query)->count();

        $sortColumn = match ((int) $orderIdx) {
            1 => 'rating',
            2 => 'status',
            3 => 'created_at',
            4 => 'created_at',
            default => 'created_at',
        };

        if ($sortColumn === 'created_at') {
            $query->orderBy('created_at', $orderDir);
        } else {
            $query->orderBy($sortColumn, $orderDir);
            $query->orderBy('created_at', 'desc'); // Secondary sort fallback
        }

        $reviews = $query->offset($start)->limit($length)->get();

        $data = $reviews->map(function ($review) {
            $starsHtml = '<div class="flex text-yellow-400 text-sm">';
            for ($i = 1; $i <= 5; $i++) {
                $starsHtml .= $i <= $review->rating ? '★' : '<span class="text-gray-300">★</span>';
            }
            $starsHtml .= '</div>';

            $reviewHtml = $starsHtml . '<div class="text-sm font-semibold text-gray-900 dark:text-primary-a0 max-w-sm truncate" title="' . htmlspecialchars($review->title ?? '') . '">' . htmlspecialchars($review->title ?? 'No Title') . '</div>';
            $reviewHtml .= '<div class="text-xs text-gray-500 max-w-sm truncate" title="' . htmlspecialchars($review->content ?? '') . '">' . htmlspecialchars($review->content ?? '') . '</div>';

            $statusHtml = '';
            if ($review->status == 'approved') {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Approved</span>';
            } elseif ($review->status == 'pending') {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>';
            } else {
                $statusHtml = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Rejected</span>';
            }

            $customerHtml = '<div class="text-sm text-gray-900 dark:text-primary-a0">';
            if ($review->is_anonymous) {
                $customerHtml .= '<i>Anonymous</i>';
            } else {
                $customerHtml .= '<a href="' . route('customers.show', $review->customer_id ?? 0) . '" class="text-indigo-600 dark:text-indigo-400 hover:underline">' . htmlspecialchars(optional($review->customer)->first_name . ' ' . optional($review->customer)->last_name) . '</a>';
            }
            $customerHtml .= '</div><div class="text-xs text-gray-500">Product: <a href="' . route('products.edit', $review->product_id ?? 0) . '" class="text-indigo-600 dark:text-indigo-400 hover:underline">' . htmlspecialchars(optional($review->product)->name ?? '') . '</a></div>';

            return [
                'id' => $review->id,
                'review_html' => $reviewHtml,
                'status_html' => $statusHtml,
                'customer_html' => $customerHtml,
                'date' => $review->created_at->format('M d, Y h:i A'),
                'update_url' => route('reviews.update', $review->id),
                'delete_url' => route('reviews.destroy', $review->id),
                'status' => $review->status,
                'rating' => $review->rating,
                'created_at' => $review->created_at->toIso8601String()
            ];
        });

        return response()->json([
            'draw' => (int) $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->toArray(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,approved,rejected']);
        $review = Review::findOrFail($id);

        $review->update([
            'status' => $request->status,
            'moderated_by' => auth('admin')->id() ?? auth()->id(),
            'moderated_at' => now(),
        ]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review status updated']);
        }
        return back()->with('success', 'Review status updated');
    }

    public function destroy($id)
    {
        Review::findOrFail($id)->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Review deleted completely']);
        }

        return back()->with('success', 'Review deleted completely');
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

        \App\Models\Review::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected reviews deleted successfully.'
        ]);
    }
}
