<?php
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function dumpTable($table) {
    echo "=== TABLA: $table ===\n";
    $columns = DB::getSchemaBuilder()->getColumnListing($table);
    echo "Columnas: " . implode(', ', $columns) . "\n";
    $first = DB::table($table)->first();
    if ($first) {
        echo "Ejemplo de registro:\n";
        print_r($first);
    } else {
        echo "No hay registros.\n";
    }
    echo "\n";
}

dumpTable('planificacion');
dumpTable('detalle_evento');
dumpTable('calendario_academico');
dumpTable('evento');
dumpTable('unidad_corte');
dumpTable('detalle_evaluacion');
dumpTable('tecnica_evaluacion');
dumpTable('tipo_evaluacion');
dumpTable('tecnica_actividad');
dumpTable('recurso');
dumpTable('detalle_recurso');
dumpTable('bibliografia');
dumpTable('detalle_bibliografia');
dumpTable('tema_unidad');
dumpTable('objetivo');
dumpTable('detalle_objetivo');
dumpTable('contenido');
dumpTable('detalle_contenido');

