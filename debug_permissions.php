<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cedula = '26265656'; // Assuming this might be the user's cedula based on previous context or common tests

$user = User::where('usu_cedula', $cedula)->first();

if (!$user) {
    echo "Usuario no encontrado para la cédula $cedula\n";
    // List some users to find one
    $users = DB::connection('emulacion_sogac_2')->table('usuario')->limit(5)->get();
    echo "Usuarios disponibles en emulacion_sogac_2:\n";
    foreach ($users as $u) {
        echo "Cedula: {$u->usu_cedula} - Rol: {$u->usu_cod_rol} - Estatus: {$u->usu_estatus}\n";
    }
    exit;
}

echo "Usuario: {$user->usu_nombre}\n";
echo "Cédula: {$user->usu_cedula}\n";
echo "Rol en DB (usu_cod_rol): {$user->usu_cod_rol}\n";
echo "Estatus: {$user->usu_estatus}\n";

$roles = $user->obtenerRolesAsociados();
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
