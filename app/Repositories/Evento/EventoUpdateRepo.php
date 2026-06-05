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
                'id_especial_evento' => ($data['is_especial'] ?? false) ? (empty($data['id_especial_evento']) ? null : $data['id_especial_evento']) : null,
                'codigo_color_evento' => $data['codigo_color_evento'] ?? null,
                'is_laborable_evento'  => $data['is_laborable'],
                'is_repetible_evento'  => $data['is_repetible'],
                'cantidad_repetible_evento' => ($data['is_repetible'] ?? false) ? ($data['cantidad_repetible_evento'] ?? null) : null,
                'is_cantidad_dias_evento' => $data['is_rango_dias'],
                'cantidad_dias_evento'  => ($data['is_rango_dias'] ?? false)
                    ? ($data['rango_dias'] ?? null)
                    : ((($data['is_especial'] ?? false) && ($data['id_especial_evento'] ?? '') == '1')
                        ? ($data['cantidad_dias_evento'] ?? null)
                        : null),
                'is_superponible_evento'=> $data['is_superponible'] ?? false,
                'is_fin_semana_evento' => $data['is_fin_semana_evento'] ?? false,
                'is_dia_evento' => $data['is_dia_evento'] ?? false,
                'dia_evento' => ($data['is_dia_evento'] ?? false) ? ($data['dia_evento'] ?? null) : null,
                'is_semana_evento' => $data['is_semana_evento'] ?? (!empty($data['semanas'])),
                'semana_evento' => (($data['is_semana_evento'] ?? !empty($data['semanas'])) && !empty($data['semanas']) && is_array($data['semanas'])) 
                ? array_values(array_filter($data['semanas'], fn($v) => is_array($v) && !empty($v['semana']) && $v['semana'] !== null && $v['semana'] !== '')) 
                : null,
                'justificativo_evento' => $data['justificativo_evento'] ?? null,
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

    public function existeEventoConDescripcion(string $descripcion, $exceptId): bool
    {
        return DB::table('evento')
            ->where('nombre_evento', $descripcion)
            ->where('id_evento', '!=', $exceptId)
            ->exists();
    }

    public function existeColor(string $codigo_color, $exceptId): bool
    {
        return DB::table('evento')
            ->where('codigo_color_evento', $codigo_color)
            ->where('id_evento', '!=', $exceptId)
            ->exists();
    }
}
