<?php
$dir = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $originalContent = $content;
        
        // 1. Replace {{ $currency_symbol }} {{ number_format($variable, 2) }} -> @price($variable)
        $content = preg_replace(
            '/\{\{\s*\$currency_symbol\s*\}\}\s*(?:&nbsp;)?\s*\{\{\s*number_format\(([^,]+),\s*2\)\s*\}\}/',
            '@price($1)',
            $content
        );
        $content = preg_replace(
            '/\{\{\s*\$currency_symbol\s*\}\}\s*(?:&nbsp;)?\s*\<span[^>]*\>\{\{\s*number_format\(([^,]+),\s*2\)\s*\}\}\<\/span\>/',
            '@price($1)',
            $content
        );

        // 2. Replace {{ $order->currency }} {{ number_format($variable, 2) }} -> @price($variable, $order->currency)
        $content = preg_replace(
            '/\{\{\s*\$([a-zA-Z0-9_]+)->currency(?:\s*\?\?\s*\'[^\']+\')?\s*\}\}\s*\{\{\s*number_format\(([^,]+),\s*2\)\s*\}\}/',
            '@price($2, $$1->currency)',
            $content
        );

        // 3. Replace Rs. {{ number_format(...) }} LKR -> @price(...)
        $content = preg_replace(
            '/Rs\.\s*\{\{\s*number_format\(([^,]+),\s*2\)\s*\}\}(?:\s*LKR)?/',
            '@price($1)',
            $content
        );

        // 4. Replace ${{ number_format(...) }} -> @price(...)
        $content = preg_replace(
            '/\$\{\{\s*number_format\(([^,]+),\s*2\)\s*\}\}/',
            '@price($1)',
            $content
        );
        
        // 5. Replace {{ $currency_symbol }} <span ...>{{ number_format(...) }}</span>
        $content = preg_replace(
            '/\{\{\s*\$currency_symbol\s*\}\}\s*(?:&nbsp;)?\s*\<span[^>]*\>\{\{\s*number_format\(([^,]+),\s*2\)\s*\}\}\<\/span\>/',
            '@price($1)',
            $content
        );

        if ($content !== $originalContent) {
            file_put_contents($file->getRealPath(), $content);
            echo "Updated: " . $file->getRealPath() . "\n";
        }
    }
}
echo "Refactoring complete.\n";
