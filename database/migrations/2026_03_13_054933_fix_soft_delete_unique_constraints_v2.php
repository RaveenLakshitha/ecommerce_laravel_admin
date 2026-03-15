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
        Schema::table('departments', function (Blueprint $table) {
            try { $table->dropUnique(['name']); } catch (\Exception $e) {}
            $table->index(['name']);
        });

        Schema::table('patients', function (Blueprint $table) {
            try { $table->dropUnique(['email']); } catch (\Exception $e) {}
            try { $table->dropUnique(['phone']); } catch (\Exception $e) {}
            $table->index(['email']);
            $table->index(['phone']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            try { $table->dropUnique(['email']); } catch (\Exception $e) {}
            try { $table->dropUnique(['license_number']); } catch (\Exception $e) {}
            $table->index(['email']);
            $table->index(['license_number']);
        });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->unique(['name']);
        });

        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
            $table->unique(['email']);
            $table->unique(['phone']);
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['license_number']);
            $table->unique(['email']);
            $table->unique(['license_number']);
        });
    }
};
