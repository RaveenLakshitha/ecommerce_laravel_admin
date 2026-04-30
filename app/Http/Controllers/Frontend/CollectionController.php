<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Display a listing of all active collections.
     */
    public function index()
    {
        $collections = Collection::where('is_active', true)
            ->withCount(['products' => function ($query) {
                $query->where('is_visible', true);
            }])
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(12);

        $storefront = \App\Models\Setting::getAll();

        return view('frontend.collections.index', compact('collections', 'storefront'));
    }
}
