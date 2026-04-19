<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'select',
                'sort_order' => 10,
            ],
            [
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color_swatch',
                'sort_order' => 20,
            ],
            [
                'name' => 'Material',
                'slug' => 'material',
                'type' => 'select',
                'sort_order' => 30,
            ],
            [
                'name' => 'Fit',
                'slug' => 'fit',
                'type' => 'select',
                'sort_order' => 40,
            ],
            [
                'name' => 'Pattern',
                'slug' => 'pattern',
                'type' => 'select',
                'sort_order' => 50,
            ],
            [
                'name' => 'Width',
                'slug' => 'width',
                'type' => 'select',
                'sort_order' => 60,
            ],
            [
                'name' => 'Heel Height',
                'slug' => 'heel-height',
                'type' => 'select',
                'sort_order' => 70,
            ],
            [
                'name' => 'Toe Style',
                'slug' => 'toe-style',
                'type' => 'select',
                'sort_order' => 80,
            ],
            [
                'name' => 'Closure Type',
                'slug' => 'closure-type',
                'type' => 'select',
                'sort_order' => 90,
            ],
            [
                'name' => 'Metal Type',
                'slug' => 'metal-type',
                'type' => 'select',
                'sort_order' => 100,
            ],
            [
                'name' => 'Gemstone',
                'slug' => 'gemstone',
                'type' => 'select',
                'sort_order' => 110,
            ],
            [
                'name' => 'Length',
                'slug' => 'length',
                'type' => 'select',
                'sort_order' => 120,
            ],
            [
                'name' => 'Jewelry Size',
                'slug' => 'jewelry-size',
                'type' => 'select',
                'sort_order' => 130,
            ],
            [
                'name' => 'Case Size',
                'slug' => 'case-size',
                'type' => 'select',
                'sort_order' => 140,
            ],
            [
                'name' => 'Band Material',
                'slug' => 'band-material',
                'type' => 'select',
                'sort_order' => 150,
            ],
            [
                'name' => 'Activity Type',
                'slug' => 'activity-type',
                'type' => 'select',
                'sort_order' => 160,
            ],
            [
                'name' => 'Compression Level',
                'slug' => 'compression-level',
                'type' => 'select',
                'sort_order' => 170,
            ],
        ];

        foreach ($attributes as $attr) {
            Attribute::updateOrCreate(
                ['slug' => $attr['slug']],
                $attr
            );
        }
    }
}