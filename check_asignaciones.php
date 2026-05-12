<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cedula = '31659136';

$asignaciones = DB::connection('emulacion_sogac_2')
    ->table('seccion_unidad_docente')
    ->where('sud_ced_docente', $cedula)
    ->get();

echo "Asignaciones para la cédula $cedula en seccion_unidad_docente:\n";
if ($asignaciones->isEmpty()) {
    echo "¡No hay asignaciones para esta cédula!\n";
    // List some assignments to see what's available
    $any = DB::connection('emulacion_sogac_2')->table('seccion_unidad_docente')->limit(5)->get();
    echo "Ejemplos de asignaciones existentes:\n";
    foreach ($any as $a) {
        echo "- Cedula: {$a->sud_ced_docente} | Unidad: {$a->sud_cod_unidad} | Seccion: {$a->sud_cod_seccion}\n";
    }
} else {
    foreach ($asignaciones as $a) {
        echo "- Código: {$a->sud_codigo} | Unidad: {$a->sud_cod_unidad} | Seccion: {$a->sud_cod_seccion} | Estatus: {$a->sud_estatus}\n";
    }
}
