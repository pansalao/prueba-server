<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = DB::getSchemaBuilder()->getColumnListing('calendario_academico');
echo "Columnas en calendario_academico: " . implode(', ', $columns) . "\n";
