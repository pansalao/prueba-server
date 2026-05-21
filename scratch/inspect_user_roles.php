<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ROL PERMISOS DETALLADO ===\n";
$rp = \DB::table('rol_permiso')
    ->join('permiso', 'rol_permiso.id_permiso', '=', 'permiso.id_permiso')
    ->select('rol_permiso.id_rol', 'permiso.nombre_permiso')
    ->get();

$grouped = [];
foreach ($rp as $entry) {
    $grouped[$entry->id_rol][] = $entry->nombre_permiso;
}

foreach ($grouped as $rolId => $perms) {
    echo "Rol ID: $rolId (" . count($perms) . " permisos)\n";
    // Mostrar primeros 10 permisos
    echo "  Permisos: " . implode(', ', array_slice($perms, 0, 10)) . "...\n\n";
}

echo "=== ROLES DEL USUARIO ACTIVO ===\n";
// Busquemos el usuario por su cédula en la tabla usuario
$user = \App\Models\User::where('usu_cedula', '31659136')->first();
if ($user) {
    echo "Usuario encontrado: {$user->usu_nombre} (Cédula: {$user->usu_cedula})\n";
    echo "Código de rol asignado (directo en usuario): {$user->usu_cod_rol}\n";
    echo "Roles asociados de emulación:\n";
    foreach ($user->obtenerRolesAsociados() as $ra) {
        echo "  - Rol Cod: {$ra->usu_cod_rol} | Nombre: {$ra->rol_nombre} | Usu Codigo: {$ra->usu_codigo}\n";
    }
} else {
    echo "Usuario 31659136 no encontrado.\n";
}
