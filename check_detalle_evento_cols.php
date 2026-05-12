<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = DB::getSchemaBuilder()->getColumnListing('detalle_evento');
echo "Columnas en detalle_evento: " . implode(', ', $columns) . "\n";
