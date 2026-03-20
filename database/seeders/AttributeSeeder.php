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
        ];

        foreach ($attributes as $attr) {
            Attribute::updateOrCreate(
                ['slug' => $attr['slug']],
                $attr
            );
        }
    }
}