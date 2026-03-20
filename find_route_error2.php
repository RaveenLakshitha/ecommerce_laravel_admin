<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Artisan::call('route:list');
    file_put_contents('error_trace2.txt', "No error!");
} catch (Throwable $e) {
    $out = "ERROR CAUGHT!\n";
    $out .= $e->getMessage() . "\n";
    $out .= $e->getFile() . ':' . $e->getLine() . "\n";
    $out .= $e->getTraceAsString();
    file_put_contents('error_trace2.txt', $out);
}
