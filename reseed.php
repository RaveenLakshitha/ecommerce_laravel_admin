<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Variant;
use Illuminate\Support\Facades\Artisan;

Schema::disableForeignKeyConstraints();

// Truncate tables correctly
DB::table('variant_attribute_value')->truncate();
DB::table('category_product')->truncate();
try { DB::table('product_images')->truncate(); } catch(\Exception $e) {}

Variant::truncate();
Product::truncate();
Category::truncate();
Brand::truncate();
AttributeValue::truncate();
Attribute::truncate();

Schema::enableForeignKeyConstraints();

$seeders = [
    'AttributeSeeder',
    'AttributeValueSeeder',
    'CategorySeeder',
    'BrandSeeder',
    'RealisticProductSeeder'
];

foreach ($seeders as $seeder) {
    echo "Running $seeder...\n";
    Artisan::call('db:seed', ['--class' => $seeder]);
    echo Artisan::output();
}

echo "All specified tables truncated and reseeded successfully.\n";
