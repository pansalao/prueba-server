<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;

class EventoIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        $eventos = DB::table('evento as e')
            ->leftJoin('detalle_evento as de', 'e.id_evento', '=', 'de.id_evento')
            ->leftJoin('calendario_academico as c', 'de.id_calendario_academico', '=', 'c.id_calendario_academico')
            ->leftJoin('color as col', 'e.id_color', '=', 'col.id_color')
            ->select(
                'e.id_evento',
                'de.id_calendario_academico as id_calendario',
                'e.nombre_evento as descripcion_evento',
                'de.dia_inicio_detalle_evento as dia_inicio_evento',
                'de.dia_fin_detalle_evento as dia_fin_evento',
                'col.codigo_color as color',
                'e.tipo_evento',
                'e.estatus'
            )
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('e.nombre_evento', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('de.dia_inicio_detalle_evento', 'desc')
            ->paginate($paginacion);

        return $eventos;
    }

    public function inhabilitar($id)
    {
        $evento = \App\Models\Evento::find($id);
        if ($evento) {
            return $evento->update([
                'estatus' => '3'
            ]);
        }
        return false;
    }

    public function restaurar($id)
    {
        $evento = \App\Models\Evento::where('id_evento', $id)->where('estatus', '3')->first();
        if ($evento) {
            return $evento->update([
                'estatus' => '1'
            ]);
        }
        return false;
    }
}
