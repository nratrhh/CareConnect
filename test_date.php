<?php require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$d = \App\Models\VolunteerEvent::whereHas('event', function($q) { $q->where('title', 'Kasih Warga Emas'); })->first()->eventDate;
echo $d->format('Y-m-d H:i:s') . "\n";
echo \Carbon\Carbon::parse($d)->format('M d, Y') . "\n";
