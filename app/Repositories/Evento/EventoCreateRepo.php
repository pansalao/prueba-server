<?php

namespace App\Repositories\Evento;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventoCreateRepo
{
    public function crear(array $data)
    {
        if (empty($data['id_calendario'])) {
            $ultimo = DB::table('calendario_academico')
                ->where('estatus', 1)
                ->orderBy('id_calendario_academico', 'desc')
                ->first();
            
            if (!$ultimo) {
                throw new \Exception('No se puede registrar el evento porque no existe un calendario académico configurado.');
            }
            $data['id_calendario'] = $ultimo->id_calendario_academico;
        }

        $evento = \App\Models\Evento::create([
            'id_calendario' => $data['id_calendario'],
            'dia_inicio_evento' => $data['dia_inicio_evento'],
            'dia_fin_evento' => $data['dia_fin_evento'],
            'descripcion_evento' => $data['descripcion_evento'],
            'tipo_evento' => $data['tipo_evento'],
            'fecha_creacion' => Carbon::now(),
            'estatus' => '1',
        ]);

        return $evento->getKey();
    }

    public function existeEventoConDescripcion(string $descripcion, ?int $idCalendario): bool
    {
        return DB::table('evento')
            ->where('id_calendario', $idCalendario)
            ->where('descripcion_evento', $descripcion)
            ->where('estatus', '!=', '3')
            ->exists();
    }
}
