<?php

namespace App\Repositories\Contenido;

use Illuminate\Support\Facades\DB;

class ContenidoViewRepo
{
    public function mostrar($id)
    {
        return DB::table('contenido as c')
            ->join('tema as t', 'c.id_tema', '=', 't.id_tema')
            ->join('unidad_curricular as uc', 't.id_unidad_curricular', '=', 'uc.id_unidad_curricular')
            ->select(
                'c.id_contenido',
                'uc.nombre_unidad_curricular',
                'c.titulo_contenido',
                'c.descripcion_contenido',
                't.unidad_tema as corte_contenido', // Alias for view compatibility
                'c.fecha_creacion',
                'c.fecha_actualizacion',
                'c.estatus'
            )
            ->where('c.id_contenido', $id)
            ->first();
    }
}
