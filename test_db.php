<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    \Illuminate\Support\Facades\DB::table('Donation')->insert([
        'fundraising_campaign_id' => 3,
        'participant_id' => 28,
        'amount' => 10,
        'status' => 'pending',
        'payment_method' => 'Test',
        'donation_date' => now()
    ]);
    echo "SUCCESS";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
