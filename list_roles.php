<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Listado de roles en emulacion_sogac_2.rol:\n";
$roles = DB::connection('emulacion_sogac_2')->table('rol')->get();
foreach ($roles as $r) {
    echo "ID: {$r->rol_codigo} - Nombre: {$r->rol_nombre}\n";
}

echo "\nListado de roles en hp_16.rol (si existe):\n";
try {
    $roles_hp = DB::table('rol')->get();
    foreach ($roles_hp as $r) {
        echo "ID: {$r->id_rol} - Nombre: {$r->nombre_rol}\n";
    }
} catch (\Exception $e) {
    echo "Error o tabla no existe: " . $e->getMessage() . "\n";
}
