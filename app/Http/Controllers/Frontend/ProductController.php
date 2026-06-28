<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $currentCategory = null;
        $categoryBanners = [];
        $promoBanners = [];
        if ($request->has('category') && $request->category !== '') {
            $currentCategory = \App\Models\Category::where('slug', $request->category)->first();
            if ($currentCategory) {
                $categoryBanners = $currentCategory->banner_urls;
            }
        }
        $currentCollection = null;
        if ($request->has('collection') && $request->collection !== '') {
            $currentCollection = \App\Models\Collection::where('slug', $request->collection)->first();
            if ($currentCollection && $currentCollection->banner_url) {
                $categoryBanners[] = [
                    'image_url' => $currentCollection->banner_url,
                    'title' => $currentCollection->name,
                    'subtitle' => $currentCollection->description,
                    'link' => '#'
                ];
            }
        }
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
                if (empty($banner['eyebrow'])) {
                    $banner['eyebrow'] = 'SPECIAL OFFER';
                }
                $promoBanners[] = $banner;
            }
        }
        $banners = array_merge($promoBanners, $categoryBanners);
        $query = Product::where('is_visible', true)
            ->with(['variants.attributeValues.attribute', 'primaryImage', 'category', 'brand', 'variants.images', 'images']);
        if ($currentCategory) {
            $query->where('category_id', $currentCategory->id);
        }
        if ($currentCollection) {
            $query->whereHas('collections', fn($q) => $q->where('collections.id', $currentCollection->id));
        }
        $baseQuery = clone $query;
        $baseProductIds = collect(); 
        $baseProductIdsQuery = clone $query;
        $maxPrice = $baseProductIdsQuery->max('base_price') ?? 1000;
        $availableCategories = \App\Models\Category::whereIn('id', (clone $query)->select('category_id'))->get();
        $availableBrands = \App\Models\Brand::whereIn('id', (clone $query)->select('brand_id'))->get();
        $availableAttributes = \App\Models\Attribute::with(['values' => function($q) use ($query) {
            $q->whereHas('variants', function($q2) use ($query) {
                $q2->whereIn('product_id', (clone $query)->select('id'));
            })->distinct();
        }])->whereHas('values.variants', function($q2) use ($query) {
            $q2->whereIn('product_id', (clone $query)->select('id'));
        })->get();
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }
        if ($request->filled('brand_id')) {
            $brands = is_array($request->brand_id) ? $request->brand_id : explode(',', $request->brand_id);
            $query->whereIn('brand_id', $brands);
        }
        if ($request->filled('filter_category_id')) {
            $cats = is_array($request->filter_category_id) ? $request->filter_category_id : explode(',', $request->filter_category_id);
            $query->whereIn('category_id', $cats);
        }
        if ($request->has('attributes') && is_array($request->input('attributes'))) {
            foreach ($request->input('attributes') as $slug => $values) {
                if (empty($values)) continue;
                $valArray = is_array($values) ? $values : explode(',', $values);
                $query->whereHas('variants.attributeValues', function ($q) use ($valArray, $slug) {
                    $q->whereIn('value', $valArray)->whereHas('attribute', function($q2) use ($slug) {
                        $q2->where('slug', $slug);
                    });
                });
            }
        }
        if ($request->has('sort') && $request->sort !== '') {
            switch ($request->sort) {
                case 'az': $query->orderBy('name', 'asc'); break;
                case 'za': $query->orderBy('name', 'desc'); break;
                case 'lh': $query->orderBy('base_price', 'asc'); break;
                case 'hl': $query->orderBy('base_price', 'desc'); break;
                case 'new': default: $query->latest(); break;
            }
        } else {
            $query->latest();
        }
        $products = $query->paginate(12)->withQueryString();
        if ($request->ajax()) {
            return view('frontend.products.partials.grid', compact('products'))->render();
        }
        return view('frontend.products.index', compact('products', 'currentCategory', 'currentCollection', 'banners', 'maxPrice', 'availableCategories', 'availableBrands', 'availableAttributes'));
    }
    public function show($slug)
    {
        $product = Product::with(['variants.attributeValues.attribute', 'variants.images', 'images', 'brand', 'category', 'reviews.customer'])
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();
        $relatedProducts = collect();
        if ($product->category_id) {
            $relatedProducts = Product::with('primaryImage', 'images', 'variants')
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('is_visible', true)
                ->latest()
                ->take(4)
                ->get();
        }
        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
