<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    Illuminate\Support\Facades\Schema::table('patients', function ($table) {
        if (!Illuminate\Support\Facades\Schema::hasColumn('patients', 'document')) {
            $table->string('document')->nullable();
            echo "Added document column.\n";
        } else {
            echo "Column already exists.\n";
        }
    });
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
