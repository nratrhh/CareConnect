<?php require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$view = view('welcome')->render();
$pos = strpos($view, 'Kasih Warga Emas');
if ($pos !== false) {
    echo substr($view, max(0, $pos - 500), 1000);
} else {
    echo "Kasih Warga Emas not found in view\n";
}
