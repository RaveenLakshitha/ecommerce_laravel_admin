<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'storefront_delivery_show')) {
                $table->boolean('storefront_delivery_show')->default(true)->after('storefront_video_show');
            }
            if (!Schema::hasColumn('settings', 'storefront_delivery_items')) {
                $table->json('storefront_delivery_items')->nullable()->after('storefront_delivery_show');
            }
            if (!Schema::hasColumn('settings', 'storefront_measure_show')) {
                $table->boolean('storefront_measure_show')->default(true)->after('storefront_delivery_items');
            }
            if (!Schema::hasColumn('settings', 'storefront_measure_note')) {
                $table->string('storefront_measure_note')->nullable()->after('storefront_measure_show');
            }
            if (!Schema::hasColumn('settings', 'storefront_measure_items')) {
                $table->json('storefront_measure_items')->nullable()->after('storefront_measure_note');
            }
        });
    }
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'storefront_delivery_show',
                'storefront_delivery_items',
                'storefront_measure_show',
                'storefront_measure_note',
                'storefront_measure_items'
            ]);
        });
    }
};
