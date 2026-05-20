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
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'storefront_size_guide_show')) {
                $table->boolean('storefront_size_guide_show')->default(true);
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_title')) {
                $table->text('storefront_size_guide_title')->nullable();
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_headers')) {
                $table->json('storefront_size_guide_headers')->nullable();
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_rows')) {
                $table->json('storefront_size_guide_rows')->nullable();
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_note')) {
                $table->text('storefront_size_guide_note')->nullable();
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_bust_desc')) {
                $table->text('storefront_size_guide_bust_desc')->nullable();
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_waist_desc')) {
                $table->text('storefront_size_guide_waist_desc')->nullable();
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_hip_desc')) {
                $table->text('storefront_size_guide_hip_desc')->nullable();
            }
            if (!Schema::hasColumn('settings', 'storefront_size_guide_fit_note')) {
                $table->text('storefront_size_guide_fit_note')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'storefront_size_guide_show',
                'storefront_size_guide_title',
                'storefront_size_guide_headers',
                'storefront_size_guide_rows',
                'storefront_size_guide_note',
                'storefront_size_guide_bust_desc',
                'storefront_size_guide_waist_desc',
                'storefront_size_guide_hip_desc',
                'storefront_size_guide_fit_note',
            ]);
        });
    }
};
