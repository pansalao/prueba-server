<?php

namespace App\Repositories\Rol;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolPermisoRepo
{
    public function getRol($rolId)
    {
        return DB::connection('external_db')
            ->table('rol')
            ->where('rol_codigo', $rolId)
            ->first();
    }

    public function getModules()
    {
        $permisos = DB::table('permiso')
            ->orderBy('nombre_permiso')
            ->where('estatus', '1')
            ->get();

        $modules = [];

        foreach ($permisos as $p) {
            if (empty($p->nombre_permiso))
                continue;

            // Nueva lógica de agrupación inteligente:
            // Si el nombre contiene ' de ', el módulo es lo que está después.
            // Ejemplo: 'Reporte General de Planificacion' -> Módulo: 'Planificacion', Acción: 'Reporte General'
            if (str_contains(strtolower($p->nombre_permiso), ' de ')) {
                $parts = explode(' de ', $p->nombre_permiso);
                $module = ucwords(trim(array_pop($parts))); // El último elemento es el módulo
                $action = trim(implode(' de ', $parts));    // Lo anterior es la acción
            } else {
                // Si no hay ' de ', dividimos por el último espacio.
                // Ejemplo: 'Listar Evento' -> Módulo: 'Evento', Acción: 'Listar'
                $parts = explode(' ', trim($p->nombre_permiso));
                if (count($parts) < 2) {
                    $module = 'General';
                    $action = $p->nombre_permiso;
                } else {
                    $module = ucwords(trim(array_pop($parts))); // La última palabra es el módulo
                    $action = trim(implode(' ', $parts));     // Lo anterior es la acción
                }
            }

            $modules[$module][] = [
                'id' => $p->id_permiso,
                'action' => $action,
                'full_name' => $p->nombre_permiso,
                'estatus' => $p->estatus
            ];
        }

        // Ordenamos alfabéticamente los módulos
        ksort($modules);

        return $modules;
    }

    public function getRolePermissions($rolId)
    {
        return DB::table('rol_permiso')
            ->where('id_rol', $rolId)
            ->where('estatus', '1')
            ->pluck('id_permiso')
            ->toArray();
    }

    public function saveRolePermissions($rolId, $selectedPermissions)
    {
        DB::beginTransaction();
        try {
            // Desactivar los permisos que ya no están seleccionados
            $queryToDeactivate = \App\Models\RolPermiso::where('id_rol', $rolId);
            if (!empty($selectedPermissions)) {
                $queryToDeactivate->whereNotIn('id_permiso', $selectedPermissions);
            }
            $toDeactivate = $queryToDeactivate->get();
            foreach ($toDeactivate as $rp) {
                if ($rp->estatus != '3') {
                    $rp->update(['estatus' => '3', 'fecha_actualizacion' => Carbon::now()]);
                }
            }

            // Insertar o activar los permisos seleccionados
            if (!empty($selectedPermissions)) {
                foreach ($selectedPermissions as $idPermiso) {
                    $rp = \App\Models\RolPermiso::where('id_rol', $rolId)
                        ->where('id_permiso', $idPermiso)
                        ->first();

                    if ($rp) {
                        if ($rp->estatus != '1') {
                            $rp->update([
                                'estatus' => '1',
                                'fecha_actualizacion' => Carbon::now()
                            ]);
                        }
                    } else {
                        \App\Models\RolPermiso::create([
                            'id_rol' => $rolId,
                            'id_permiso' => $idPermiso,
                            'estatus' => '1',
                            'fecha_creacion' => Carbon::now()
                        ]);
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
