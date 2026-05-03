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
            $table->boolean('storefront_our_story_show')->default(true)->after('storefront_our_story_image');
            $table->boolean('storefront_stats_show')->default(true)->after('storefront_stats');
            $table->boolean('storefront_trust_show')->default(true)->after('storefront_trust_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['storefront_our_story_show', 'storefront_stats_show', 'storefront_trust_show']);
        });
    }
};
