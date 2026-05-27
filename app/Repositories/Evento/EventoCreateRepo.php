<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;
use App\Models\Evento;

class EventoCreateRepo
{
    public function crear(array $data)
    {
        $params = [
            'nombre_evento' => $data['descripcion_evento'],
            'tipo_evento'   => $data['tipo_evento'] ?? null,
            'especial_evento' => ($data['is_especial'] ?? false) ? (empty($data['especial_evento']) ? null : $data['especial_evento']) : null,
            'codigo_color_evento' => $data['codigo_color_evento'] ?? null,
            'is_laborable_evento'  => $data['is_laborable'] ?? true,
            'is_repetible_evento'  => $data['is_repetible'] ?? false,
            'is_cantidad_dias_evento'  => $data['is_cantidad_dias_evento'] ?? false,
            'cantidad_dias_evento'  => $data['is_cantidad_dias_evento'] ? ($data['cantidad_dias_evento'] ?? null) : null,
            'is_superponible_evento' => $data['is_superponible'] ?? false,
            'is_semana_evento' => $data['is_semana_evento'] ?? (!empty($data['semanas'])),
            'semana_evento' => (($data['is_semana_evento'] ?? !empty($data['semanas'])) && !empty($data['semanas']) && is_array($data['semanas'])) ? json_encode(array_values($data['semanas'])) : null,
            'estatus'       => '1',
        ];

        // Guardar is_independiente de forma dinámica según la columna que exista en la BD
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('evento');
        if (in_array('is_independiente', $columns)) {
            $params['is_independiente'] = $data['is_independiente'] ?? false;
        }
        if (in_array('is_independiente_evento', $columns)) {
            $params['is_independiente_evento'] = $data['is_independiente'] ?? false;
        }

        $evento = Evento::create($params);

        return $evento->id_evento;
    }

    public function existeEventoConDescripcion(string $descripcion): bool
    {
        return DB::table('evento')
            ->where('nombre_evento', $descripcion)
            ->exists();
    }

    public function existeColor(string $codigo_color): bool
    {
        return DB::table('evento')
            ->where('codigo_color_evento', $codigo_color)
            ->exists();
    }
}
