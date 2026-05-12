<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$name = 'MAIKOL DAVID';

$user = DB::connection('emulacion_sogac_2')->table('usuario')
    ->where('usu_nombre', 'like', "%$name%")
    ->first();

if (!$user) {
    echo "Usuario no encontrado para el nombre $name\n";
    exit;
}

echo "Usuario: {$user->usu_nombre}\n";
echo "Cédula: {$user->usu_cedula}\n";
echo "Rol en DB (usu_cod_rol): {$user->usu_cod_rol}\n";
echo "Estatus: {$user->usu_estatus}\n";

$roles = DB::connection('emulacion_sogac_2')
    ->table('usuario as u')
    ->join('rol as r', 'u.usu_cod_rol', '=', 'r.rol_codigo')
    ->where('u.usu_cedula', $user->usu_cedula)
    ->where('u.usu_estatus', 'A')
    ->select('u.usu_cod_rol', 'r.rol_nombre', 'u.usu_codigo')
    ->get();

echo "Roles asociados en emulacion_sogac_2:\n";
foreach ($roles as $r) {
    echo "ID Rol: {$r->usu_cod_rol} - Nombre: {$r->rol_nombre}\n";
}

$permissions = DB::table('rol_permiso as rp')
    ->join('permiso as p', 'rp.id_permiso', '=', 'p.id_permiso')
    ->where('rp.id_rol', $user->usu_cod_rol)
    ->where('p.estatus', '1')
    ->where('rp.estatus', '1')
    ->select('p.nombre_permiso')
    ->get();

echo "Permisos asignados al rol {$user->usu_cod_rol} en hp_16:\n";
if ($permissions->isEmpty()) {
    echo "¡No hay permisos asignados!\n";
} else {
    foreach ($permissions as $p) {
        echo "- {$p->nombre_permiso}\n";
    }
}
