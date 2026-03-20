<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 20; $i++) {
            \App\Models\Subscriber::create([
                'email' => $faker->unique()->safeEmail(),
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'status' => $faker->randomElement(['subscribed', 'subscribed', 'subscribed', 'unsubscribed', 'bounced']),
                'source' => $faker->randomElement(['checkout', 'popup', 'footer']),
                'subscribed_at' => now()->subDays(rand(1, 100)),
            ]);
        }
    }
}
