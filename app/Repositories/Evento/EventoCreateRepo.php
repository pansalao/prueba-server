<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;
use App\Models\Evento;

class EventoCreateRepo
{
    public function crear(array $data)
    {
        // Solo creamos el maestro del evento (plantilla)
        $evento = Evento::create([
            'nombre_evento' => $data['descripcion_evento'],
            'tipo_evento'   => $data['tipo_evento'] ?? null,
            'id_color'      => $data['id_color'] ?? null,
            'is_laborable_evento'  => $data['is_laborable'] ?? true,
            'is_repetible_evento'  => $data['is_repetible'] ?? false,
            'estatus'       => '1',
        ]);

        return $evento->id_evento;
    }

    public function getColoresDisponibles()
    {
        return DB::table('color')
            ->where('estatus', '1')
            ->whereNotIn('id_color', function ($query) {
                $query->select('id_color')
                    ->from('evento')
                    ->whereNotNull('id_color')
                    ->where('estatus', '!=', '3');
            })
            ->get();
    }

    public function existeEventoConDescripcion(string $descripcion): bool
    {
        return DB::table('evento')
            ->where('nombre_evento', $descripcion)
            ->exists();
    }

    public function existeColor(string $id_color): bool
    {
        return DB::table('evento')
            ->where('id_color', $id_color)
            ->exists();
    }
}
