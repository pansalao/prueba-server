<?php

namespace App\Repositories\Permiso;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermisoGestionRepo
{
    public function getPermitible($id)
    {
        return DB::connection('external_db')
            ->table('rol')
            ->where('rol_codigo', $id)
            ->first();
    }

    public function getActivePermissions()
    {
        return DB::table('permiso')
            ->orderBy('nombre_permiso')
            ->where('estatus', '1')
            ->get();
    }

    public function getPermitiblePermissions($id)
    {
        return DB::table('rol_permiso')
            ->where('id_rol', $id)
            ->where('estatus', '1')
            ->pluck('id_permiso')
            ->toArray();
    }

    public function savePermitiblePermissions($id, $selectedPermissions)
    {
        DB::beginTransaction();
        try {
            // Desactivar los permisos que ya no están seleccionados
            $queryToDeactivate = \App\Models\RolPermiso::where('id_rol', $id);
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
                    $rp = \App\Models\RolPermiso::where('id_rol', $id)
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
                            'id_rol' => $id,
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
