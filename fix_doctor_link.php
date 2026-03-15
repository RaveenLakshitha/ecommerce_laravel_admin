<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Doctor;

$user = User::find(8);
$doctor = Doctor::where('email', $user->email)->first();

if ($doctor) {
    echo "Found doctor ID: " . $doctor->id . " for user ID: " . $user->id . "\n";
    if (!$doctor->user_id) {
        echo "Doctor user_id is null. Linking...\n";
        $doctor->user_id = $user->id;
        $doctor->save();
        echo "Linked!\n";
    } else {
        echo "Doctor user_id is already: " . $doctor->user_id . "\n";
    }
} else {
    echo "No doctor found with email: " . $user->email . "\n";
    // Search by name
    $nameParts = explode(' ', str_replace('Dr. ', '', $user->name));
    $doctor = Doctor::where('first_name', 'like', '%' . ($nameParts[0] ?? '') . '%')
        ->where('last_name', 'like', '%' . (end($nameParts) ?? '') . '%')
        ->first();
    if ($doctor) {
        echo "Found doctor ID: " . $doctor->id . " by name matching for user ID: " . $user->id . "\n";
        if (!$doctor->user_id) {
            $doctor->user_id = $user->id;
            $doctor->save();
            echo "Linked by name!\n";
        }
    }
}

// Check for other unlinked doctors
$unlinked = Doctor::whereNull('user_id')->get();
echo "Unlinked doctors: " . $unlinked->count() . "\n";
foreach ($unlinked as $u) {
    echo "ID: {$u->id}, Name: {$u->first_name} {$u->last_name}, Email: {$u->email}\n";
}
