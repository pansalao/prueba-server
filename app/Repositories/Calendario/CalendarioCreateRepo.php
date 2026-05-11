<?php

namespace App\Repositories\Calendario;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarioCreateRepo
{
    public function crear(array $data)
    {
        $calendario = \App\Models\CalendarioAcademico::create([
            'semana_calendario_academico' => $data['semana_calendario_academico'],
            'dia_inicio_calendario_academico' => $data['dia_inicio_calendario_academico'],
            'dia_fin_calendario_academico' => $data['dia_fin_calendario_academico'],
            'estatus' => '2', // En revisión
        ]);

        return $calendario->getKey();
    }

    public function existeCalendarioEnSemana(int $semana): bool
    {
        return DB::table('calendario_academico')
            ->where('semana_calendario_academico', $semana)
            ->where('estatus', '!=', '3')
            ->exists();
    }

    public function hayCalendarioActivo(): bool
    {
        \App\Models\CalendarioAcademico::inactivarVencidos();
        return DB::table('calendario_academico')
            ->where('estatus', '1')
            ->exists();
    }

    /**
     * Crea un calendario académico junto con todos sus eventos en una transacción.
     */
    public function crearConEventos(array $data, array $eventos)
    {
        return DB::transaction(function () use ($data, $eventos) {
            // Calcular semanas si no vienen calculadas
            if (!isset($data['semana_calendario_academico'])) {
                $inicio = Carbon::parse($data['dia_inicio_calendario_academico']);
                $fin = Carbon::parse($data['dia_fin_calendario_academico']);
                $data['semana_calendario_academico'] = ceil(($inicio->diffInDays($fin) + 1) / 7);
            }

            $id = $this->crear($data);

            if ($id && count($eventos) > 0) {
                foreach ($eventos as $evento) {
                    DB::table('detalle_evento')->insert([
                        'id_calendario_academico' => $id,
                        'id_evento' => $evento['id'],
                        'dia_inicio_detalle_evento' => $evento['inicio'],
                        'dia_fin_detalle_evento' => $evento['fin'],
                        'estatus' => '1',
                    ]);
                }
            }

            return $id;
        });
    }
}
