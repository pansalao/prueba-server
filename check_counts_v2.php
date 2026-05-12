<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Conteo de Registros en Base de Datos Local (hp_16) ---\n";
$tablesLocal = [
    'evento' => 'Eventos',
    'calendario_academico' => 'Calendarios Académicos',
    'detalle_evento' => 'Eventos Registrados en Calendarios',
    'planificacion' => 'Planificaciones',
    'tema_unidad' => 'Unidades/Temas',
    'contenido' => 'Contenidos de Clase',
    'bibliografia' => 'Bibliografías',
    'recurso' => 'Recursos Educativos',
    'tecnica_evaluacion' => 'Técnicas de Evaluación',
    'tipo_evaluacion' => 'Tipos de Evaluación',
    'bitacora' => 'Entradas en Bitácora'
];

foreach ($tablesLocal as $table => $label) {
    try {
        $count = DB::table($table)->count();
        echo "- $label: $count\n";
    } catch (\Exception $e) {
        echo "- $label: [No existe la tabla]\n";
    }
}

echo "\n--- Conteo de Registros en Base de Datos de Emulación (emulacion_sogac_2) ---\n";
$tablesEmulacion = [
    'usuario' => 'Usuarios',
    'persona' => 'Personas (Docentes/Estudiantes)',
    'rol' => 'Roles',
    'unidad_curricular' => 'Unidades Curriculares (Asignaturas)',
    'lapso_academico' => 'Lapsos Académicos'
];

foreach ($tablesEmulacion as $table => $label) {
    try {
        $count = DB::connection('emulacion_sogac_2')->table($table)->count();
        echo "- $label: $count\n";
    } catch (\Exception $e) {
        echo "- $label: [No existe la tabla]\n";
    }
}
