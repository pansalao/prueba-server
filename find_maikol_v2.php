<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = DB::connection('emulacion_sogac_2')->table('usuario')
    ->whereRaw('LOWER(usu_nombre) LIKE ?', ['%maikol%'])
    ->get();

if ($users->isEmpty()) {
    echo "No se encontraron usuarios con 'maikol'.\n";
    // List 10 users to see naming convention
    $users = DB::connection('emulacion_sogac_2')->table('usuario')->limit(10)->get();
    foreach ($users as $u) {
        echo "Nombre: '{$u->usu_nombre}' - Cedula: '{$u->usu_cedula}'\n";
    }
} else {
    foreach ($users as $u) {
        echo "Nombre: {$u->usu_nombre} - Cedula: {$u->usu_cedula} - Rol: {$u->usu_cod_rol} - Estatus: {$u->usu_estatus}\n";
    }
}
