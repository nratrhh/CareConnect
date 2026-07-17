<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = array_map(function($t) { return $t['name']; }, Illuminate\Support\Facades\Schema::getTables());
foreach ($tables as $t) {
    echo strtoupper($t) . " TABLE:\n";
    echo implode(', ', Illuminate\Support\Facades\Schema::getColumnListing($t)) . "\n\n";
}
