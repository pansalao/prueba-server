<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rol_id = 2; // DOCENTE
$count = DB::table('rol_permiso')->where('id_rol', $rol_id)->count();

echo "El rol $rol_id (DOCENTE) tiene $count permisos.\n";
