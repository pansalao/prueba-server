<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;
use App\Models\Evento;

class EventoUpdateRepo
{
    public function actualizar($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $evento = \App\Models\Evento::findOrFail($id);
            
            // Solo actualizamos el maestro (plantilla)
            $evento->update([
                'nombre_evento' => $data['descripcion_evento'],
                'tipo_evento' => $data['tipo_evento'],
                'id_color' => $data['id_color'],
            ]);

            return $evento;
        });
    }
}
