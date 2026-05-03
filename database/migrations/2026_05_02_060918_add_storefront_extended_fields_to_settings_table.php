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
            if (!Schema::hasColumn('settings', 'storefront_stats')) {
                $table->json('storefront_stats')->nullable()->after('storefront_our_story_image');
            }
            if (!Schema::hasColumn('settings', 'storefront_trust_items')) {
                $table->json('storefront_trust_items')->nullable()->after('storefront_stats');
            }
            if (!Schema::hasColumn('settings', 'storefront_logo_text')) {
                $table->string('storefront_logo_text')->nullable()->after('storefront_trust_items');
            }
            if (!Schema::hasColumn('settings', 'storefront_logo_subtext')) {
                $table->string('storefront_logo_subtext')->nullable()->after('storefront_logo_text');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['storefront_stats', 'storefront_trust_items', 'storefront_logo_text', 'storefront_logo_subtext']);
        });
    }
};
