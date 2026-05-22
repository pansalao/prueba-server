<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;
use App\Models\Evento;

class EventoUpdateRepo
{
    public function actualizar($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $evento = Evento::findOrFail($id);
            
            $params = [
                'nombre_evento' => $data['descripcion_evento'],
                'tipo_evento'   => $data['tipo_evento'],
                'especial_evento' => ($data['is_especial'] ?? false) ? (empty($data['especial_evento']) ? null : $data['especial_evento']) : null,
                'id_color'      => $data['id_color'],
                'is_laborable_evento'  => $data['is_laborable'],
                'is_repetible_evento'  => $data['is_repetible'],
                'is_rango_dias_evento'  => $data['is_rango_dias'],
                'rango_dias_evento'     => $data['is_rango_dias'] ? ($data['rango_dias'] ?? null) : null,
                'cantidad_dias_evento'  => (($data['is_especial'] ?? false) && ($data['especial_evento'] ?? '') == '1') ? ($data['cantidad_dias_evento'] ?? null) : null,
            ];

            // Guardar is_independiente de forma dinámica según la columna que exista en la BD
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing('evento');
            if (in_array('is_independiente', $columns)) {
                $params['is_independiente'] = $data['is_independiente'] ?? false;
            }
            if (in_array('is_independiente_evento', $columns)) {
                $params['is_independiente_evento'] = $data['is_independiente'] ?? false;
            }

            $evento->update($params);

            return $evento;
        });
    }

    public function getColoresDisponibles($id_evento)
    {
        return DB::table('color')
            ->where('estatus', '1')
            ->whereNotIn('id_color', function ($query) use ($id_evento) {
                $query->select('id_color')
                    ->from('evento')
                    ->where('id_evento', '!=', $id_evento)
                    ->whereNotNull('id_color')
                    ->where('estatus', '!=', '3');
            })
            ->get();
    }

    public function existeEventoConDescripcion(string $descripcion, $exceptId): bool
    {
        return DB::table('evento')
            ->where('nombre_evento', $descripcion)
            ->where('id_evento', '!=', $exceptId)
            ->exists();
    }

    public function existeColor(string $id_color, $exceptId): bool
    {
        return DB::table('evento')
            ->where('id_color', $id_color)
            ->where('id_evento', '!=', $exceptId)
            ->exists();
    }
}
