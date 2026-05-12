<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$name = 'MAIKOL';

$users = DB::connection('emulacion_sogac_2')->table('usuario')
    ->where('usu_nombre', 'like', "%$name%")
    ->get();

foreach ($users as $u) {
    echo "Nombre: {$u->usu_nombre} - Cedula: {$u->usu_cedula} - Rol: {$u->usu_cod_rol} - Estatus: {$u->usu_estatus}\n";
}
