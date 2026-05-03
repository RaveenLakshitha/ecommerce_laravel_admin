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
            if (!Schema::hasColumn('settings', 'storefront_our_story_title')) {
                $table->string('storefront_our_story_title')->nullable()->after('storefront_about_us_content');
            }
            if (!Schema::hasColumn('settings', 'storefront_our_story_content')) {
                $table->text('storefront_our_story_content')->nullable()->after('storefront_our_story_title');
            }
            if (!Schema::hasColumn('settings', 'storefront_our_story_image')) {
                $table->string('storefront_our_story_image')->nullable()->after('storefront_our_story_content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'storefront_our_story_title')) {
                $table->dropColumn('storefront_our_story_title');
            }
            if (Schema::hasColumn('settings', 'storefront_our_story_content')) {
                $table->dropColumn('storefront_our_story_content');
            }
            if (Schema::hasColumn('settings', 'storefront_our_story_image')) {
                $table->dropColumn('storefront_our_story_image');
            }
        });
    }
};
