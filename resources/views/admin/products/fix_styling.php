<?php

$files = [
    __DIR__ . '/create.blade.php',
    __DIR__ . '/edit.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        
        // Change colors from indigo to blue
        $content = str_replace('indigo', 'blue', $content);
        
        // Standardize border radius classes from 2xl/lg to xl to match show.blade.php
        $content = str_replace('rounded-lg', 'rounded-xl', $content);
        $content = str_replace('rounded-2xl', 'rounded-xl', $content);
        
        // Standardize font size for the default variant badge as per show page (text-[10px] -> text-xs, font-medium -> font-semibold)
        // Edit page already uses text-xs for variant badges but maybe fine-tuning is needed
        
        file_put_contents($file, $content);
        echo "Processed " . basename($file) . "\n";
    }
}
