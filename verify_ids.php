<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Doctor;

$u = User::find(8);
if ($u) {
    echo "USER 8: Name: '{$u->name}', Email: '{$u->email}'\n";
    $d = $u->doctor;
    echo "USER 8 LINKED DOCTOR: " . ($d ? "ID: {$d->id}, Name: {$d->first_name} {$d->last_name}" : "NONE") . "\n";
} else {
    echo "USER 8 NOT FOUND\n";
}

$d43 = Doctor::find(43);
if ($d43) {
    echo "DOCTOR 43: Name: '{$d43->first_name} {$d43->last_name}', Email: '{$d43->email}', UserID: '{$d43->user_id}'\n";
} else {
    echo "DOCTOR 43 NOT FOUND\n";
}

// Search for any doctor matching User 8's name or email
$d_by_email = Doctor::where('email', $u->email)->first();
echo "DOCTOR BY EMAIL: " . ($d_by_email ? "ID: {$d_by_email->id}" : "NONE") . "\n";

$name_clean = str_replace('Dr. ', '', $u->name);
$d_by_name = Doctor::whereRaw("CONCAT(first_name, ' ', COALESCE(middle_name,''), ' ', last_name) LIKE ?", ["%{$name_clean}%"])->first();
echo "DOCTOR BY NAME: " . ($d_by_name ? "ID: {$d_by_name->id}" : "NONE") . "\n";
