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
            // Numeric sizes (jeans, shirts, shoes, etc.)
            ['value' => '28', 'slug' => '28', 'sort_order' => 10],
            ['value' => '30', 'slug' => '30', 'sort_order' => 11],
            ['value' => '32', 'slug' => '32', 'sort_order' => 12],
            ['value' => '34', 'slug' => '34', 'sort_order' => 13],
            ['value' => '36', 'slug' => '36', 'sort_order' => 14],
            ['value' => '38', 'slug' => '38', 'sort_order' => 15],
            ['value' => '40', 'slug' => '40', 'sort_order' => 16],
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
            ['value' => 'Brown', 'slug' => 'brown', 'color_hex' => '#8B4513'],
            ['value' => 'Orange', 'slug' => 'orange', 'color_hex' => '#FF4500'],
            ['value' => 'Teal', 'slug' => 'teal', 'color_hex' => '#008080'],
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
            ['value' => 'Nylon', 'slug' => 'nylon'],
            ['value' => 'Fleece', 'slug' => 'fleece'],
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

        // ── Pattern (new core attribute) ───────────────────────
        $pattern = Attribute::where('slug', 'pattern')->firstOrFail();

        $patterns = [
            ['value' => 'Solid', 'slug' => 'solid', 'sort_order' => 1],
            ['value' => 'Striped', 'slug' => 'striped', 'sort_order' => 2],
            ['value' => 'Floral', 'slug' => 'floral', 'sort_order' => 3],
            ['value' => 'Plaid', 'slug' => 'plaid', 'sort_order' => 4],
            ['value' => 'Polka Dot', 'slug' => 'polka-dot', 'sort_order' => 5],
            ['value' => 'Geometric', 'slug' => 'geometric', 'sort_order' => 6],
            ['value' => 'Camouflage', 'slug' => 'camouflage', 'sort_order' => 7],
            ['value' => 'Animal Print', 'slug' => 'animal-print', 'sort_order' => 8],
            ['value' => 'Checkered', 'slug' => 'checkered', 'sort_order' => 9],
            ['value' => 'Paisley', 'slug' => 'paisley', 'sort_order' => 10],
        ];

        foreach ($patterns as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $pattern->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $pattern->id]
            );
        }

        // ── Width (Shoes) ──────────────────────────────────────
        $width = Attribute::where('slug', 'width')->firstOrFail();

        $widths = [
            ['value' => 'Narrow', 'slug' => 'narrow', 'sort_order' => 1],
            ['value' => 'Medium', 'slug' => 'medium', 'sort_order' => 2],
            ['value' => 'Wide', 'slug' => 'wide', 'sort_order' => 3],
            ['value' => 'Extra Wide', 'slug' => 'extra-wide', 'sort_order' => 4],
        ];

        foreach ($widths as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $width->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $width->id]
            );
        }

        // ── Heel Height (Shoes) ────────────────────────────────
        $heel = Attribute::where('slug', 'heel-height')->firstOrFail();

        $heels = [
            ['value' => 'Flat (0")', 'slug' => 'flat', 'sort_order' => 1],
            ['value' => 'Low (1-2")', 'slug' => 'low', 'sort_order' => 2],
            ['value' => 'Medium (2-3")', 'slug' => 'medium', 'sort_order' => 3],
            ['value' => 'High (3"+)', 'slug' => 'high', 'sort_order' => 4],
            ['value' => 'Stiletto', 'slug' => 'stiletto', 'sort_order' => 5],
            ['value' => 'Wedge', 'slug' => 'wedge', 'sort_order' => 6],
        ];

        foreach ($heels as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $heel->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $heel->id]
            );
        }

        // ── Toe Style (Shoes) ──────────────────────────────────
        $toe = Attribute::where('slug', 'toe-style')->firstOrFail();

        $toes = [
            ['value' => 'Round Toe', 'slug' => 'round-toe', 'sort_order' => 1],
            ['value' => 'Pointed Toe', 'slug' => 'pointed-toe', 'sort_order' => 2],
            ['value' => 'Square Toe', 'slug' => 'square-toe', 'sort_order' => 3],
            ['value' => 'Open Toe', 'slug' => 'open-toe', 'sort_order' => 4],
            ['value' => 'Almond Toe', 'slug' => 'almond-toe', 'sort_order' => 5],
        ];

        foreach ($toes as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $toe->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $toe->id]
            );
        }

        // ── Closure Type (Shoes) ───────────────────────────────
        $closure = Attribute::where('slug', 'closure-type')->firstOrFail();

        $closures = [
            ['value' => 'Lace Up', 'slug' => 'lace-up', 'sort_order' => 1],
            ['value' => 'Slip On', 'slug' => 'slip-on', 'sort_order' => 2],
            ['value' => 'Velcro', 'slug' => 'velcro', 'sort_order' => 3],
            ['value' => 'Zipper', 'slug' => 'zipper', 'sort_order' => 4],
            ['value' => 'Buckle', 'slug' => 'buckle', 'sort_order' => 5],
            ['value' => 'Hook & Loop', 'slug' => 'hook-loop', 'sort_order' => 6],
        ];

        foreach ($closures as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $closure->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $closure->id]
            );
        }

        // ── Metal Type (Jewelry) ───────────────────────────────
        $metal = Attribute::where('slug', 'metal-type')->firstOrFail();

        $metals = [
            ['value' => 'Gold', 'slug' => 'gold', 'sort_order' => 1],
            ['value' => 'Silver', 'slug' => 'silver', 'sort_order' => 2],
            ['value' => 'Rose Gold', 'slug' => 'rose-gold', 'sort_order' => 3],
            ['value' => 'Platinum', 'slug' => 'platinum', 'sort_order' => 4],
            ['value' => 'Stainless Steel', 'slug' => 'stainless-steel', 'sort_order' => 5],
            ['value' => 'Brass', 'slug' => 'brass', 'sort_order' => 6],
        ];

        foreach ($metals as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $metal->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $metal->id]
            );
        }

        // ── Gemstone (Jewelry) ─────────────────────────────────
        $gem = Attribute::where('slug', 'gemstone')->firstOrFail();

        $gems = [
            ['value' => 'Diamond', 'slug' => 'diamond', 'sort_order' => 1],
            ['value' => 'Ruby', 'slug' => 'ruby', 'sort_order' => 2],
            ['value' => 'Sapphire', 'slug' => 'sapphire', 'sort_order' => 3],
            ['value' => 'Emerald', 'slug' => 'emerald', 'sort_order' => 4],
            ['value' => 'Pearl', 'slug' => 'pearl', 'sort_order' => 5],
            ['value' => 'Amethyst', 'slug' => 'amethyst', 'sort_order' => 6],
            ['value' => 'Cubic Zirconia', 'slug' => 'cubic-zirconia', 'sort_order' => 7],
            ['value' => 'None', 'slug' => 'none', 'sort_order' => 8],
        ];

        foreach ($gems as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $gem->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $gem->id]
            );
        }

        // ── Length (Jewelry - Necklaces) ───────────────────────
        $length = Attribute::where('slug', 'length')->firstOrFail();

        $lengths = [
            ['value' => 'Choker (14-16")', 'slug' => 'choker', 'sort_order' => 1],
            ['value' => 'Princess (17-19")', 'slug' => 'princess', 'sort_order' => 2],
            ['value' => 'Matinee (20-24")', 'slug' => 'matinee', 'sort_order' => 3],
            ['value' => 'Opera (28-34")', 'slug' => 'opera', 'sort_order' => 4],
            ['value' => 'Rope (36"+)', 'slug' => 'rope', 'sort_order' => 5],
        ];

        foreach ($lengths as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $length->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $length->id]
            );
        }

        // ── Jewelry Size (Rings) ───────────────────────────────
        $jewelrySize = Attribute::where('slug', 'jewelry-size')->firstOrFail();

        $jewelrySizes = [
            ['value' => 'Size 5', 'slug' => '5', 'sort_order' => 1],
            ['value' => 'Size 6', 'slug' => '6', 'sort_order' => 2],
            ['value' => 'Size 7', 'slug' => '7', 'sort_order' => 3],
            ['value' => 'Size 8', 'slug' => '8', 'sort_order' => 4],
            ['value' => 'Size 9', 'slug' => '9', 'sort_order' => 5],
            ['value' => 'Size 10', 'slug' => '10', 'sort_order' => 6],
        ];

        foreach ($jewelrySizes as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $jewelrySize->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $jewelrySize->id]
            );
        }

        // ── Case Size (Watches) ────────────────────────────────
        $caseSize = Attribute::where('slug', 'case-size')->firstOrFail();

        $caseSizes = [
            ['value' => '34mm', 'slug' => '34mm', 'sort_order' => 1],
            ['value' => '38mm', 'slug' => '38mm', 'sort_order' => 2],
            ['value' => '40mm', 'slug' => '40mm', 'sort_order' => 3],
            ['value' => '42mm', 'slug' => '42mm', 'sort_order' => 4],
            ['value' => '44mm', 'slug' => '44mm', 'sort_order' => 5],
            ['value' => '46mm', 'slug' => '46mm', 'sort_order' => 6],
        ];

        foreach ($caseSizes as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $caseSize->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $caseSize->id]
            );
        }

        // ── Band Material (Watches) ────────────────────────────
        $band = Attribute::where('slug', 'band-material')->firstOrFail();

        $bands = [
            ['value' => 'Leather', 'slug' => 'leather', 'sort_order' => 1],
            ['value' => 'Stainless Steel', 'slug' => 'stainless-steel', 'sort_order' => 2],
            ['value' => 'Silicone', 'slug' => 'silicone', 'sort_order' => 3],
            ['value' => 'Fabric', 'slug' => 'fabric', 'sort_order' => 4],
            ['value' => 'Gold', 'slug' => 'gold', 'sort_order' => 5],
            ['value' => 'Rose Gold', 'slug' => 'rose-gold', 'sort_order' => 6],
        ];

        foreach ($bands as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $band->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $band->id]
            );
        }

        // ── Activity Type (Activewear) ─────────────────────────
        $activity = Attribute::where('slug', 'activity-type')->firstOrFail();

        $activities = [
            ['value' => 'Running', 'slug' => 'running', 'sort_order' => 1],
            ['value' => 'Yoga', 'slug' => 'yoga', 'sort_order' => 2],
            ['value' => 'Training/Gym', 'slug' => 'training', 'sort_order' => 3],
            ['value' => 'Hiking', 'slug' => 'hiking', 'sort_order' => 4],
            ['value' => 'Cycling', 'slug' => 'cycling', 'sort_order' => 5],
            ['value' => 'Swimming', 'slug' => 'swimming', 'sort_order' => 6],
            ['value' => 'All Sports', 'slug' => 'all-sports', 'sort_order' => 7],
        ];

        foreach ($activities as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $activity->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $activity->id]
            );
        }

        // ── Compression Level (Activewear) ─────────────────────
        $compression = Attribute::where('slug', 'compression-level')->firstOrFail();

        $compressions = [
            ['value' => 'Light', 'slug' => 'light', 'sort_order' => 1],
            ['value' => 'Medium', 'slug' => 'medium', 'sort_order' => 2],
            ['value' => 'High', 'slug' => 'high', 'sort_order' => 3],
        ];

        foreach ($compressions as $item) {
            AttributeValue::updateOrCreate(
                ['attribute_id' => $compression->id, 'slug' => $item['slug']],
                $item + ['attribute_id' => $compression->id]
            );
        }
    }
}