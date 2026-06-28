<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('brand_id')->constrained()->onDelete('set null');
        });
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            $category = DB::table('category_product')
                ->where('product_id', $product->id)
                ->orderBy('id')
                ->first();
            if ($category) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['category_id' => $category->category_id]);
            }
        }
    }
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
