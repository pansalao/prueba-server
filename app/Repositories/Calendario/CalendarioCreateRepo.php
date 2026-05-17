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
    public function crearConEventos(array $data, array $eventos, $id = null)
    {
        return DB::transaction(function () use ($data, $eventos, $id) {
            // Calcular semanas si no vienen calculadas
            if (!isset($data['semana_calendario_academico'])) {
                $inicio = Carbon::parse($data['dia_inicio_calendario_academico']);
                $fin = Carbon::parse($data['dia_fin_calendario_academico']);
                $data['semana_calendario_academico'] = ceil(($inicio->diffInDays($fin) + 1) / 7);
            }

            if ($id) {
                DB::table('calendario_academico')
                    ->where('id_calendario_academico', $id)
                    ->update([
                        'semana_calendario_academico' => $data['semana_calendario_academico'],
                        'dia_inicio_calendario_academico' => $data['dia_inicio_calendario_academico'],
                        'dia_fin_calendario_academico' => $data['dia_fin_calendario_academico'],
                        'estatus' => '2', // Al confirmar, pasa a En revisión
                    ]);
                $finalId = $id;
            } else {
                $finalId = $this->crear($data);
            }

            if ($finalId) {
                $this->sincronizarEventos($finalId, $eventos);
            }

            return $finalId;
        });
    }

    /**
     * Obtiene un calendario académico por su ID.
     */
    public function obtenerPorId($id)
    {
        return DB::table('calendario_academico')
            ->where('id_calendario_academico', $id)
            ->first();
    }

    /**
     * Obtiene los eventos detallados asociados a un calendario.
     */
    public function obtenerEventosDetalle($id)
    {
        return DB::table('detalle_evento as de')
            ->join('evento as e', 'de.id_evento', '=', 'e.id_evento')
            ->leftJoin('color as c', 'e.id_color', '=', 'c.id_color')
            ->where('de.id_calendario_academico', $id)
            ->select(
                'e.id_evento as id',
                'de.dia_inicio_detalle_evento as inicio',
                'de.dia_fin_detalle_evento as fin',
                'e.nombre_evento as nombre',
                'e.tipo_evento as tipo',
                'c.codigo_color as color'
            )
            ->get();
    }

    /**
     * Guarda o actualiza un borrador del calendario.
     */
    public function guardarBorrador($data, $eventos, $id = null)
    {
        return DB::transaction(function () use ($data, $eventos, $id) {
            $inicioDate = $data['dia_inicio_calendario_academico'] ? Carbon::parse($data['dia_inicio_calendario_academico']) : null;
            $finDate = $data['dia_fin_calendario_academico'] ? Carbon::parse($data['dia_fin_calendario_academico']) : null;
            
            $semanas = ($inicioDate && $finDate) ? ceil(($inicioDate->diffInDays($finDate) + 1) / 7) : 0;

            $record = [
                'dia_inicio_calendario_academico' => $data['dia_inicio_calendario_academico'],
                'dia_fin_calendario_academico' => $data['dia_fin_calendario_academico'],
                'semana_calendario_academico' => $semanas,
                'estatus' => $data['estatus'] ?? '4' // Por defecto incompleto
            ];

            if ($id) {
                DB::table('calendario_academico')
                    ->where('id_calendario_academico', $id)
                    ->update($record);
                $finalId = $id;
            } else {
                $finalId = DB::table('calendario_academico')->insertGetId($record);
            }

            $this->sincronizarEventos($finalId, $eventos);

            return $finalId;
        });
    }

    /**
     * Actualiza el estatus de un calendario.
     */
    public function actualizarEstatus($id, $estatus, $dataExtra = [])
    {
        return DB::table('calendario_academico')
            ->where('id_calendario_academico', $id)
            ->update(array_merge(['estatus' => $estatus], $dataExtra));
    }

    /**
     * Sincroniza los eventos asignados a un calendario.
     */
    public function sincronizarEventos($id, array $eventos)
    {
        DB::table('detalle_evento')->where('id_calendario_academico', $id)->delete();

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

    /**
     * Verifica si existe un evento con el mismo nombre (excluyendo inactivos).
     */
    public function existeEventoConNombre($nombre, $excluirId = null)
    {
        return DB::table('evento')
            ->where('nombre_evento', $nombre)
            ->where('estatus', '!=', '3')
            ->when($excluirId, function ($q) use ($excluirId) {
                $q->where('id_evento', '!=', $excluirId);
            })
            ->exists();
    }

    /**
     * Verifica si existe un evento con el mismo color (excluyendo inactivos).
     */
    public function existeEventoConColor($id_color, $excluirId = null)
    {
        return DB::table('evento')
            ->where('id_color', $id_color)
            ->where('estatus', '!=', '3')
            ->when($excluirId, function ($q) use ($excluirId) {
                $q->where('id_evento', '!=', $excluirId);
            })
            ->exists();
    }
    /**
     * Registra un nuevo evento (plantilla) en la base de datos.
     */
    public function crearTemplate($data)
    {
        return DB::table('evento')->insertGetId([
            'id_color' => $data['id_color'],
            'nombre_evento' => mb_strtoupper($data['nombre']),
            'tipo_evento' => $data['tipo'],
            'is_laborable_evento' => $data['is_laborable'],
            'is_repetible_evento' => $data['is_repetible'],
            'is_rango_dias_evento' => $data['is_rango_dias'],
            'rango_dias_evento' => $data['rango_dias'],
            'estatus' => '1',
        ]);
    }
}
