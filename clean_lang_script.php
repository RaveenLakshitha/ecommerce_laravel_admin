<?php

$files = [
    __DIR__ . '/resources/lang/en/file.php',
    __DIR__ . '/resources/lang/es/file.php',
    __DIR__ . '/resources/lang/en/messages.php',
    __DIR__ . '/resources/lang/es/messages.php',
];

$medical_words = [
    'clinic', 
    'patient', 
    'doctor',
    'medical', 
    'appointment', 
    'prescription', 
    'diagnostic', 
    'therapeutic', 
    'medication', 
    'treatment', 
    'blood_group',
    'blood_type',
    'chronic_condition', 
    'chronic',
    'insurance', 
    'specialization', 
    'age_group', 
    'surgery', 
    'disease', 
    'nurse', 
    'pharmac', 
    'care_provider',
    'room_facility',
    'triage',
    'symptom',
    'medicine',
    'hospital',
    'consultation',
    'therapy',
    'clinical',
    'vitals',
    'dosage'
];

// Removed \b so that it matches inside words with underscores like appointment_details
$pattern = '/(' . implode('|', $medical_words) . ')/i';

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $lines = file($file);
    $cleaned_lines = [];
    
    foreach ($lines as $line) {
        if (preg_match($pattern, $line)) {
            continue;
        }
        $cleaned_lines[] = $line;
    }
    
    file_put_contents($file, implode("", $cleaned_lines));
    echo "Cleaned $file\n";
}

echo "Done.\n";
