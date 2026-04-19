<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        // Pre-load attributes & some values for easier reference
        $sizeAttr = Attribute::where('slug', 'size')->firstOrFail();
        $colorAttr = Attribute::where('slug', 'color')->firstOrFail();
        $fitAttr = Attribute::where('slug', 'fit')->firstOrFail();
        $materialAttr = Attribute::where('slug', 'material')->firstOrFail();

        $sizes = AttributeValue::where('attribute_id', $sizeAttr->id)->pluck('id', 'slug')->toArray();
        $colors = AttributeValue::where('attribute_id', $colorAttr->id)->pluck('id', 'slug')->toArray();
        $fits = AttributeValue::where('attribute_id', $fitAttr->id)->pluck('id', 'slug')->toArray();

        // ── Product 1: Basic Cotton T-Shirt ───────────────────────────────
        $tshirt = Product::create([
            'name' => 'Basic Cotton T-Shirt',
            'slug' => Str::slug('Basic Cotton T-Shirt'),
            'description' => 'Soft 100% cotton everyday t-shirt. Classic fit, great for casual wear.',
            'is_visible' => true,
            'is_featured' => true,
        ]);

        $tshirtVariantsData = [
            // S - Black
            [
                'sku' => 'TS-BLK-S',
                'price' => 24.99,
                'sale_price' => 19.99,
                'stock_quantity' => 45,
                'is_default' => true,
                'sizes' => ['s'],
                'colors' => ['black']
            ],
            // S - White
            [
                'sku' => 'TS-WHT-S',
                'price' => 24.99,
                'stock_quantity' => 38,
                'sizes' => ['s'],
                'colors' => ['white']
            ],
            // M - Black
            [
                'sku' => 'TS-BLK-M',
                'price' => 24.99,
                'sale_price' => 19.99,
                'stock_quantity' => 62,
                'sizes' => ['m'],
                'colors' => ['black']
            ],
            // M - Navy
            [
                'sku' => 'TS-NVY-M',
                'price' => 24.99,
                'stock_quantity' => 31,
                'sizes' => ['m'],
                'colors' => ['navy']
            ],
            // L - Grey
            [
                'sku' => 'TS-GRY-L',
                'price' => 24.99,
                'stock_quantity' => 19,
                'sizes' => ['l'],
                'colors' => ['grey']
            ],
            // XL - Black
            [
                'sku' => 'TS-BLK-XL',
                'price' => 24.99,
                'sale_price' => 21.99,
                'stock_quantity' => 12,
                'sizes' => ['xl'],
                'colors' => ['black']
            ],
        ];

        foreach ($tshirtVariantsData as $data) {
            $variant = $tshirt->variants()->create([
                'sku' => $data['sku'],
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock_quantity' => $data['stock_quantity'],
                'is_default' => $data['is_default'] ?? false,
            ]);

            // Attach sizes
            foreach ($data['sizes'] as $sizeSlug) {
                $variant->attributeValues()->attach($sizes[$sizeSlug]);
            }

            // Attach colors
            foreach ($data['colors'] as $colorSlug) {
                $variant->attributeValues()->attach($colors[$colorSlug]);
            }
        }

        // ── Product 2: Slim Fit Jeans ─────────────────────────────────────
        $jeans = Product::create([
            'name' => 'Slim Fit Stretch Jeans',
            'slug' => Str::slug('Slim Fit Stretch Jeans'),
            'description' => 'Modern slim fit with comfort stretch. Dark wash denim.',
            'is_visible' => true,
            'is_featured' => false,
        ]);

        $jeansVariantsData = [
            [
                'sku' => 'JNS-DRK-30',
                'price' => 59.99,
                'sale_price' => 49.99,
                'stock_quantity' => 28,
                'is_default' => true,
                'sizes' => ['30'],
                'colors' => ['navy'],
                'fits' => ['slim']
            ],
            [
                'sku' => 'JNS-DRK-32',
                'price' => 59.99,
                'stock_quantity' => 41,
                'sizes' => ['32'],
                'colors' => ['navy'],
                'fits' => ['slim']
            ],
            [
                'sku' => 'JNS-DRK-34',
                'price' => 59.99,
                'stock_quantity' => 22,
                'sizes' => ['34'],
                'colors' => ['navy'],
                'fits' => ['slim']
            ],
            [
                'sku' => 'JNS-BLK-32',
                'price' => 62.99,
                'stock_quantity' => 15,
                'sizes' => ['32'],
                'colors' => ['black'],
                'fits' => ['slim']
            ],
        ];

        foreach ($jeansVariantsData as $data) {
            $variant = $jeans->variants()->create([
                'sku' => $data['sku'],
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock_quantity' => $data['stock_quantity'],
                'is_default' => $data['is_default'] ?? false,
            ]);

            foreach ($data['sizes'] as $s) {
                $variant->attributeValues()->attach($sizes[$s]);
            }
            foreach ($data['colors'] as $c) {
                $variant->attributeValues()->attach($colors[$c]);
            }
            foreach ($data['fits'] ?? [] as $f) {
                $variant->attributeValues()->attach($fits[$f]);
            }
        }

        // ── Product 3: Oversized Hoodie ───────────────────────────────────
        $hoodie = Product::create([
            'name' => 'Heavyweight Oversized Hoodie',
            'slug' => Str::slug('Heavyweight Oversized Hoodie'),
            'description' => 'Super soft fleece, dropped shoulders, perfect for streetwear.',
            'is_visible' => true,
            'is_featured' => true,
        ]);

        $hoodieVariantsData = [
            [
                'sku' => 'HD-BLK-S',
                'price' => 49.99,
                'stock_quantity' => 18,
                'is_default' => false,
                'sizes' => ['s'],
                'colors' => ['black']
            ],
            [
                'sku' => 'HD-BLK-M',
                'price' => 49.99,
                'sale_price' => 39.99,
                'stock_quantity' => 55,
                'is_default' => true,
                'sizes' => ['m'],
                'colors' => ['black']
            ],
            [
                'sku' => 'HD-GRY-L',
                'price' => 49.99,
                'stock_quantity' => 33,
                'sizes' => ['l'],
                'colors' => ['grey']
            ],
            [
                'sku' => 'HD-OLV-XL',
                'price' => 52.99,
                'stock_quantity' => 8,
                'sizes' => ['xl'],
                'colors' => ['olive']
            ],
        ];

        foreach ($hoodieVariantsData as $data) {
            $variant = $hoodie->variants()->create([
                'sku' => $data['sku'],
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'stock_quantity' => $data['stock_quantity'],
                'is_default' => $data['is_default'] ?? false,
            ]);

            foreach ($data['sizes'] as $s) {
                $variant->attributeValues()->attach($sizes[$s]);
            }
            foreach ($data['colors'] as $c) {
                $variant->attributeValues()->attach($colors[$c]);
            }
        }

        $this->command->info('Sample products, variants and attribute combinations seeded successfully!');
    }
}