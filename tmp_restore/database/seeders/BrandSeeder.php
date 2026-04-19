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
            // === CORE GLOBAL BRANDS (featured) ===
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
                'is_featured' => true,
                'sort_order' => 30,
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

            // === CLOTHING BRANDS ===
            [
                'name' => 'Levi\'s',
                'slug' => Str::slug('Levi\'s'),
                'description' => 'Iconic American denim and casual clothing.',
                'logo_path' => 'brands/levis.png',
                'website_url' => 'https://www.levi.com',
                'is_featured' => false,
                'sort_order' => 40,
            ],
            [
                'name' => 'Gap',
                'slug' => Str::slug('Gap'),
                'description' => 'Classic American casual wear for the whole family.',
                'logo_path' => 'brands/gap.png',
                'website_url' => 'https://www.gap.com',
                'is_featured' => false,
                'sort_order' => 50,
            ],
            [
                'name' => 'Zara',
                'slug' => Str::slug('Zara'),
                'description' => 'Fast fashion with trendy styles for women, men & kids.',
                'logo_path' => 'brands/zara.png',
                'website_url' => 'https://www.zara.com',
                'is_featured' => true,
                'sort_order' => 60,
            ],
            [
                'name' => 'H&M',
                'slug' => Str::slug('H&M'),
                'description' => 'Affordable fashion for everyone.',
                'logo_path' => 'brands/hm.png',
                'website_url' => 'https://www.hm.com',
                'is_featured' => false,
                'sort_order' => 70,
            ],
            [
                'name' => 'Uniqlo',
                'slug' => Str::slug('Uniqlo'),
                'description' => 'Japanese functional and quality clothing.',
                'logo_path' => 'brands/uniqlo.png',
                'website_url' => 'https://www.uniqlo.com',
                'is_featured' => false,
                'sort_order' => 80,
            ],
            [
                'name' => 'Tommy Hilfiger',
                'slug' => Str::slug('Tommy Hilfiger'),
                'description' => 'Premium American lifestyle brand.',
                'logo_path' => 'brands/tommy-hilfiger.png',
                'website_url' => 'https://www.tommy.com',
                'is_featured' => false,
                'sort_order' => 90,
            ],
            [
                'name' => 'Calvin Klein',
                'slug' => Str::slug('Calvin Klein'),
                'description' => 'Modern underwear, denim and casual wear.',
                'logo_path' => 'brands/calvin-klein.png',
                'website_url' => 'https://www.calvinklein.com',
                'is_featured' => false,
                'sort_order' => 100,
            ],

            // === SHOES BRANDS ===
            [
                'name' => 'Converse',
                'slug' => Str::slug('Converse'),
                'description' => 'Iconic sneakers since 1908.',
                'logo_path' => 'brands/converse.png',
                'website_url' => 'https://www.converse.com',
                'is_featured' => true,
                'sort_order' => 110,
            ],
            [
                'name' => 'New Balance',
                'slug' => Str::slug('New Balance'),
                'description' => 'Premium athletic and lifestyle footwear.',
                'logo_path' => 'brands/new-balance.png',
                'website_url' => 'https://www.newbalance.com',
                'is_featured' => false,
                'sort_order' => 120,
            ],
            [
                'name' => 'Vans',
                'slug' => Str::slug('Vans'),
                'description' => 'Skate culture and casual shoes.',
                'logo_path' => 'brands/vans.png',
                'website_url' => 'https://www.vans.com',
                'is_featured' => false,
                'sort_order' => 130,
            ],
            [
                'name' => 'Dr. Martens',
                'slug' => Str::slug('Dr. Martens'),
                'description' => 'Iconic boots and footwear with attitude.',
                'logo_path' => 'brands/dr-martens.png',
                'website_url' => 'https://www.drmartens.com',
                'is_featured' => false,
                'sort_order' => 140,
            ],

            // === ACTIVEWEAR BRANDS ===
            [
                'name' => 'Lululemon',
                'slug' => Str::slug('Lululemon'),
                'description' => 'Premium yoga and activewear for women & men.',
                'logo_path' => 'brands/lululemon.png',
                'website_url' => 'https://www.lululemon.com',
                'is_featured' => true,
                'sort_order' => 150,
            ],
            [
                'name' => 'Gymshark',
                'slug' => Str::slug('Gymshark'),
                'description' => 'Performance gym and training apparel.',
                'logo_path' => 'brands/gymshark.png',
                'website_url' => 'https://www.gymshark.com',
                'is_featured' => false,
                'sort_order' => 160,
            ],
            [
                'name' => 'Under Armour',
                'slug' => Str::slug('Under Armour'),
                'description' => 'Performance apparel for athletes.',
                'logo_path' => 'brands/under-armour.png',
                'website_url' => 'https://www.underarmour.com',
                'is_featured' => false,
                'sort_order' => 170,
            ],
            [
                'name' => 'Reebok',
                'slug' => Str::slug('Reebok'),
                'description' => 'Classic fitness and lifestyle brand.',
                'logo_path' => 'brands/reebok.png',
                'website_url' => 'https://www.reebok.com',
                'is_featured' => false,
                'sort_order' => 180,
            ],

            // === ACCESSORIES & JEWELRY BRANDS ===
            [
                'name' => 'Ray-Ban',
                'slug' => Str::slug('Ray-Ban'),
                'description' => 'World-famous sunglasses and eyewear.',
                'logo_path' => 'brands/ray-ban.png',
                'website_url' => 'https://www.ray-ban.com',
                'is_featured' => true,
                'sort_order' => 190,
            ],
            [
                'name' => 'Michael Kors',
                'slug' => Str::slug('Michael Kors'),
                'description' => 'Luxury handbags, watches and accessories.',
                'logo_path' => 'brands/michael-kors.png',
                'website_url' => 'https://www.michaelkors.com',
                'is_featured' => false,
                'sort_order' => 200,
            ],
            [
                'name' => 'Fossil',
                'slug' => Str::slug('Fossil'),
                'description' => 'Stylish watches, bags and leather accessories.',
                'logo_path' => 'brands/fossil.png',
                'website_url' => 'https://www.fossil.com',
                'is_featured' => false,
                'sort_order' => 210,
            ],
            [
                'name' => 'Pandora',
                'slug' => Str::slug('Pandora'),
                'description' => 'Danish jewelry brand famous for charm bracelets.',
                'logo_path' => 'brands/pandora.png',
                'website_url' => 'https://www.pandora.net',
                'is_featured' => false,
                'sort_order' => 220,
            ],
            [
                'name' => 'Swarovski',
                'slug' => Str::slug('Swarovski'),
                'description' => 'Crystal jewelry and luxury accessories.',
                'logo_path' => 'brands/swarovski.png',
                'website_url' => 'https://www.swarovski.com',
                'is_featured' => false,
                'sort_order' => 230,
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