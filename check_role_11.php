<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$rol_id = 11;
$perm_name = 'Listar de Evento';

$has = DB::table('rol_permiso as rp')
    ->join('permiso as p', 'rp.id_permiso', '=', 'p.id_permiso')
    ->where('rp.id_rol', $rol_id)
    ->where('p.nombre_permiso', $perm_name)
    ->where('p.estatus', '1')
    ->where('rp.estatus', '1')
    ->exists();

echo "El rol $rol_id tiene el permiso '$perm_name'? " . ($has ? "SÍ" : "NO") . "\n";

$all_perms = DB::table('rol_permiso as rp')
    ->join('permiso as p', 'rp.id_permiso', '=', 'p.id_permiso')
    ->where('rp.id_rol', $rol_id)
    ->pluck('p.nombre_permiso');

echo "Permisos del rol $rol_id:\n";
foreach ($all_perms as $p) {
    echo "- $p\n";
}
