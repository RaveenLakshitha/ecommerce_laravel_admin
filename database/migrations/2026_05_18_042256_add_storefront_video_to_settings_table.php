<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'storefront_video_url')) {
                $table->string('storefront_video_url')->nullable()->after('storefront_use_logo_text');
            }
            if (!Schema::hasColumn('settings', 'storefront_video_title')) {
                $table->string('storefront_video_title')->nullable()->after('storefront_video_url');
            }
            if (!Schema::hasColumn('settings', 'storefront_video_subtitle')) {
                $table->string('storefront_video_subtitle')->nullable()->after('storefront_video_title');
            }
            if (!Schema::hasColumn('settings', 'storefront_video_show')) {
                $table->boolean('storefront_video_show')->default(true)->after('storefront_video_subtitle');
            }
        });
    }
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['storefront_video_url', 'storefront_video_title', 'storefront_video_subtitle', 'storefront_video_show']);
        });
    }
};
