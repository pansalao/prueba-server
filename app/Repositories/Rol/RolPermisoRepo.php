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

    public function getActivePermissions()
    {
        return DB::table('permiso')
            ->orderBy('nombre_permiso')
            ->where('estatus', '1')
            ->get();
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
