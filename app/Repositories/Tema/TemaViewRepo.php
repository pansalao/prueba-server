<?php

namespace App\Repositories\Tema;

use Illuminate\Support\Facades\DB;

class TemaViewRepo
{
    public function mostrar($id)
    {
        return DB::table('tema as t')
            ->join('unidad_curricular as uc', 't.id_unidad_curricular', '=', 'uc.id_unidad_curricular')
            ->select(
                't.id_tema',
                'uc.nombre_unidad_curricular as titulo_contenido', // Keeping alias 'titulo_contenido' if the view 'show-tema' relies on it. I should check the view but this is a safer bet for now to match IndexRepo logic.
                'uc.nombre_unidad_curricular', // Added for clarity
                't.titulo_tema',
                't.descripcion_tema',
                't.unidad_tema', // Added unit number
                't.fecha_creacion',
                't.fecha_actualizacion',
                't.estatus'
            )
            ->where('t.id_tema', $id)
            ->first();
    }
}
