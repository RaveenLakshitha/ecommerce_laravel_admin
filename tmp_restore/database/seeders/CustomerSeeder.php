<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 15; $i++) {
            $customer = \App\Models\Customer::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'status' => 'active',
                'total_orders' => rand(0, 10),
                'total_spent' => rand(0, 1000) + mt_rand(0, 99) / 100,
                'last_order_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                'lifetime_value' => rand(0, 1500),
            ]);
            
            // Random Tagging for VIP/Problematic, etc.
            if (rand(1, 10) > 7) {
                // Ensure VIP tag exists
                $vipTag = \App\Models\Tag::firstOrCreate(['slug' => 'vip'], ['name' => 'VIP']);
                $customer->tags()->attach($vipTag->id);
            }
        }
    }
}
