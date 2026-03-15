<?php
$en = include __DIR__ . '/resources/lang/en/file.php';
$es = include __DIR__ . '/resources/lang/es/file.php';

$missing = [];
foreach ($en as $key => $enVal) {
    if (isset($es[$key]) && $es[$key] === $enVal) {
        $missing[$key] = $enVal;
    }
}

file_put_contents('missing_es_keys.json', json_encode($missing, JSON_PRETTY_PRINT));
echo "Found " . count($missing) . " keys to translate.\n";
