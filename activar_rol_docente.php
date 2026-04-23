<?php

/**
 * Script para activar el rol DOCENTE del usuario Alejandro (C.I. 31009367).
 * Uso: php activar_rol_docente.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Boot kernel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cedula = '31009367'; // Alejandro
$rolDocente = 3;      // DOCENTE

echo "--- Iniciando activación del rol DOCENTE para Alejandro ---\n";

try {
    $afectados = DB::connection('emulacion_sogac_2')
        ->table('usuario')
        ->where('usu_cedula', $cedula)
        ->where('usu_cod_rol', $rolDocente)
        ->update(['usu_estatus' => 'A']);

    if ($afectados > 0) {
        echo "ÉXITO: Se ha ACTIVADO la relación del usuario {$cedula} con el rol {$rolDocente}.\n";
        echo "Ahora Alejandro podrá ver el rol DOCENTE al iniciar sesión.\n";
    } else {
        echo "AVISO: No se encontró la relación para Alejandro con el rol DOCENTE o ya estaba activa.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: No se pudo realizar la operación: " . $e->getMessage() . "\n";
}

echo "--- Proceso finalizado ---\n";
