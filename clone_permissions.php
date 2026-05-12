<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sourceRol = 11; // COORDINADOR PNFINF (Con permisos)
$targetRoles = [1, 5]; // COORDINADOR y COORDINADOR PNFINF (Sin permisos)

echo "Iniciando clonación de permisos desde Rol $sourceRol...\n";

$permissions = DB::table('rol_permiso')
    ->where('id_rol', $sourceRol)
    ->where('estatus', '1')
    ->get();

if ($permissions->isEmpty()) {
    echo "Error: El rol origen ($sourceRol) no tiene permisos para clonar.\n";
    exit;
}

foreach ($targetRoles as $targetRol) {
    echo "\nProcesando Rol $targetRol:\n";
    $inserted = 0;
    $alreadyHad = 0;

    foreach ($permissions as $p) {
        $exists = DB::table('rol_permiso')
            ->where('id_rol', $targetRol)
            ->where('id_permiso', $p->id_permiso)
            ->exists();

        if (!$exists) {
            DB::table('rol_permiso')->insert([
                'id_rol' => $targetRol,
                'id_permiso' => $p->id_permiso,
                'estatus' => '1'
            ]);
            $inserted++;
        } else {
            // Asegurar que esté activo
            DB::table('rol_permiso')
                ->where('id_rol', $targetRol)
                ->where('id_permiso', $p->id_permiso)
                ->update(['estatus' => '1']);
            $alreadyHad++;
        }
    }
    echo "- Permisos nuevos asignados: $inserted\n";
    echo "- Permisos que ya existían (activados): $alreadyHad\n";
}

echo "\n¡Proceso completado con éxito!\n";
