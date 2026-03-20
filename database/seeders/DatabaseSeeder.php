<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  use WithoutModelEvents;

  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
        // RolesAndPermissionsSeeder::class,
        // AdminSeeder::class,
        // UserSeeder::class,
        // AttributeSeeder::class,
        //AttributeValueSeeder::class,
        //  ProductVariantSeeder::class,
        //  CategorySeeder::class,
        // BrandSeeder::class,
      RealisticProductSeeder::class,
      CustomerSeeder::class,
      SubscriberSeeder::class,
      ReviewSeeder::class,

    ]);

    // User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);
  }
}
