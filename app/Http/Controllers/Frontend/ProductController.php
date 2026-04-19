<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['variants.attributeValues.attribute', 'primaryImage', 'categories'])
            ->where('is_visible', true);

        $currentCategory = null;
        $categoryBanners = [];
        $promoBanners = [];
        
        if ($request->has('category') && $request->category !== '') {
            $currentCategory = \App\Models\Category::where('slug', $request->category)->first();
            if ($currentCategory) {
                $query->whereHas('categories', function($q) use ($currentCategory) {
                    $q->where('categories.id', $currentCategory->id);
                });
                $categoryBanners = $currentCategory->banner_urls;
            }
        }

        // Fetch active Discount Rule banners
        $activeRules = \App\Models\DiscountRule::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->whereNotNull('banner_images')
            ->where('banner_images', '!=', '[]')
            ->when($currentCategory, function ($q) use ($currentCategory) {
                // Fetch rules for this category OR global ones
                $q->where(function ($sub) use ($currentCategory) {
                    $sub->where(function ($app) use ($currentCategory) {
                        $app->where('applies_to', 'categories')
                            ->whereHas('categories', fn($c) => $c->where('categories.id', $currentCategory->id));
                    })->orWhere('applies_to', 'all');
                });
            }, function ($q) {
                $q->where('applies_to', 'all');
            })
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($activeRules as $rule) {
            foreach ($rule->banner_urls as $banner) {
                // Add a "PROMOTION" tag if not present
                if (empty($banner['eyebrow'])) {
                    $banner['eyebrow'] = 'SPECIAL OFFER';
                }
                $promoBanners[] = $banner;
            }
        }

        // Merge: Promotions first, then Category banners
        $banners = array_merge($promoBanners, $categoryBanners);

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = \App\Models\Category::where('is_active', true)
            ->where(function ($q) {
                // Keep categories that are parent-less (top level) OR whose parent is active
                $q->whereNull('parent_id')
                  ->orWhereHas('parent', function ($parentQuery) {
                      $parentQuery->where('is_active', true);
                  });
            })
            ->orderBy('name')
            ->get();

        // Scope attributes to the filtered products
        $productIds = (clone $query)->pluck('id');

        $colorAttr = \App\Models\Attribute::where('slug', 'color')->first();
        $colors = $colorAttr ? $colorAttr->values()->whereHas('variants', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->get() : collect();

        $sizeAttr = \App\Models\Attribute::where('slug', 'size')->first();
        $sizes = $sizeAttr ? $sizeAttr->values()->whereHas('variants', function($q) use ($productIds) {
            $q->whereIn('product_id', $productIds);
        })->get() : collect();

        return view('frontend.products.index', compact('products', 'categories', 'colors', 'sizes', 'currentCategory', 'banners'));
    }

    public function show($slug)
    {
        $product = Product::with('variants.attributeValues.attribute', 'images', 'brand', 'categories', 'reviews.customer')
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        $relatedProducts = collect();
        if ($product->categories->isNotEmpty()) {
            $relatedProducts = Product::with('primaryImage', 'images', 'variants')
                ->whereHas('categories', function($q) use ($product) {
                    $q->whereIn('categories.id', $product->categories->pluck('id'));
                })
                ->where('id', '!=', $product->id)
                ->where('is_visible', true)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
