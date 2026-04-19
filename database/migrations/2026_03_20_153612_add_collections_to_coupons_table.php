<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE coupons MODIFY COLUMN applies_to ENUM('all', 'specific_products', 'specific_categories', 'specific_collections') DEFAULT 'all'");

        Schema::create('coupon_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_collection');
        
        DB::statement("ALTER TABLE coupons MODIFY COLUMN applies_to ENUM('all', 'specific_products', 'specific_categories') DEFAULT 'all'");
    }
};
