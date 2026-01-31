<?php

namespace App\Repositories\Tema;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TemaEditRepo
{
    public function mostrar($id)
    {
        return DB::table('tema')
            ->where('id_tema', $id)
            ->select(
                'id_tema as id',
                'id_unidad_curricular',
                'unidad_tema',
                'titulo_tema',
                'descripcion_tema'
            )
            ->first();
    }

    public function editar($id, array $data)
    {
        return DB::table('tema')
            ->where('id_tema', $id)
            ->update([
                'id_unidad_curricular' => $data['id_unidad_curricular'],
                'unidad_tema' => $data['unidad_tema'],
                'titulo_tema' => $data['titulo_tema'],
                'descripcion_tema' => $data['descripcion_tema'] ?? null,
                'fecha_actualizacion' => Carbon::now(),
            ]);
    }
}
