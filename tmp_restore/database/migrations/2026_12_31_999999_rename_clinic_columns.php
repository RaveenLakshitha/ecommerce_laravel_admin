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
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (Schema::hasColumn('settings', 'clinic_name')) {
                    $table->renameColumn('clinic_name', 'site_name');
                }
                if (Schema::hasColumn('settings', 'clinic_logo')) {
                    $table->renameColumn('clinic_logo', 'site_logo');
                }
                if (Schema::hasColumn('settings', 'clinic_address')) {
                    $table->renameColumn('clinic_address', 'site_address');
                }
                if (Schema::hasColumn('settings', 'clinic_phone')) {
                    $table->renameColumn('clinic_phone', 'site_phone');
                }
                if (Schema::hasColumn('settings', 'clinic_email')) {
                    $table->renameColumn('clinic_email', 'site_email');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->renameColumn('site_name', 'clinic_name');
            $table->renameColumn('site_logo', 'clinic_logo');
            $table->renameColumn('site_address', 'clinic_address');
            $table->renameColumn('site_phone', 'clinic_phone');
            $table->renameColumn('site_email', 'clinic_email');
        });
    }
};
