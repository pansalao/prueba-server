<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Tablas en hp_16:\n";
$tables = DB::select('SHOW TABLES');
foreach ($tables as $table) {
    echo "- " . current((array)$table) . "\n";
}

echo "\nTablas en emulacion_sogac_2:\n";
$tablesE = DB::connection('emulacion_sogac_2')->select('SHOW TABLES');
foreach ($tablesE as $table) {
    echo "- " . current((array)$table) . "\n";
}
