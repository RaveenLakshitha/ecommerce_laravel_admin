<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Category;
class HomeController extends Controller
{
    public function index()
    {
        $newArrivals = Product::with(['brand', 'primaryImage', 'variants'])
            ->where('is_visible', true)
            ->latest()
            ->take(8)
            ->get();
        $bestSellers = Product::with(['brand', 'primaryImage', 'variants'])
            ->where('is_visible', true)
            ->whereHas('orderItems', function ($q) {
                $q->whereHas('order', function ($q2) {
                    $q2->where('created_at', '>=', now()->subDays(30));
                });
            })
            ->withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(8)
            ->get();
        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::with(['brand', 'primaryImage', 'variants'])
                ->where('is_visible', true)
                ->where('is_featured', true)
                ->latest()
                ->take(8)
                ->get();
        }
        $featuredCollections = Collection::where('is_featured', true)
            ->where('is_active', true)
            ->with('products.primaryImage')
            ->latest()
            ->take(5)
            ->get();
        $featuredCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->withCount([
                'products' => function ($q) {
                    $q->where('is_visible', true);
                }
            ])
            ->orderBy('name')
            ->take(8)
            ->get();
        $storefront = \App\Models\Setting::getAll();
        return view('frontend.home', compact(
            'newArrivals',
            'bestSellers',
            'featuredCollections',
            'featuredCategories',
            'storefront'
        ));
    }
    public function about()
    {
        $storefront = \App\Models\Setting::getAll();
        return view('frontend.pages.about', compact('storefront'));
    }
}
