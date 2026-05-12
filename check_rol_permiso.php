<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Conteos en rol_permiso por id_rol:\n";
$counts = DB::table('rol_permiso')
    ->select('id_rol', DB::raw('count(*) as total'))
    ->groupBy('id_rol')
    ->get();

foreach ($counts as $c) {
    echo "ID Rol: {$c->id_rol} - Permisos asignados: {$c->total}\n";
}
