<?php

namespace App\Repositories\Rol;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolIndexRepo
{
    /**
     * Consulta pura para listar roles desde la base de datos externa.
     */
    public function listar($busqueda = '', $paginacion = 5)
    {
        return DB::connection('external_db')->table('rol')
            ->select('rol_codigo', 'rol_nombre')
            ->whereIn('rol_codigo', [4, 3, 11])
            ->when($busqueda, function ($consulta, $busqueda) {
                // Compatible con Postgres e ILIKE para búsqueda insensible a mayúsculas
                $consulta->where('rol_nombre', /*'ILIKE'*/ 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('rol_codigo', 'desc')
            ->paginate($paginacion);
    }

    /**
     * Inserta o activa un permiso en la base de datos local.
     */
    public function upsertPermiso($nombre)
    {
        $existe = DB::table('permiso')->where('nombre_permiso', $nombre)->first();

        if (!$existe) {
            DB::table('permiso')->insert([
                'nombre_permiso' => $nombre,
                'fecha_creacion' => Carbon::now(),
                'estatus' => '1'
            ]);
        } else if ($existe->estatus != '1') {
            // Si existía pero no estaba activo, lo reactivamos
            DB::table('permiso')->where('id_permiso', $existe->id_permiso)->update(['estatus' => '1']);
        }
    }

    /**
     * Inactiva los permisos que no se encuentran en la lista proporcionada.
     */
    public function inactivarObsoletos(array $permisosValidos)
    {
        if (empty($permisosValidos))
            return;

        // 1. Identificamos los IDs de los permisos que ya no son válidos
        $idsObsoletos = DB::table('permiso')
            ->whereNotIn('nombre_permiso', $permisosValidos)
            ->pluck('id_permiso');

        if ($idsObsoletos->isEmpty())
            return;

        // 2. Inactivamos las relaciones en rol_permiso para esos IDs
        DB::table('rol_permiso')
            ->whereIn('id_permiso', $idsObsoletos)
            ->update([
                'estatus' => '3',
                'fecha_actualizacion' => Carbon::now()
            ]);

        // 3. Inactivamos el permiso en sí
        DB::table('permiso')
            ->whereIn('id_permiso', $idsObsoletos)
            ->update(['estatus' => '3']);
    }
}
