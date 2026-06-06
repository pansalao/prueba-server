<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cedula = '31114131';
$results = DB::connection('emulacion_sogac_2')
    ->table('usuario')
    ->where('usu_cedula', $cedula)
    ->get();

print_r($results->toArray());
