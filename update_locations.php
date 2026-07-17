<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\VolunteerEvent;

$updates = [
    1 => [
        'location_details' => 'Lot 1234, Jalan Tok Adis, Kuala Ibai, 20400 Kuala Terengganu, Terengganu'
    ],
    2 => [
        'location_details' => 'KM 22, Jalan Dungun-Kuala Terengganu, 23050 Rantau Abang, Terengganu'
    ]
];

foreach($updates as $id => $data) {
    $v = VolunteerEvent::find($id);
    if($v) {
        $v->update($data);
        echo "Updated Event ID {$id}\n";
    }
}
