<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;

class EventoIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        return \App\Models\Evento::with('especialEvento')->select('evento.*')
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
        return DB::table('evento')
            ->where('estatus', 1)
            ->select('id_evento', 'nombre_evento', 'tipo_evento', 
                     'is_laborable_evento', 'is_repetible_evento', 
                     'is_cantidad_dias_evento', 'cantidad_dias_evento', 
                     'codigo_color_evento')
            ->get();
    }

    /**
     * Obtiene un color específico de un evento por su ID.
     */
    public function obtenerColorPorId($id)
    {
        $evento = DB::table('evento')->where('id_evento', $id)->first();
        if ($evento && $evento->codigo_color_evento) {
            return (object) ['codigo_color' => $evento->codigo_color_evento];
        }
        // Fallback por tipo
        $colors = [
            1 => '#DC3545', 2 => '#FFC107', 3 => '#007BFF',
            4 => '#28A745', 5 => '#6c757d'
        ];
        return (object) ['codigo_color' => $colors[$evento->tipo_evento] ?? '#808080'];
    }
}
