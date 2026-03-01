<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;

class EventoViewRepo
{
    public function mostrar($id)
    {
        $evento = DB::table('evento')
            ->where('id_evento', $id)
            ->first();

        // Get lapso info
        if ($evento && $evento->id_lapso) {
            $lapso = DB::connection('pgsql_daece')->table('lapso_academico')
                ->where('lap_codigo', $evento->id_lapso)
                ->first();
            $evento->nombre_lapso = $lapso ? $lapso->lap_nombre : 'No definido (DAECE)';
        } elseif ($evento) {
            $evento->nombre_lapso = 'Sin Lapso';
        }

        if ($evento) {
            \App\Models\Evento::logMostrar(\App\Models\Evento::find($id));
        }

        return $evento;
    }
}
