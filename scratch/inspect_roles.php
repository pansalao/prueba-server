<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ROLES IN SYSTEM ===\n";
foreach (\App\Models\Rol::all() as $rol) {
    echo "ID: {$rol->rol_codigo} | Nombre: {$rol->rol_nombre}\n";
}

echo "\n=== ROL PERMISOS count ===\n";
$rolPermisos = \DB::table('rol_permiso')
    ->select('id_rol', \DB::raw('count(*) as count'))
    ->groupBy('id_rol')
    ->get();
foreach ($rolPermisos as $rp) {
    echo "Rol ID: {$rp->id_rol} | Permisos asignados: {$rp->count}\n";
}
