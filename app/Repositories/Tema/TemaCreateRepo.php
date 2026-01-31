<?php

namespace App\Repositories\Tema;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TemaCreateRepo
{
    public function select_unidades_curriculares()
    {
        return DB::table('unidad_curricular')
            ->select('id_unidad_curricular as id', 'nombre_unidad_curricular as nombre')
            ->where('estatus', '1')
            ->get();
    }

    public function crear(array $data)
    {
        return DB::table('tema')->insertGetId([
            'id_unidad_curricular' => $data['id_unidad_curricular'],
            'titulo_tema' => $data['titulo_tema'],
            'descripcion_tema' => $data['descripcion_tema'] ?? null,
            'unidad_tema' => $data['unidad_tema'],
            'fecha_creacion' => Carbon::now(),
            'fecha_actualizacion' => null,
            'estatus' => '1',
        ]);
    }
}
