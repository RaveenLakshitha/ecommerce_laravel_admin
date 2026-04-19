<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Variant;

class WipeAndReseed extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        
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

        $this->call([
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            RealisticProductSeeder::class,
        ]);
        
        $this->command->info('WipeAndReseed Complete!');
    }
}
