<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\VolunteerEvent;

foreach(VolunteerEvent::with('event')->get() as $v) {
    echo "ID: {$v->volunteer_event_id} | Title: {$v->event->title} | Location: {$v->location}\n";
}
