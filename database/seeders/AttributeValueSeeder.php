<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeValueSeeder extends Seeder
{
    public function run(): void
    {
        // ── Size ───────────────────────────────────────────────
        $size = Attribute::where('slug', 'size')->firstOrFail();

        $sizes = [
            ['value' => 'XXS', 'slug' => 'xxs', 'sort_order' => 1],
            ['value' => 'XS', 'slug' => 'xs', 'sort_order' => 2],
            ['value' => 'S', 'slug' => 's', 'sort_order' => 3],
            ['value' => 'M', 'slug' => 'm', 'sort_order' => 4],
            ['value' => 'L', 'slug' => 'l', 'sort_order' => 5],
            ['value' => 'XL', 'slug' => 'xl', 'sort_order' => 6],
            ['value' => 'XXL', 'slug' => 'xxl', 'sort_order' => 7],
            ['value' => '3XL', 'slug' => '3xl', 'sort_order' => 8],
            ['value' => '4XL', 'slug' => '4xl', 'sort_order' => 9],
            // Optional numeric (jeans, shirts, etc.)
            ['value' => '28', 'slug' => '28', 'sort_order' => 10],
            ['value' => '30', 'slug' => '30', 'sort_order' => 11],
            ['value' => '32', 'slug' => '32', 'sort_order' => 12],
            ['value' => '34', 'slug' => '34', 'sort_order' => 13],
            ['value' => '36', 'slug' => '36', 'sort_order' => 14],
        ];

        foreach ($sizes as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $size->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $size->id]
            );
        }

        // ── Color (with hex for swatches) ─────────────────────────────
        $color = Attribute::where('slug', 'color')->firstOrFail();

        $colors = [
            ['value' => 'Black', 'slug' => 'black', 'color_hex' => '#000000'],
            ['value' => 'White', 'slug' => 'white', 'color_hex' => '#FFFFFF'],
            ['value' => 'Navy', 'slug' => 'navy', 'color_hex' => '#001F3F'],
            ['value' => 'Grey', 'slug' => 'grey', 'color_hex' => '#808080'],
            ['value' => 'Charcoal', 'slug' => 'charcoal', 'color_hex' => '#36454F'],
            ['value' => 'Red', 'slug' => 'red', 'color_hex' => '#FF0000'],
            ['value' => 'Burgundy', 'slug' => 'burgundy', 'color_hex' => '#800020'],
            ['value' => 'Green', 'slug' => 'green', 'color_hex' => '#008000'],
            ['value' => 'Olive', 'slug' => 'olive', 'color_hex' => '#556B2F'],
            ['value' => 'Beige', 'slug' => 'beige', 'color_hex' => '#F5F5DC'],
            ['value' => 'Khaki', 'slug' => 'khaki', 'color_hex' => '#C3B091'],
            ['value' => 'Blue', 'slug' => 'blue', 'color_hex' => '#0000FF'],
            ['value' => 'Royal Blue', 'slug' => 'royal-blue', 'color_hex' => '#4169E1'],
            ['value' => 'Pink', 'slug' => 'pink', 'color_hex' => '#FFC0CB'],
            ['value' => 'Yellow', 'slug' => 'yellow', 'color_hex' => '#FFFF00'],
            ['value' => 'Purple', 'slug' => 'purple', 'color_hex' => '#800080'],
            ['value' => 'Cream', 'slug' => 'cream', 'color_hex' => '#FFFDD0'],
        ];

        foreach ($colors as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $color->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $color->id]
            );
        }

        // ── Material ───────────────────────────────────────────
        $material = Attribute::where('slug', 'material')->firstOrFail();

        $materials = [
            ['value' => 'Cotton', 'slug' => 'cotton'],
            ['value' => 'Organic Cotton', 'slug' => 'organic-cotton'],
            ['value' => 'Polyester', 'slug' => 'polyester'],
            ['value' => 'Cotton/Polyester', 'slug' => 'cotton-polyester'],
            ['value' => 'Linen', 'slug' => 'linen'],
            ['value' => 'Wool', 'slug' => 'wool'],
            ['value' => 'Merino Wool', 'slug' => 'merino-wool'],
            ['value' => 'Denim', 'slug' => 'denim'],
            ['value' => 'Silk', 'slug' => 'silk'],
            ['value' => 'Rayon', 'slug' => 'rayon'],
            ['value' => 'Viscose', 'slug' => 'viscose'],
            ['value' => 'Spandex', 'slug' => 'spandex'],
            ['value' => 'Elastane', 'slug' => 'elastane'],
            ['value' => 'Leather', 'slug' => 'leather'],
            ['value' => 'Suede', 'slug' => 'suede'],
        ];

        foreach ($materials as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $material->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $material->id, 'sort_order' => 0]
            );
        }

        // ── Fit ────────────────────────────────────────────────
        $fit = Attribute::where('slug', 'fit')->firstOrFail();

        $fits = [
            ['value' => 'Slim', 'slug' => 'slim'],
            ['value' => 'Regular', 'slug' => 'regular'],
            ['value' => 'Relaxed', 'slug' => 'relaxed'],
            ['value' => 'Oversized', 'slug' => 'oversized'],
            ['value' => 'Tailored', 'slug' => 'tailored'],
            ['value' => 'Skinny', 'slug' => 'skinny'],
            ['value' => 'Straight', 'slug' => 'straight'],
            ['value' => 'Loose', 'slug' => 'loose'],
            ['value' => 'Athletic', 'slug' => 'athletic'],
        ];

        foreach ($fits as $i => $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $fit->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $fit->id, 'sort_order' => $i + 1]
            );
        }
    }
}