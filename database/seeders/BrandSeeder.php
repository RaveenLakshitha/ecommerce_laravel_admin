<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Nike',
                'slug' => Str::slug('Nike'),
                'description' => 'Global leader in athletic footwear, apparel, and equipment.',
                'logo_path' => 'brands/nike.png',
                'website_url' => 'https://www.nike.com',
                'is_featured' => true,
                'sort_order' => 10,
            ],
            [
                'name' => 'Adidas',
                'slug' => Str::slug('Adidas'),
                'description' => 'German sportswear brand known for the three stripes.',
                'logo_path' => 'brands/adidas.png',
                'website_url' => 'https://www.adidas.com',
                'is_featured' => true,
                'sort_order' => 20,
            ],
            [
                'name' => 'Puma',
                'slug' => Str::slug('Puma'),
                'description' => 'Fast & furious – sport and street fashion.',
                'logo_path' => 'brands/puma.png',
                'website_url' => 'https://us.puma.com',
                'is_featured' => false,
                'sort_order' => 30,
            ],
            [
                'name' => 'Under Armour',
                'slug' => Str::slug('Under Armour'),
                'description' => 'Performance apparel for athletes.',
                'logo_path' => 'brands/under-armour.png',
                'is_featured' => false,
                'sort_order' => 40,
            ],
            [
                'name' => 'Local Threads',
                'slug' => Str::slug('Local Threads'),
                'description' => 'Sri Lankan streetwear & casual brand.',
                'logo_path' => 'brands/local-threads.png',
                'website_url' => 'https://example.com/local-threads',
                'is_featured' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($brands as $data) {
            Brand::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}