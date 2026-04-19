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
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_name')->nullable();
                $table->string('site_id')->nullable();
                $table->string('address')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('website')->nullable();
                $table->string('tax_id')->nullable();
                $table->string('timezone')->nullable();
                $table->string('date_format')->nullable();
                $table->string('time_format')->nullable();
                $table->string('first_day_of_week')->nullable();
                $table->string('language')->nullable();
                $table->string('logo_path')->nullable();
                $table->string('primary_color')->nullable();
                $table->string('secondary_color')->nullable();
                $table->string('currency')->nullable();
                $table->json('storefront_banners')->nullable();
                $table->string('storefront_offer_text')->nullable();
                $table->string('storefront_offer_link')->nullable();
                $table->text('storefront_about_us')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('settings', function (Blueprint $table) {
                $table->json('storefront_banners')->nullable();
                $table->string('storefront_offer_text')->nullable();
                $table->string('storefront_offer_link')->nullable();
                $table->text('storefront_about_us')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn([
                    'storefront_banners',
                    'storefront_offer_text',
                    'storefront_offer_link',
                    'storefront_about_us',
                ]);
            });
        }
    }
};
