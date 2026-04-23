<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;

class EventoViewRepo
{
    public function mostrar($id)
    {
        $evento = \App\Models\Evento::with(['detalles.calendario'])
            ->where('id_evento', $id)
            ->first();

        if ($evento) {
            \App\Models\Evento::logMostrar($evento);
        }

        return $evento;
    }
}
