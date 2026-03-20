<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Top-level categories
        $men = Category::create([
            'name' => 'Men',
            'slug' => 'men',
            'description' => 'Men’s clothing and accessories',
            'sort_order' => 10,
        ]);

        $women = Category::create([
            'name' => 'Women',
            'slug' => 'women',
            'description' => 'Women’s fashion and apparel',
            'sort_order' => 20,
        ]);

        // Sub-categories
        Category::create([
            'name' => 'T-Shirts',
            'slug' => 't-shirts',
            'parent_id' => $men->id,
            'sort_order' => 10,
        ]);

        Category::create([
            'name' => 'Jeans',
            'slug' => 'jeans',
            'parent_id' => $men->id,
            'sort_order' => 20,
        ]);

        Category::create([
            'name' => 'Hoodies & Sweatshirts',
            'slug' => 'hoodies',
            'parent_id' => $men->id,
            'sort_order' => 30,
        ]);

        Category::create([
            'name' => 'Tops',
            'slug' => 'tops',
            'parent_id' => $women->id,
            'sort_order' => 10,
        ]);

        Category::create([
            'name' => 'Dresses',
            'slug' => 'dresses',
            'parent_id' => $women->id,
            'sort_order' => 20,
        ]);

        $this->command->info('Categories seeded (with some hierarchy).');
    }
}