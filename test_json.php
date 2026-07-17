<?php require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$event = \App\Models\Event::with(['volunteerEvent', 'fundraisingCampaign'])->where('title', 'Kasih Warga Emas')->first();
echo $event->toJson();
