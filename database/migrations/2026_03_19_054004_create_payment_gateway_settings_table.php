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
        Schema::create('payment_gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->string('gateway')->unique(); // stripe, payhere, paypal
            $table->boolean('is_active')->default(false);
            $table->string('environment')->default('sandbox');
            $table->text('public_key')->nullable();
            $table->text('secret_key')->nullable();
            $table->string('merchant_id')->nullable();
            $table->json('additional_config')->nullable();
            $table->decimal('minimum_amount', 12, 2)->nullable();
            $table->decimal('maximum_amount', 12, 2)->nullable();
            $table->json('supported_currencies')->nullable();
            $table->string('logo')->nullable();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_settings');
    }
};
