<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;

class EventoEditRepo
{
    public function mostrar($id)
    {
        return DB::table('evento')
            ->where('id_evento', $id)
            ->first();
    }

    public function editar($id, array $data)
    {
        $evento = \App\Models\Evento::find($id);
        if ($evento) {
            $id_lapso = $data['id_lapso'] ?? null;
            if (empty($id_lapso)) {
                $activo = DB::connection('pgsql_daece')->table('lapso_academico')
                    ->where('lap_estatus', 'A')
                    ->where('lap_cerrado', 'N')
                    ->first();
                if (!$activo) {
                    throw new \Exception('No se puede actualizar el evento porque no existe un lapso académico activo.');
                }
                $id_lapso = $activo->lap_codigo;
            }

            return $evento->update([
                'id_lapso' => $id_lapso,
                'dia_inicio_evento' => $data['dia_inicio_evento'],
                'dia_fin_evento' => $data['dia_fin_evento'],
                'semana_evento' => $data['semana_evento'],
                'descripcion_evento' => $data['descripcion_evento'],
                'tipo_evento' => $data['tipo_evento'],
            ]);
        }
        return false;
    }

    public function existeEventoConDescripcion(string $descripcion, ?int $idLapso, ?int $idEventoExcluir): bool
    {
        return DB::table('evento')
            ->where('id_lapso', $idLapso)
            ->where('descripcion_evento', $descripcion)
            ->where('id_evento', '!=', $idEventoExcluir)
            ->where('estatus', '!=', '3')
            ->exists();
    }
}
