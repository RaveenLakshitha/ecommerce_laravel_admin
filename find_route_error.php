<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Artisan::call('route:list');
    echo "No error!\n";
} catch (Throwable $e) {
    echo "ERROR CAUGHT!\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . ':' . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
