<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = [
    'departments' => ['name'],
    'specializations' => ['name'],
    'patients' => ['email', 'phone'],
    'doctors' => ['email', 'license_number'],
    'age_groups' => ['name'],
    'leave_types' => ['name', 'code'],
    'rooms' => ['name', 'room_number'],
    'categories' => ['name'],
    'services' => ['name'],
    'inventory_items' => ['sku'],
    'unit_of_measures' => ['name'],
];

foreach ($tables as $table => $columns) {
    if (!Schema::hasTable($table)) {
        echo "Table $table does not exist. Skipping.\n";
        continue;
    }

    foreach ($columns as $column) {
        $indexName = "{$table}_{$column}_unique";
        
        // Check if index exists
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        if (count($indexes) > 0) {
            try {
                DB::statement("ALTER TABLE {$table} DROP INDEX {$indexName}");
                echo "Dropped unique index $indexName on $table.\n";
            } catch (\Exception $e) {
                echo "Failed to drop unique index $indexName on $table: " . $e->getMessage() . "\n";
            }
        } else {
            // Try dropping by name in case of non-standard naming
            try {
                // We'll only do this if the standard name search failed
                // and we're sure there is a unique index on that column.
                // But for now, let's just stick to standard names.
            } catch (\Exception $e) {}
        }

        // Add regular index if it doesn't exist
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", ["{$table}_{$column}_index"]);
        if (count($indexes) == 0) {
            try {
                DB::statement("ALTER TABLE {$table} ADD INDEX ({$column})");
                echo "Added regular index on $column in $table.\n";
            } catch (\Exception $e) {
                // Index might already exist with another name
            }
        }
    }
}

echo "Cleanup complete.\n";
