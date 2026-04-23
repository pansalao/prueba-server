<?php

/**
 * Script para conmutar (activar/inactivar) el estatus de un rol para un usuario.
 * Uso: php toggle_rol.php {cedula} {rol_codigo}
 * Ejemplo: php toggle_rol.php 31009367 4
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Boot kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Obtener argumentos
$cedula = $argv[1] ?? '31009367';
$rol_codigo = $argv[2] ?? null;

if (!$rol_codigo) {
    echo "ERROR: Debe especificar el código del rol.\n";
    echo "Ejemplo: php toggle_rol.php 31009367 4 (Estudiante)\n";
    exit(1);
}

echo "--- Iniciando inversión de estatus para C.I. {$cedula} y Rol {$rol_codigo} ---\n";

try {
    // Buscar el registro actual
    $usuario = DB::connection('emulacion_sogac_2')
        ->table('usuario')
        ->where('usu_cedula', $cedula)
        ->where('usu_cod_rol', $rol_codigo)
        ->first();

    if (!$usuario) {
        echo "AVISO: No se encontró una relación registrada para el usuario {$cedula} con el rol {$rol_codigo}.\n";
        exit(0);
    }

    $nuevoEstatus = ($usuario->usu_estatus === 'A') ? 'I' : 'A';
    $textoEstatus = ($nuevoEstatus === 'A') ? 'ACTIVADO' : 'INACTIVADO';

    DB::connection('emulacion_sogac_2')
        ->table('usuario')
        ->where('usu_codigo', $usuario->usu_codigo)
        ->update(['usu_estatus' => $nuevoEstatus]);

    echo "ÉXITO: La relación ha sido {$textoEstatus} (Nuevo estatus: '{$nuevoEstatus}').\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "--- Proceso finalizado ---\n";
