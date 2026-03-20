<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealisticProductSeeder extends Seeder
{
    public function run(): void
    {
        // ── Load reusable references ───────────────────────────────────────
        $sizeAttr = Attribute::where('slug', 'size')->firstOrFail();
        $colorAttr = Attribute::where('slug', 'color')->firstOrFail();
        $fitAttr = Attribute::where('slug', 'fit')->firstOrFail();
        // $materialAttr = Attribute::where('slug', 'material')->firstOrFail();

        $sizes = AttributeValue::where('attribute_id', $sizeAttr->id)->pluck('id', 'slug')->toArray();
        $colors = AttributeValue::where('attribute_id', $colorAttr->id)->pluck('id', 'slug')->toArray();
        $fits = AttributeValue::where('attribute_id', $fitAttr->id)->pluck('id', 'slug')->toArray();

        // Brands
        $nike = Brand::where('slug', 'nike')->first();
        $adidas = Brand::where('slug', 'adidas')->first();
        $puma = Brand::where('slug', 'puma')->first();
        $local = Brand::where('slug', 'local-threads')->first();

        // Categories (adjust slugs if your CategorySeeder used different ones)
        $menTshirts = Category::where('slug', 't-shirts')->first();
        $menJeans = Category::where('slug', 'jeans')->first();
        $menHoodies = Category::where('slug', 'hoodies')->first();

        // ── Product 1: Nike Sport T-Shirt ────────────────────────────────
        $tshirt = Product::updateOrCreate(
            ['slug' => Str::slug('Nike Sportswear Club T-Shirt')],
            [
                'name' => 'Nike Sportswear Club T-Shirt',
                'description' => 'Classic cotton tee with Nike logo print. Breathable and comfortable for everyday wear or light training.',
                'short_description' => 'Iconic Nike cotton t-shirt',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );

        if ($nike) {
            $tshirt->brand_id = $nike->id;
            $tshirt->save();
        }
        if ($menTshirts) {
            $tshirt->categories()->syncWithoutDetaching([$menTshirts->id]);
        }

        $tshirtVariants = [
            [
                'sku' => 'NK-TS-BLK-S',
                'price' => 39.99,
                'sale_price' => 32.99,
                'stock' => 48,
                'default' => true,
                'sizes' => ['s'],
                'colors' => ['black']
            ],
            [
                'sku' => 'NK-TS-WHT-M',
                'price' => 39.99,
                'stock' => 65,
                'default' => false,
                'sizes' => ['m'],
                'colors' => ['white']
            ],
            [
                'sku' => 'NK-TS-NVY-L',
                'price' => 39.99,
                'sale_price' => 34.99,
                'stock' => 22,
                'default' => false,
                'sizes' => ['l'],
                'colors' => ['navy']
            ],
            [
                'sku' => 'NK-TS-GRY-XL',
                'price' => 42.99,
                'stock' => 15,
                'default' => false,
                'sizes' => ['xl'],
                'colors' => ['grey']
            ],
        ];

        foreach ($tshirtVariants as $data) {
            $variant = $tshirt->variants()->updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'] ?? null,
                    'stock_quantity' => $data['stock'],
                    'is_default' => $data['default'] ?? false,
                    'weight_grams' => 180,
                ]
            );

            // Attach attributes
            foreach ($data['sizes'] as $s)
                $variant->attributeValues()->syncWithoutDetaching([$sizes[$s]]);
            foreach ($data['colors'] as $c)
                $variant->attributeValues()->syncWithoutDetaching([$colors[$c]]);
        }

        // ── Product 2: Adidas Originals Jeans ─────────────────────────────
        $jeans = Product::updateOrCreate(
            ['slug' => Str::slug('Adidas Originals Adicolor Trefoil Jeans')],
            [
                'name' => 'Adidas Originals Adicolor Trefoil Jeans',
                'description' => 'Slim fit stretch denim jeans with signature trefoil branding. Comfortable for casual street style.',
                'short_description' => 'Adidas slim fit jeans',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );

        if ($adidas) {
            $jeans->brand_id = $adidas->id;
            $jeans->save();
        }
        if ($menJeans) {
            $jeans->categories()->syncWithoutDetaching([$menJeans->id]);
        }

        $jeansVariants = [
            [
                'sku' => 'AD-JNS-BLK-30',
                'price' => 89.99,
                'sale_price' => 69.99,
                'stock' => 18,
                'default' => true,
                'sizes' => ['30'],
                'colors' => ['black']
            ],
            [
                'sku' => 'AD-JNS-NAV-32',
                'price' => 89.99,
                'stock' => 34,
                'default' => false,
                'sizes' => ['32'],
                'colors' => ['navy']
            ],
            [
                'sku' => 'AD-JNS-DNM-34',
                'price' => 92.99,
                'stock' => 9,
                'default' => false,
                'sizes' => ['34'],
                'colors' => ['navy']
            ],
        ];

        foreach ($jeansVariants as $data) {
            $variant = $jeans->variants()->updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'] ?? null,
                    'stock_quantity' => $data['stock'],
                    'is_default' => $data['default'] ?? false,
                    'weight_grams' => 680,
                ]
            );

            foreach ($data['sizes'] as $s)
                $variant->attributeValues()->syncWithoutDetaching([$sizes[$s]]);
            foreach ($data['colors'] as $c)
                $variant->attributeValues()->syncWithoutDetaching([$colors[$c]]);
            // Optional: attach 'slim' fit if wanted
            $variant->attributeValues()->syncWithoutDetaching([$fits['slim']]);
        }

        // ── Product 3: Local Threads Oversized Hoodie ─────────────────────
        $hoodie = Product::updateOrCreate(
            ['slug' => Str::slug('Local Threads Heavyweight Oversized Hoodie')],
            [
                'name' => 'Local Threads Heavyweight Oversized Hoodie',
                'description' => 'Premium fleece hoodie with dropped shoulders and front pouch pocket. Perfect for Sri Lankan street style.',
                'short_description' => 'Cozy oversized hoodie – local brand',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );

        if ($local) {
            $hoodie->brand_id = $local->id;
            $hoodie->save();
        }
        if ($menHoodies) {
            $hoodie->categories()->syncWithoutDetaching([$menHoodies->id]);
        }

        $hoodieVariants = [
            [
                'sku' => 'LT-HD-BLK-M',
                'price' => 59.99,
                'sale_price' => 49.99,
                'stock' => 42,
                'default' => true,
                'sizes' => ['m'],
                'colors' => ['black']
            ],
            [
                'sku' => 'LT-HD-GRY-L',
                'price' => 59.99,
                'stock' => 31,
                'default' => false,
                'sizes' => ['l'],
                'colors' => ['grey']
            ],
            [
                'sku' => 'LT-HD-OLV-XL',
                'price' => 62.99,
                'stock' => 12,
                'default' => false,
                'sizes' => ['xl'],
                'colors' => ['olive']
            ],
            [
                'sku' => 'LT-HD-BLK-XXL',
                'price' => 64.99,
                'stock' => 8,
                'default' => false,
                'sizes' => ['xxl'],
                'colors' => ['black']
            ],
        ];

        foreach ($hoodieVariants as $data) {
            $variant = $hoodie->variants()->updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'] ?? null,
                    'stock_quantity' => $data['stock'],
                    'is_default' => $data['default'] ?? false,
                    'weight_grams' => 620,
                ]
            );

            foreach ($data['sizes'] as $s)
                $variant->attributeValues()->syncWithoutDetaching([$sizes[$s]]);
            foreach ($data['colors'] as $c)
                $variant->attributeValues()->syncWithoutDetaching([$colors[$c]]);
            $variant->attributeValues()->syncWithoutDetaching([$fits['oversized']]);
        }

        $this->command->info('→ 3 realistic clothing products + variants + brand/category links seeded.');
    }
}