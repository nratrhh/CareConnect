<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['participant', 'admin', 'event', 'fundraising_campaign', 'volunteer_event', 'donation', 'volunteer_application', 'notification'];

foreach ($tables as $t) {
    echo strtoupper($t) . " TABLE:\n";
    $columns = Illuminate\Support\Facades\DB::select('SHOW COLUMNS FROM ' . $t);
    foreach ($columns as $c) {
        echo "{$c->Field} | {$c->Type} | Null: {$c->Null} | Key: {$c->Key} | Default: {$c->Default} | Extra: {$c->Extra}\n";
    }
    echo "\n";
}
