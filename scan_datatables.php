<?php
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('e:/Program Files/xampp/htdocs/test/appointmentsystem/docappointment - Copy/'));

$results = [];
foreach ($dir as $file) {
    $path = $file->getPathname();
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if ($ext === 'php') {
        // Only look in app/Http/Controllers or resources/views
        if (strpos($path, 'app\Http\Controllers') !== false || strpos($path, 'resources\views') !== false) {
            $content = file_get_contents($path);
            if (
                strpos($content, 'edit_url') !== false ||
                strpos($content, 'delete_url') !== false ||
                strpos($content, 'confirmDelete') !== false ||
                strpos($content, 'Datatables::') !== false ||
                strpos($content, 'class="btn ') !== false ||
                strpos($content, 'class="dropdown-item') !== false
            ) {
                // To narrow down, check if it contains edit, delete or destroy
                if (preg_match('/(edit|delete|destroy|fa-pen|fa-edit|fa-trash|bi-pencil|bi-trash)/is', $content)) {
                    $results[] = (string)$path;
                }
            }
        }
    }
}
file_put_contents('e:/Program Files/xampp/htdocs/test/appointmentsystem/docappointment - Copy/actions_scan.txt', implode("\n", $results));
