<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('website_url')->nullable()->after('logo_url');
            $table->boolean('is_featured')->default(false)->after('website_url');
            $table->integer('sort_order')->default(0)->after('is_featured');
            $table->renameColumn('logo_url', 'logo_path');

        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn([
                'website_url',
                'is_featured',
                'sort_order',
            ]);

            $table->renameColumn('logo_path', 'logo_url');
        });
    }
};