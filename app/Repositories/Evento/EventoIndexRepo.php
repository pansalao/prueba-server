<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;

class EventoIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        $eventos = DB::table('evento')
            ->select('id_evento', 'id_lapso', 'descripcion_evento', 'dia_inicio_evento', 'dia_fin_evento', 'semana_evento', 'tipo_evento', 'estatus')
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('descripcion_evento', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('dia_inicio_evento', 'desc')
            ->paginate($paginacion);

        // Obtener nombres de lapsos desde la base de datos externa DAECE
        $idLapsos = $eventos->pluck('id_lapso')->filter()->unique()->toArray();
        $lapsosDaece = DB::connection('pgsql_daece')->table('lapso_academico')
            ->whereIn('lap_codigo', $idLapsos)
            ->pluck('lap_nombre', 'lap_codigo');

        // Mapear los nombres a la colección
        $eventos->getCollection()->transform(function ($evento) use ($lapsosDaece) {
            $evento->nombre_lapso = $evento->id_lapso ? ($lapsosDaece[$evento->id_lapso] ?? 'No definido (DAECE)') : 'Sin Lapso';
            return $evento;
        });

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
