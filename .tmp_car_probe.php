<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$cars = App\Models\Car::where('title', 'like', '%Golf%5%Plus%')->get(['title', 'body', 'generation', 'years']);
foreach ($cars as $c) {
    echo json_encode($c->only(['title', 'body', 'generation', 'years']), JSON_UNESCAPED_UNICODE), PHP_EOL;
}
