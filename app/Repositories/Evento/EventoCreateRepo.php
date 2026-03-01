<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventoCreateRepo
{
    public function crear(array $data)
    {
        if (empty($data['id_lapso'])) {
            $activo = DB::connection('pgsql_daece')->table('lapso_academico')
                ->where('lap_estatus', 'A')
                ->where('lap_cerrado', 'N')
                ->first();
            if (!$activo) {
                // Si no hay nada activo, lanzamos una excepción o retornamos un valor que evite el insert
                throw new \Exception('No se puede registrar el evento porque no existe un lapso académico activo.');
            }
            $data['id_lapso'] = $activo->lap_codigo;
        }

        $evento = \App\Models\Evento::create([
            'id_lapso' => $data['id_lapso'],
            'dia_inicio_evento' => $data['dia_inicio_evento'],
            'dia_fin_evento' => $data['dia_fin_evento'],
            'semana_evento' => $data['semana_evento'],
            'descripcion_evento' => $data['descripcion_evento'],
            'tipo_evento' => $data['tipo_evento'],
            'fecha_creacion' => Carbon::now(),
            'estatus' => '1',
        ]);

        return $evento->getKey();
    }

    public function existeEventoConDescripcion(string $descripcion, ?int $idLapso): bool
    {
        return DB::table('evento')
            ->where('id_lapso', $idLapso)
            ->where('descripcion_evento', $descripcion)
            ->where('estatus', '!=', '3')
            ->exists();
    }
}
