<?php
$_SERVER['DOCUMENT_ROOT'] = __DIR__;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$v = \App\Models\Variant::with('attributeValues.attribute')->first();
file_put_contents('out.json', json_encode($v, JSON_PRETTY_PRINT));
