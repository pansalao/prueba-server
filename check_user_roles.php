<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cedula = 'V12345678'; // Let's try the first one from previous list

$users = DB::connection('emulacion_sogac_2')->table('usuario')
    ->where('usu_cedula', $cedula)
    ->get();

echo "Roles para la cédula $cedula:\n";
foreach ($users as $u) {
    echo "Código: {$u->usu_codigo} - Rol: {$u->usu_cod_rol} - Estatus: {$u->usu_estatus}\n";
}
