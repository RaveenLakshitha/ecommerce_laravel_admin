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
        Schema::table('collections', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('banner_url');
            $table->date('end_date')->nullable()->after('start_date');
            $table->integer('sort_order')->default(0)->after('is_featured');
            $table->string('meta_title')->nullable()->after('sort_order');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date', 'sort_order', 'meta_title', 'meta_description']);
        });
    }
};
