<?php

/**
 * Script para borrar todos los registros de calendario académico y eventos.
 * Uso: php limpiar_todo.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use Illuminate\Support\Facades\DB;

// Iniciar kernel de Laravel
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--------------------------------------------------\n";
echo "SOGAT - LIMPIEZA TOTAL DE DATOS DE CALENDARIO\n";
echo "--------------------------------------------------\n";

try {
    // Desactivar llaves foráneas temporalmente para evitar errores de integridad
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    echo "PROCESANDO: Eliminando registros de la tabla 'evento'...\n";
    $eventosBorrados = DB::table('evento')->delete();
    echo " > Se eliminaron {$eventosBorrados} eventos.\n";

    echo "PROCESANDO: Eliminando registros de la tabla 'calendario_academico'...\n";
    $calendariosBorrados = DB::table('calendario_academico')->delete();
    echo " > Se eliminaron {$calendariosBorrados} calendarios.\n";

    // Reactivar llaves foráneas
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    echo "\nÉXITO: La base de datos ha sido limpiada correctamente.\n";
} catch (\Exception $e) {
    echo "ERROR CRÍTICO: " . $e->getMessage() . "\n";
    // Asegurar que se reactiven las llaves foráneas incluso si falla
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
}

echo "--------------------------------------------------\n";
echo "Proceso finalizado.\n";
