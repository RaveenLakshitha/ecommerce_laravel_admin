<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Women' => [
                "Women's Clothing" => [
                    'Blazers',
                    'Bras, Underwear & Lingerie',
                    'Coats & Jackets',
                    'Dresses',
                    'Hoodies & Sweatshirts',
                    'Jeans & Denim',
                    'Jumpsuits & Rompers',
                    'Pajamas & Robes',
                    'Pants & Leggings',
                    'Shorts',
                    'Skirts',
                    'Suits & Suit Separates',
                    'Sweaters',
                    'Swimsuits & Cover-Ups',
                    'Tops',
                ],
                'More Sizes' => [
                    'Plus Sizes',
                    'Petites (5\'4" and under)',
                    'Juniors & Young Adult',
                    'Maternity',
                ],
                'Dresses by Occasion' => [
                    'Formal Dresses',
                    'Cocktail & Party Dresses',
                    'Wedding Guest Dresses',
                    'Prom Dresses',
                    'Casual Dresses',
                ],
            ],

            'Men' => [
                "Men's Clothing" => [
                    'Blazers & Sportcoats',
                    'Coats & Jackets',
                    'Dress Shirts',
                    'Hoodies & Sweatshirts',
                    'Jeans & Denim',
                    'Pajamas & Robes',
                    'Pants',
                    'Polo Shirts',
                    'Shirts & T-shirts',
                    'Shorts',
                    'Suits & Tuxedos',
                    'Sweaters',
                    'Swimwear',
                    'Underwear & Socks',
                    'Big & Tall',
                ],
            ],

            'Kids' => [
                'Girls' => [
                    'Tops, Hoodies & Sweatshirts',
                    'Bottoms',
                    'Dresses',
                    'Outerwear',
                    'Activewear',
                    'Swimwear',
                    'Underwear & Sleepwear',
                    'Sets & Outfits',
                    'School Uniforms'
                ],
                'Boys' => [
                    'Tops, Hoodies & Sweatshirts',
                    'Bottoms',
                    'Outerwear',
                    'Activewear',
                    'Swimwear',
                    'Underwear & Sleepwear',
                    'Sets & Outfits',
                    'School Uniforms'
                ],
                'Baby & Newborn' => [
                    'Bodysuits & Onesies',
                    'Tops',
                    'Bottoms',
                    'Sets & Outfits',
                    'Sleepwear'
                ]
            ],

            // 🔥 NEW MAIN CATEGORIES (exactly as you asked)
            'Shoes' => [
                'Women’s Shoes' => [
                    'Athletic Shoes & Sneakers',
                    'Casual Shoes',
                    'Dress Shoes',
                    'Loafers & Drivers',
                    'Sandals',
                    'Boots',
                    'Flats',
                    'High Heels',
                    'Wedges'
                ],
                'Men’s Shoes' => [
                    'Athletic Shoes & Sneakers',
                    'Casual Shoes',
                    'Dress Shoes',
                    'Loafers & Drivers',
                    'Sandals',
                ],
                'Kids’ Shoes' => [
                    'Sneakers',
                    'Boots',
                    'Sandals',
                    'School Shoes',
                    'Athletic Shoes',
                    'Slippers'
                ],
            ],

            'Accessories' => [
                'Jewelry' => ['Necklaces', 'Earrings', 'Rings', 'Bracelets'],
                'Bags & Wallets' => ['Handbags', 'Tote Bags', 'Crossbody Bags', 'Clutches', 'Backpacks', 'Wallets'],
                'Hats & Caps' => ['Beanies', 'Baseball Caps', 'Bucket Hats', 'Sun Hats'],
                'Belts & Suspenders' => ['Belts', 'Suspenders'],
                'Scarves & Gloves' => ['Scarves', 'Gloves'],
                'Sunglasses' => ['Aviator', 'Wayfarer', 'Round', 'Oversized'],
                'Watches' => ['Women’s Watches', 'Men’s Watches', 'Smart Watches'],
                'Ties & Pocket Squares' => ['Ties', 'Bow Ties', 'Pocket Squares'],
            ],

            'Activewear' => [
                'Women’s Activewear' => [
                    'Leggings',
                    'Sports Bras',
                    'Tank Tops',
                    'Hoodies & Sweatshirts',
                    'Jackets',
                    'Shorts',
                    'Tracksuits',
                    'Yoga Pants'
                ],
                'Men’s Activewear' => [
                    'T-Shirts & Tanks',
                    'Hoodies & Sweatshirts',
                    'Shorts',
                    'Joggers',
                    'Jackets',
                    'Tracksuits'
                ],
                'Kids’ Activewear' => [
                    'Tops',
                    'Bottoms',
                    'Hoodies',
                    'Sets & Outfits',
                    'Jackets'
                ],
            ],
        ];

        $order = 10;
        foreach ($categories as $parentName => $subData) {
            $parent = Category::updateOrCreate(
                ['slug' => Str::slug($parentName)],
                ['name' => $parentName, 'sort_order' => $order, 'is_active' => true]
            );
            $order += 10;

            $subOrder = 10;
            if (is_array($subData) && !empty($subData)) {
                // All categories now use 3-level structure (exactly like Kids)
                if (array_keys($subData) !== range(0, count($subData) - 1)) {
                    foreach ($subData as $subName => $childData) {
                        $sub = Category::updateOrCreate(
                            ['slug' => Str::slug($subName)],
                            ['name' => $subName, 'parent_id' => $parent->id, 'sort_order' => $subOrder, 'is_active' => true]
                        );
                        $subOrder += 10;

                        $childOrder = 10;
                        foreach ($childData as $childName) {
                            $childSlug = Str::slug($subName . ' ' . $childName);
                            Category::updateOrCreate(
                                ['slug' => $childSlug],
                                ['name' => $childName, 'parent_id' => $sub->id, 'sort_order' => $childOrder, 'is_active' => true]
                            );
                            $childOrder += 10;
                        }
                    }
                }
            }
        }

        $this->command->info('Categories seeded successfully! Shoes, Accessories & Activewear are now main top-level categories.');
    }
}