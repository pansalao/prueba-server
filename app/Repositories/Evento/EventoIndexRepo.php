<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;

class EventoIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        return \App\Models\Evento::with('color_rel')
            ->select('evento.*')
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_evento', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('id_evento', 'desc')
            ->paginate($paginacion);
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

    /**
     * Obtiene todos los eventos activos con sus colores (Biblioteca de plantillas).
     */
    public function obtenerBiblioteca()
    {
        return DB::table('evento as e')
            ->join('color as c', 'e.id_color', '=', 'c.id_color')
            ->where('e.estatus', 1)
            ->select('e.id_evento', 'e.nombre_evento', 'e.tipo_evento', 'e.is_laborable_evento', 'e.is_repetible_evento', 'c.codigo_color')
            ->get();
    }
}
