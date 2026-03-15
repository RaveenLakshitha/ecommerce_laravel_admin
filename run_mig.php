<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Artisan::call('migrate');
    echo \Artisan::output();
} catch (\Exception $e) {
    echo $e->getMessage();
}
