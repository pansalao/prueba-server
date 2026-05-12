<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Conteo de Registros en Base de Datos Local (hp_16) ---\n";
$tablesLocal = [
    'evento' => 'Eventos',
    'calendario_academico' => 'Calendario Académico',
    'detalle_calendario' => 'Detalle de Calendario (Eventos)',
    'planificacion' => 'Planificaciones',
    'tema' => 'Temas',
    'contenido' => 'Contenidos',
    'bibliografia' => 'Bibliografías',
    'recurso' => 'Recursos',
    'estrategia_pedagogica' => 'Estrategias',
    'tecnica_evaluacion' => 'Técnicas de Evaluación',
    'tipo_evaluacion' => 'Tipos de Evaluación',
    'indicador_logro' => 'Indicadores de Logro',
    'bitacora' => 'Registros en Bitácora'
];

foreach ($tablesLocal as $table => $label) {
    try {
        $count = DB::table($table)->count();
        echo "- $label ($table): $count\n";
    } catch (\Exception $e) {
        echo "- $label ($table): [Error: Tabla no encontrada]\n";
    }
}

echo "\n--- Conteo de Registros en Base de Datos de Emulación (emulacion_sogac_2) ---\n";
$tablesEmulacion = [
    'usuario' => 'Usuarios',
    'persona' => 'Personas',
    'rol' => 'Roles',
    'asignatura' => 'Asignaturas',
    'periodo' => 'Periodos Académicos'
];

foreach ($tablesEmulacion as $table => $label) {
    try {
        $count = DB::connection('emulacion_sogac_2')->table($table)->count();
        echo "- $label ($table): $count\n";
    } catch (\Exception $e) {
        echo "- $label ($table): [Error: Tabla no encontrada]\n";
    }
}
