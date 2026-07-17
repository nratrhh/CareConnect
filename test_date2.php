<?php require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$model = \App\Models\VolunteerEvent::whereHas('event', function($q) { $q->where('title', 'Kasih Warga Emas'); })->first();
echo "Raw eventDate: " . $model->getRawOriginal('eventDate') . "\n";
echo "Parsed by blade: " . \Carbon\Carbon::parse($model->eventDate)->format('M d, Y') . "\n";
