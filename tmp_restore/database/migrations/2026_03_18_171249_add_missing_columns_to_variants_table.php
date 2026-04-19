<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            $table->integer('reserved_quantity')->default(0)->after('stock_quantity');
            $table->integer('low_stock_threshold')->nullable()->after('reserved_quantity');
            $table->integer('weight_grams')->nullable()->after('low_stock_threshold');
            $table->string('dimensions')->nullable()->after('weight_grams');
            $table->string('barcode')->nullable()->after('dimensions');
            $table->boolean('is_default')->default(false)->after('barcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn([
                'sale_price',
                'reserved_quantity',
                'low_stock_threshold',
                'weight_grams',
                'dimensions',
                'barcode',
                'is_default',
            ]);
        });
    }
};
