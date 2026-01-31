<?php

namespace App\Repositories\Contenido;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ContenidoCreateRepo
{
    public function select_temas()
    {
        return DB::table('tema')
            ->select('id_tema as id', 'titulo_tema as nombre')
            ->where('estatus', '1')
            ->get();
    }

    public function crear(array $data)
    {
        return DB::table('contenido')->insertGetId([
            'id_tema' => $data['id_tema'],
            'titulo_contenido' => $data['titulo_contenido'],
            'descripcion_contenido' => $data['descripcion_contenido'] ?? null,
            'fecha_creacion' => Carbon::now(),
            'fecha_actualizacion' => null,
            'estatus' => '1',
        ]);
    }
}
