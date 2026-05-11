<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['variants.attributeValues.attribute', 'primaryImage', 'category'])
            ->where('is_visible', true);

        $currentCategory = null;
        $categoryBanners = [];
        $promoBanners = [];
        
        if ($request->has('category') && $request->category !== '') {
            $currentCategory = \App\Models\Category::where('slug', $request->category)->first();
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
                $categoryBanners = $currentCategory->banner_urls;
            }
        }

        $currentCollection = null;
        if ($request->has('collection') && $request->collection !== '') {
            $currentCollection = \App\Models\Collection::where('slug', $request->collection)->first();
            if ($currentCollection) {
                $query->whereHas('collections', fn($q) => $q->where('collections.id', $currentCollection->id));
                // If the collection has a banner, we could use it too
                if ($currentCollection->banner_url) {
                    $categoryBanners[] = [
                        'image_url' => $currentCollection->banner_url,
                        'title' => $currentCollection->name,
                        'subtitle' => $currentCollection->description,
                        'link' => '#'
                    ];
                }
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

        // Base query for filters (only category/collection)
        $filterBaseQuery = Product::where('is_visible', true);
        if ($currentCategory) {
            $filterBaseQuery->where('category_id', $currentCategory->id);
        }
        if ($currentCollection) {
            $filterBaseQuery->whereHas('collections', fn($q) => $q->where('collections.id', $currentCollection->id));
        }

        $maxPriceAvailable = $filterBaseQuery->max('base_price') ?? 15000;
        $minPriceAvailable = $filterBaseQuery->min('base_price') ?? 0;

        // Apply Filters
        if ($request->has('colors') && is_array($request->colors)) {
            $query->whereHas('variants.attributeValues', function ($q) use ($request) {
                $q->whereIn('slug', $request->colors);
            });
        }

        if ($request->has('sizes') && is_array($request->sizes)) {
            $query->whereHas('variants.attributeValues', function ($q) use ($request) {
                $q->whereIn('slug', $request->sizes);
            });
        }

        if ($request->has('attributes') && is_array($request->attributes)) {
            foreach ($request->attributes as $attrSlug => $values) {
                if (is_array($values) && count($values) > 0) {
                    $query->whereHas('variants.attributeValues', function ($q) use ($values) {
                        $q->whereIn('slug', $values);
                    });
                }
            }
        }

        $currentMaxPrice = $request->has('max_price') ? (float) $request->max_price : $maxPriceAvailable;
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('base_price', '<=', $currentMaxPrice);
        }
        
        // Sorting
        if ($request->has('sort') && $request->sort !== '') {
            switch ($request->sort) {
                case 'az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'za':
                    $query->orderBy('name', 'desc');
                    break;
                case 'lh':
                    $query->orderBy('base_price', 'asc');
                    break;
                case 'hl':
                    $query->orderBy('base_price', 'desc');
                    break;
                case 'new':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $categoryBaseQuery = Product::where('is_visible', true);
        if ($currentCollection) {
            $categoryBaseQuery->whereHas('collections', fn($q) => $q->where('collections.id', $currentCollection->id));
        }
        $categoryProductIds = (clone $categoryBaseQuery)->pluck('id');

        $categories = \App\Models\Category::where('is_active', true)
            ->where(function ($q) {
                // Keep categories that are parent-less (top level) OR whose parent is active
                $q->whereNull('parent_id')
                  ->orWhereHas('parent', function ($parentQuery) {
                      $parentQuery->where('is_active', true);
                  });
            })
            ->whereHas('products', function ($q) use ($categoryProductIds) {
                $q->whereIn('products.id', $categoryProductIds);
            })
            ->withCount(['products' => function ($q) use ($categoryProductIds) {
                $q->whereIn('products.id', $categoryProductIds);
            }])
            ->orderBy('name')
            ->get();

        // Scope attributes to the base filtered products
        $baseProductIds = (clone $filterBaseQuery)->pluck('id');

        $dynamicAttributes = \App\Models\Attribute::whereHas('values.variants', function($q) use ($baseProductIds) {
            $q->whereIn('product_id', $baseProductIds);
        })->with(['values' => function($q) use ($baseProductIds) {
            $q->whereHas('variants', function($q) use ($baseProductIds) {
                $q->whereIn('product_id', $baseProductIds);
            })->withCount(['variants' => function($q) use ($baseProductIds) {
                $q->whereIn('product_id', $baseProductIds);
            }]);
        }])->get();

        $colors = $dynamicAttributes->where('slug', 'color')->first()?->values ?? collect();
        $sizes = $dynamicAttributes->where('slug', 'size')->first()?->values ?? collect();

        $otherAttributes = $dynamicAttributes->reject(function($attr) {
            return in_array($attr->slug, ['color', 'size']);
        });

        return view('frontend.products.index', compact('products', 'categories', 'colors', 'sizes', 'otherAttributes', 'currentCategory', 'currentCollection', 'banners', 'maxPriceAvailable', 'minPriceAvailable', 'currentMaxPrice'));
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
