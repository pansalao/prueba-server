<?php
/**
 * Script para agregar y activar los roles de DOCENTE (3) y COORDINADOR (11)
 * para el usuario Enmanuel Gabriel Salas Adans (C.I. 31114131).
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cedula = '31114131';
$passwordHash = '$2y$10$1aU6afels2wF4ufRTJ7QJ.JCOvdriP0EgwN6ef3AHlk7znAu3wdY.'; // Hash de Enmanuel

echo "--- Iniciando asignación de roles para C.I. {$cedula} ---\n";

$rolesParaAsignar = [
    3  => ['nombre' => '31114131', 'rol_name' => 'DOCENTE'],
    11 => ['nombre' => '31114131PNFINF', 'rol_name' => 'COORDINADOR PNFINF']
];

foreach ($rolesParaAsignar as $codRol => $info) {
    try {
        $usuarioExistente = DB::connection('emulacion_sogac_2')
            ->table('usuario')
            ->where('usu_cedula', $cedula)
            ->where('usu_cod_rol', $codRol)
            ->first();

        if ($usuarioExistente) {
            if ($usuarioExistente->usu_estatus !== 'A') {
                DB::connection('emulacion_sogac_2')
                    ->table('usuario')
                    ->where('usu_codigo', $usuarioExistente->usu_codigo)
                    ->update(['usu_estatus' => 'A']);
                echo "Rol {$info['rol_name']} ({$codRol}) EXISTÍA pero estaba INACTIVO. Se ha ACTIVADO.\n";
            } else {
                echo "Rol {$info['rol_name']} ({$codRol}) ya está registrado y ACTIVO.\n";
            }
        } else {
            DB::connection('emulacion_sogac_2')
                ->table('usuario')
                ->insert([
                    'usu_nombre' => $info['nombre'],
                    'usu_pegunta_1' => 'Cuál es su postre favorito',
                    'usu_pegunta_2' => 'Nombre de su mascota',
                    'usu_respuesta_1' => 'b47907a8021e3730a6ba0d6681bcdbd4f29c9de3ac8c3668cf35e4b078ed98b8',
                    'usu_respuesta_2' => 'b47907a8021e3730a6ba0d6681bcdbd4f29c9de3ac8c3668cf35e4b078ed98b8',
                    'usu_cod_rol' => $codRol,
                    'usu_estatus' => 'A',
                    'usu_cedula' => $cedula,
                    'usu_clave' => $passwordHash,
                    'usu_intento_inicio' => 0,
                    'usu_fecha_intento_inicio' => date('Y-m-d')
                ]);
            echo "Rol {$info['rol_name']} ({$codRol}) ha sido CREADO y ACTIVADO con éxito.\n";
        }
    } catch (\Exception $e) {
        echo "ERROR al procesar rol {$info['rol_name']}: " . $e->getMessage() . "\n";
    }
}

echo "--- Proceso finalizado ---\n";
