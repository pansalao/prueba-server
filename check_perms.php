<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Listado de permisos en hp_16.permiso (Primeros 100):\n";
$perms = DB::table('permiso')->limit(100)->get();
foreach ($perms as $p) {
    echo "ID: {$p->id_permiso} - Nombre: '{$p->nombre_permiso}'\n";
}
