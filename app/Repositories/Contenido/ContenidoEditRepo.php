<?php

namespace App\Repositories\Contenido;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContenidoEditRepo
{
    public function mostrar($id)
    {
        return DB::table('contenido')
            ->where('id_contenido', $id)
            ->select(
                'id_contenido as id',
                'id_tema', // Now selecting the correct foreign key
                'titulo_contenido',
                'descripcion_contenido'
                // 'corte_contenido' removed
            )
            ->first();
    }

    public function editar($id, array $data)
    {
        return DB::table('contenido')
            ->where('id_contenido', $id)
            ->update([
                'id_tema' => $data['id_tema'], // Updating relationship to Tema
                'titulo_contenido' => $data['titulo_contenido'],
                'descripcion_contenido' => $data['descripcion_contenido'] ?? null,
                'fecha_actualizacion' => Carbon::now(),
            ]);
    }
}
