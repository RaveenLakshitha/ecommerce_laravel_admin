<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variants', 'primaryImage')
            ->where('is_visible', true)
            ->latest()
            ->paginate(12);

        $categories = \App\Models\Category::orderBy('name')->get();

        $colorAttr = \App\Models\Attribute::with('values')->where('slug', 'color')->first();
        $colors = $colorAttr ? $colorAttr->values : collect();

        $sizeAttr = \App\Models\Attribute::with('values')->where('slug', 'size')->first();
        $sizes = $sizeAttr ? $sizeAttr->values : collect();

        return view('frontend.products.index', compact('products', 'categories', 'colors', 'sizes'));
    }

    public function show($slug)
    {
        $product = Product::with('variants', 'images', 'brand', 'categories')
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->firstOrFail();

        return view('frontend.products.show', compact('product'));
    }
}
