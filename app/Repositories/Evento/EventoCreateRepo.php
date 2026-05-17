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
            'especial_evento' => ($data['is_especial'] ?? false) ? (empty($data['especial_evento']) ? null : $data['especial_evento']) : null,
            'id_color'      => $data['id_color'] ?? null,
            'is_laborable_evento'  => $data['is_laborable'] ?? true,
            'is_repetible_evento'  => $data['is_repetible'] ?? false,
            'is_rango_dias_evento'  => $data['is_rango_dias'] ?? false,
            'rango_dias_evento'     => $data['is_rango_dias'] ? ($data['rango_dias'] ?? null) : null,
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
