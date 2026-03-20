<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->cascadeOnDelete();
            $table->string('value');                   // "Red", "M", "Cotton", "Slim Fit"
            $table->string('slug')->index();
            $table->string('color_hex')->nullable();   // #FF0000
            $table->string('swatch_image')->nullable(); // future-proof for image swatches
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['attribute_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};