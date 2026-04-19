<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\DiscountRule;

class CollectionDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a couple of Collections
        $summerCollection = Collection::firstOrCreate(
            ['slug' => 'summer-collection-2026'],
            [
                'name' => 'Summer Collection 2026',
                'description' => 'Get ready for the summer with our exclusive items.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $winterCollection = Collection::firstOrCreate(
            ['slug' => 'winter-exclusives'],
            [
                'name' => 'Winter Exclusives',
                'description' => 'Stay warm with our winter collection.',
                'is_active' => true,
                'is_featured' => false,
            ]
        );

        // 2. Attach some products if they exist
        $products = Product::take(5)->get();
        if ($products->count() > 0) {
            $summerCollection->products()->syncWithoutDetaching($products->take(3)->pluck('id'));
            $winterCollection->products()->syncWithoutDetaching($products->skip(3)->take(2)->pluck('id'));
        } else {
            // Create some dummy products if database is empty
            $prod1 = Product::create([
                'name' => 'Summer T-Shirt',
                'slug' => 'summer-tshirt-1',
                'base_price' => 25.00,
                'is_visible' => true,
            ]);
            $prod2 = Product::create([
                'name' => 'Winter Jacket',
                'slug' => 'winter-jacket-1',
                'base_price' => 120.00,
                'is_visible' => true,
            ]);
            $summerCollection->products()->syncWithoutDetaching([$prod1->id]);
            $winterCollection->products()->syncWithoutDetaching([$prod2->id]);
        }

        // 3. Create a Coupon for specific_collections
        $coupon = Coupon::firstOrCreate(
            ['code' => 'SUMMER20'],
            [
                'description' => '20% off Summer Collection',
                'type' => 'percentage',
                'value' => 20,
                'applies_to' => 'specific_collections',
                'is_active' => true,
            ]
        );
        $coupon->collections()->syncWithoutDetaching([$summerCollection->id]);

        // 4. Create a Discount Rule for collections
        $discountRule = DiscountRule::firstOrCreate(
            ['name' => 'Winter Clearance'],
            [
                'description' => 'Flat $50 off on our winter collection orders',
                'type' => 'fixed',
                'value' => 50.00,
                'applies_to' => 'collections',
                'priority' => 10,
                'is_active' => true,
            ]
        );
        $discountRule->collections()->syncWithoutDetaching([$winterCollection->id]);
    }
}
