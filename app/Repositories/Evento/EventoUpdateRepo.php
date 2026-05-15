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
            
            $evento->update([
                'nombre_evento' => $data['descripcion_evento'],
                'tipo_evento'   => $data['tipo_evento'],
                'id_color'      => $data['id_color'],
                'is_laborable_evento'  => $data['is_laborable'],
                'is_repetible_evento'  => $data['is_repetible'],
                'is_obligatorio_evento' => $data['is_obligatorio'],
            ]);

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
