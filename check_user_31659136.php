<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cedula = '31659136';

$users = DB::connection('emulacion_sogac_2')->table('usuario')
    ->where('usu_cedula', $cedula)
    ->get();

if ($users->isEmpty()) {
    echo "Usuario no encontrado para la cédula $cedula en emulacion_sogac_2.\n";
    exit;
}

echo "Roles encontrados para la cédula $cedula:\n";
foreach ($users as $u) {
    // Buscar nombre del rol
    $rol = DB::connection('emulacion_sogac_2')->table('rol')
        ->where('rol_codigo', $u->usu_cod_rol)
        ->first();
    
    $rolNombre = $rol ? $rol->rol_nombre : 'Desconocido';
    
    echo "- ID Usuario: {$u->usu_codigo} | ID Rol: {$u->usu_cod_rol} ({$rolNombre}) | Estatus: {$u->usu_estatus} | Nombre: {$u->usu_nombre}\n";
    
    // Verificar permisos en hp_16
    $permsCount = DB::table('rol_permiso')
        ->where('id_rol', $u->usu_cod_rol)
        ->where('estatus', '1')
        ->count();
    
    echo "  -> Permisos activos en hp_16 para este rol: $permsCount\n";
}
