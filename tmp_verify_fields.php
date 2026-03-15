<?php

use App\Models\Appointment;
use App\Models\AgeGroup;
use App\Models\OptionList;
use App\Models\Patient;
use App\Models\Doctor;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $ageGroup = AgeGroup::first();
    $language = OptionList::where('type', 'language')->first();
    $patient = Patient::first();
    $doctor = Doctor::first();

    if (!$ageGroup || !$language || !$patient || !$doctor) {
        echo "Missing required data for test.\n";
        exit(1);
    }

    echo "Testing appointment creation with age group and language...\n";
    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'scheduled_start' => now()->addDay(),
        'reason_for_visit' => 'Test visit',
        'status' => 'pending',
        'age_group_id' => $ageGroup->id,
        'preferred_language_id' => $language->id,
    ]);

    echo "Appointment created with ID: {$appointment->id}\n";
    echo "Age Group: " . ($appointment->ageGroup->name ?? 'None') . "\n";
    echo "Language: " . ($appointment->preferredLanguage->name ?? 'None') . "\n";

    if ($appointment->age_group_id == $ageGroup->id && $appointment->preferred_language_id == $language->id) {
        echo "SUCCESS: Fields saved correctly during creation.\n";
    } else {
        echo "ERROR: Fields not saved correctly during creation.\n";
    }

    echo "Testing appointment update...\n";
    $newAgeGroup = AgeGroup::skip(1)->first() ?: $ageGroup;
    $appointment->update([
        'age_group_id' => $newAgeGroup->id,
    ]);

    $appointment->refresh();
    echo "Updated Age Group: " . ($appointment->ageGroup->name ?? 'None') . "\n";

    if ($appointment->age_group_id == $newAgeGroup->id) {
        echo "SUCCESS: Fields updated correctly.\n";
    } else {
        echo "ERROR: Fields not updated correctly.\n";
    }

    // Cleanup
    $appointment->delete();
    echo "Test appointment deleted.\n";

} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
    exit(1);
}
