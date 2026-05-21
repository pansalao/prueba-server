<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== INICIANDO SINCRO DE PERMISOS ===\n";

// Función para copiar permisos de un rol origen a un rol destino
function copiarPermisos($origenId, $destinoId) {
    $permisosOrigen = \DB::table('rol_permiso')
        ->where('id_rol', $origenId)
        ->get();
        
    echo "Copiando " . count($permisosOrigen) . " permisos desde Rol ID $origenId a Rol ID $destinoId...\n";
    
    $insertedCount = 0;
    foreach ($permisosOrigen as $p) {
        // Verificar si ya existe para evitar duplicación
        $exists = \DB::table('rol_permiso')
            ->where('id_rol', $destinoId)
            ->where('id_permiso', $p->id_permiso)
            ->exists();
            
        if (!$exists) {
            \DB::table('rol_permiso')->insert([
                'id_rol' => $destinoId,
                'id_permiso' => $p->id_permiso,
                'estatus' => $p->estatus,
            ]);
            $insertedCount++;
        }
    }
    
    echo "Sincronizado: $insertedCount permisos nuevos insertados.\n\n";
}

// 1. Sincronizar permisos de COORDINADOR PNFINF (de 11 a 5 y a 1)
copiarPermisos(11, 5);
copiarPermisos(11, 1);

// 2. Sincronizar permisos de VICERRECTOR (de 31 a 4)
copiarPermisos(31, 4);

echo "=== SINCRO DE PERMISOS COMPLETADA ===\n";
