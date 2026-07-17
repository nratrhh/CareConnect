<?php require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$events = \App\Models\VolunteerEvent::all();
foreach($events as $e) {
    if ($e->event) {
        echo $e->event->title . ' : ' . $e->eventDate . " | \n";
    }
}
