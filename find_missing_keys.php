<?php
$content = file_get_contents('app/Http/Controllers/AppointmentController.php');
preg_match_all("/__\(\s*['\"]file\.([^'\"]+)['\"]\s*\)/", $content, $matches);
$keys = array_unique($matches[1]);

$enContent = file_get_contents('resources/lang/en/file.php');
$esContent = file_get_contents('resources/lang/es/file.php');

echo "Missing in EN:\n";
foreach ($keys as $key) {
    if (strpos($enContent, "'$key'") === false && strpos($enContent, "\"$key\"") === false) {
        echo "- $key\n";
    }
}

echo "\nMissing in ES:\n";
foreach ($keys as $key) {
    if (strpos($esContent, "'$key'") === false && strpos($esContent, "\"$key\"") === false) {
        echo "- $key\n";
    }
}
