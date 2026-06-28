<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'admin_login_bg')) {
                $table->text('admin_login_bg')->nullable()->after('site_favicon');
            }
        });
    }
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'admin_login_bg')) {
                $table->dropColumn('admin_login_bg');
            }
        });
    }
};
