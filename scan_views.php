<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('e:/Program Files/xampp/htdocs/test/appointmentsystem/docappointment - Copy/resources/views'));

$results = [];

foreach ($dir as $file) {
    if (strpos($file->getPathname(), '.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        
        $hasEditOrDelete = preg_match('/class="[^"]*(btn|dropdown-item)[^"]*".*?(edit|delete|destroy|fa-pen|fa-edit|fa-trash|bi-pencil|bi-trash)/is', $content) ||
                           preg_match('/(edit|delete|destroy).*?class="[^"]*(btn|dropdown-item)[^"]*"/is', $content);
                           
        if ($hasEditOrDelete) {
            $results[] = (string)$file->getPathname();
        }
    }
}

file_put_contents('e:/Program Files/xampp/htdocs/test/appointmentsystem/docappointment - Copy/scan_results.txt', implode("\n", $results));
