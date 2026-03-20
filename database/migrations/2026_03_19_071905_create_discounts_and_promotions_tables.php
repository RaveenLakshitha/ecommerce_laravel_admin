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
        // Coupons Table
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_per_user')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('applies_to', ['all', 'specific_products', 'specific_categories'])->default('all');
            $table->integer('used_count')->default(0);
            $table->timestamps();
        });

        // Coupon Product Pivot
        Schema::create('coupon_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Coupon Category Pivot
        Schema::create('coupon_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Coupon Usages
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code_used');
            $table->decimal('discount_amount', 10, 2);
            $table->dateTime('used_at');
            $table->timestamps();
        });

        // Discount Rules Table
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed', 'bogo', 'buy_x_get_y']);
            $table->decimal('value', 10, 2)->nullable();
            $table->integer('buy_quantity')->nullable();
            $table->integer('get_quantity')->nullable();
            $table->decimal('min_order_amount', 10, 2)->nullable();
            $table->enum('applies_to', ['all', 'products', 'categories', 'collections'])->default('all');
            $table->integer('priority')->default(0);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_flash_sale')->default(false);
            $table->timestamps();
        });

        // Discount Rule Product Pivot
        Schema::create('discount_rule_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_rule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Discount Rule Category Pivot
        Schema::create('discount_rule_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_rule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Discount Rule Collection Pivot
        Schema::create('discount_rule_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_rule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_rule_collection');
        Schema::dropIfExists('discount_rule_category');
        Schema::dropIfExists('discount_rule_product');
        Schema::dropIfExists('discount_rules');
        
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupon_category');
        Schema::dropIfExists('coupon_product');
        Schema::dropIfExists('coupons');
    }
};
