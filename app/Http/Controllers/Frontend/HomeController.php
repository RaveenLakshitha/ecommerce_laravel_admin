<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        // Featured / hero banners (from DB or static for start)
        // $banners = Banner::where('is_active', true)
        //     ->orderBy('sort_order')
        //     ->take(5)
        //     ->get();

        // New arrivals (latest 8 products)
        $newArrivals = Product::with('variants', 'primaryImage', 'categories')
            ->latest()
            ->take(8)
            ->get();

        // Best sellers (example: most ordered in last 30 days)
        $bestSellers = Product::with('variants', 'primaryImage', 'categories')
            ->whereHas('orderItems', function ($q) {
                $q->whereHas('order', function ($q2) {
                    $q2->where('created_at', '>=', now()->subDays(30));
                });
            })
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(8)
            ->get();

        // Featured collections (e.g. Summer Sale, New Season)
        $featuredCollections = Collection::where('is_featured', true)
            ->with('products')
            ->take(3)
            ->get();

        return view('frontend.home', compact(
            //'banners',
            'newArrivals',
            'bestSellers',
            'featuredCollections'
        ));
    }
}
