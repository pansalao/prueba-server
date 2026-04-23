<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventoCreateRepo
{
    public function crear(array $data)
    {
        // Solo creamos el maestro del evento (plantilla)
        $evento = \App\Models\Evento::create([
            'nombre_evento' => $data['descripcion_evento'],
            'tipo_evento' => $data['tipo_evento'] ?? null,
            'id_color' => $data['id_color'] ?? null,
            'estatus' => '1',
        ]);

        return $evento->id_evento;
    }

    public function existeEventoConDescripcion(string $descripcion): bool
    {
        return DB::table('evento')
            ->where('nombre_evento', $descripcion)
            ->where('estatus', '!=', '3')
            ->exists();
    }
}
