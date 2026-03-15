<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Appointment;
use App\Models\Doctor;

$sophia_doctors = Doctor::where('first_name', 'like', '%Sophia%')
    ->orWhere('last_name', 'like', '%Sophia%')
    ->get();

echo "Doctors named Sophia:\n";
foreach ($sophia_doctors as $d) {
    $count = Appointment::where('doctor_id', $d->id)->count();
    echo "ID: {$d->id}, Name: {$d->first_name} {$d->last_name}, Appointments: {$count}, UserID: " . ($d->user_id ?? 'None') . "\n";
}

$all_appointments = Appointment::with('doctor')->get();
echo "\nRecent appointments (last 5):\n";
foreach ($all_appointments->sortByDesc('created_at')->take(5) as $a) {
    echo "ID: {$a->id}, Doctor: " . ($a->doctor ? "{$a->doctor->id} ({$a->doctor->first_name} {$a->doctor->last_name})" : "Unknown") . ", Patient: {$a->patient_id}, Scheduled: {$a->scheduled_start}\n";
}
