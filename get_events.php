<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$events = \App\Models\Event::with('category')->get();

foreach ($events as $event) {
    echo $event->id . ". " . $event->title . "\n";
    echo "   Category: " . ($event->category ? $event->category->name : 'None') . "\n";
    echo "   Image: " . $event->image . "\n\n";
}
