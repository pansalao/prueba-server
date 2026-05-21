<?php
use Illuminate\Support\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$eventos = DB::table('evento')->get();
foreach ($eventos as $e) {
    echo "Evento: {$e->nombre_evento} (ID: {$e->id_evento}, Tipo: {$e->tipo_evento})\n";
    $detalles = DB::table('detalle_evento')->where('id_evento', $e->id_evento)->get();
    foreach ($detalles as $d) {
        echo "  - Detalle ID: {$d->id_detalle_evento}, Calendario ID: {$d->id_calendario_academico}, Inicio: {$d->dia_inicio_detalle_evento}, Fin: {$d->dia_fin_detalle_evento}\n";
    }
}
