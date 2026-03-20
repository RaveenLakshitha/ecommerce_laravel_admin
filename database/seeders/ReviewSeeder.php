<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        $products = \App\Models\Product::pluck('id')->toArray();
        $customers = \App\Models\Customer::pluck('id')->toArray();

        // We only create if products and customers exist
        if (empty($products) || empty($customers)) {
            return;
        }

        for ($i = 0; $i < 30; $i++) {
            \App\Models\Review::create([
                'customer_id'   => $faker->randomElement($customers),
                'product_id'    => $faker->randomElement($products),
                'rating'        => rand(1, 5),
                'title'         => $faker->sentence(),
                'content'       => $faker->paragraph(),
                'status'        => $faker->randomElement(['pending', 'approved', 'rejected']),
                'is_anonymous'  => rand(0, 1),
                'helpful_count' => rand(0, 20),
            ]);
        }
    }
}
