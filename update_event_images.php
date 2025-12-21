<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Mapping of event IDs to image filenames
$imageMapping = [
    // Seminar
    1 => 'seminar_01.png',
    5 => 'seminar_02.png',
    17 => 'seminar_03.png',
    20 => 'seminar_04.png',
    
    // Webinar
    2 => 'webinar_01.png',
    10 => 'webinar_02.png',
    16 => 'webinar_03.png',
    
    // Open Recruitment
    3 => 'recruitment_01.png',
    4 => 'recruitment_02.png',
    9 => 'recruitment_03.png',
    12 => 'recruitment_04.png',
    
    // Workshop
    6 => 'workshop_01.png',
    11 => 'workshop_02.png',
    13 => 'workshop_03.png',
    18 => 'workshop_04.png',
    
    // Lomba/Competition
    7 => 'competition_01.png',
    8 => 'competition_02.png',
    14 => 'competition_03.png',
    15 => 'competition_04.png',
    19 => 'competition_05.png',
];

foreach ($imageMapping as $eventId => $filename) {
    $event = \App\Models\Event::find($eventId);
    if ($event) {
        $event->image = 'events/' . $filename;
        $event->save();
        echo "Updated Event #{$eventId}: {$event->title} -> {$filename}\n";
    }
}

echo "\nAll event images updated successfully!\n";
