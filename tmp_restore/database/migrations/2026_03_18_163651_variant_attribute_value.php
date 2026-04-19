<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('variant_attribute_value', function (Blueprint $table) {
            $table->id(); // optional but useful for debugging / extra pivot data later

            $table->foreignId('variant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attribute_value_id')->constrained()->cascadeOnDelete();

            $table->timestamps(); // optional - sometimes useful to know when combination was added

            $table->unique(['variant_id', 'attribute_value_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_attribute_value');
    }
};