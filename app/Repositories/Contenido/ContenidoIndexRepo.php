<?php

namespace App\Repositories\Contenido;

use Illuminate\Support\Facades\DB;

class ContenidoIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        return DB::table('contenido as c')
            ->join('tema as t', 'c.id_tema', '=', 't.id_tema')
            ->join('unidad_curricular as uc', 't.id_unidad_curricular', '=', 'uc.id_unidad_curricular')
            ->select(
                'c.id_contenido',
                'c.titulo_contenido',
                // 'c.corte_contenido', // Removed as it likely doesn't exist in new schema or was legacy
                't.unidad_tema as corte_contenido', // Using Tema's unit as proxy or alias if needed for view compatibility
                'uc.nombre_unidad_curricular',
                't.titulo_tema', // Added to display Theme name
                'c.estatus'
            )
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('c.titulo_contenido', 'LIKE', '%' . $busqueda . '%')
                    ->orWhere('uc.nombre_unidad_curricular', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('c.fecha_creacion', 'desc')
            ->paginate($paginacion);
    }

    public function inhabilitar($id)
    {
        return DB::table('contenido')
            ->where('id_contenido', $id)
            ->update([
                'estatus' => '3',
                'fecha_actualizacion' => now()
            ]);
    }

    public function restaurar($id)
    {
        return DB::table('contenido')
            ->where('id_contenido', $id)
            ->where('estatus', '3')
            ->update([
                'estatus' => '1',
                'fecha_actualizacion' => now()
            ]);
    }
}
