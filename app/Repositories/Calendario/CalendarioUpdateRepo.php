<?php

namespace App\Repositories\Calendario;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarioUpdateRepo
{
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
            ->where('de.id_calendario_academico', $id)
            ->select(
                'e.id_evento as id',
                'de.dia_inicio_detalle_evento as inicio',
                'de.dia_fin_detalle_evento as fin',
                'e.nombre_evento as nombre',
                'e.tipo_evento as tipo',
                'e.codigo_color_evento as color',
                'e.especial_evento as especial_evento'
            )
            ->get();
    }

    /**
     * Guarda o actualiza un borrador del calendario (específico para el flujo de actualización).
     */
    public function guardarBorrador($data, $eventos, $id)
    {
        return DB::transaction(function () use ($data, $eventos, $id) {
            $inicioDate = $data['dia_inicio_calendario_academico'] ? Carbon::parse($data['dia_inicio_calendario_academico']) : null;
            $finDate = $data['dia_fin_calendario_academico'] ? Carbon::parse($data['dia_fin_calendario_academico']) : null;

            $record = [
                'dia_inicio_calendario_academico' => $data['dia_inicio_calendario_academico'],
                'dia_fin_calendario_academico' => $data['dia_fin_calendario_academico'],
                'semana_lapso_uno_calendario_academico' => $data['semana_lapso_uno_calendario_academico'] ?? 0,
                'semana_lapso_dos_calendario_academico' => $data['semana_lapso_dos_calendario_academico'] ?? 0,
                'semana_lapso_uno_introductorio_calendario_academico' => $data['semana_lapso_uno_introductorio_calendario_academico'] ?? null,
                'semana_lapso_dos_introductorio_calendario_academico' => $data['semana_lapso_dos_introductorio_calendario_academico'] ?? null,
                'semana_intensibo_introductorio_calendario_academico' => $data['semana_intensibo_introductorio_calendario_academico'] ?? null,
                'estatus' => $data['estatus'] ?? '2' // En revisión por defecto en update
            ];

            DB::table('calendario_academico')
                ->where('id_calendario_academico', $id)
                ->update($record);

            $this->sincronizarEventos($id, $eventos);

            return $id;
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
     * Verifica si existe un evento con el mismo nombre (específico para Update).
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
     * Verifica si existe un evento con el mismo color (específico para Update).
     */
    public function existeEventoConColor($codigo_color, $excluirId = null)
    {
        return DB::table('evento')
            ->where('codigo_color_evento', $codigo_color)
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
        $insert = [
            'codigo_color_evento' => $data['codigo_color_evento'],
            'nombre_evento' => mb_strtoupper($data['nombre']),
            'tipo_evento' => $data['tipo'],
            'is_laborable_evento' => $data['is_laborable'],
            'is_repetible_evento' => $data['is_repetible'],
            'is_cantidad_dias_evento' => $data['is_rango_dias'],
            'cantidad_dias_evento' => $data['rango_dias'],
            'estatus' => '1',
        ];

        // Guardar is_independiente de forma dinámica según la columna que exista en la BD
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('evento');
        if (in_array('is_independiente', $columns)) {
            $insert['is_independiente'] = $data['is_independiente'] ?? false;
        }
        if (in_array('is_independiente_evento', $columns)) {
            $insert['is_independiente_evento'] = $data['is_independiente'] ?? false;
        }

        // Guardar is_superponible de forma dinámica según la columna que exista en la BD
        if (in_array('is_superponible_evento', $columns)) {
            $insert['is_superponible_evento'] = $data['is_superponible'] ?? true;
        }

        return DB::table('evento')->insertGetId($insert);
    }

    /**
     * Obtiene el evento de vacaciones colectivas (especial_evento = 1) activo.
     */
    public function obtenerEventoVacacionesActivo()
    {
        return DB::table('evento')
            ->where('especial_evento', '1')
            ->where('estatus', '1')
            ->first();
    }

    /**
     * Obtiene la suma de días de vacaciones colectivas ya asignados en otros calendarios activos para un año específico.
     */
    public function obtenerDiasVacacionesEnOtrosCalendarios($idEvento, $year, $excluirCalendarioId = null)
    {
        $query = DB::table('detalle_evento as de')
            ->join('calendario_academico as ca', 'de.id_calendario_academico', '=', 'ca.id_calendario_academico')
            ->where('de.id_evento', $idEvento)
            ->where('de.estatus', 1)
            ->where('ca.estatus', 1)
            ->where(function($q) use ($year) {
                $q->whereYear('de.dia_inicio_detalle_evento', $year)
                  ->orWhereYear('de.dia_fin_detalle_evento', $year);
            });

        if ($excluirCalendarioId) {
            $query->where('ca.id_calendario_academico', '!=', $excluirCalendarioId);
        }

        $otrosDetalles = $query->select('de.dia_inicio_detalle_evento', 'de.dia_fin_detalle_evento')->get();
        $diasEnOtrosCalendarios = 0;
        foreach ($otrosDetalles as $det) {
            $start = new \DateTime($det->dia_inicio_detalle_evento);
            $end = new \DateTime($det->dia_fin_detalle_evento);
            
            $interval = new \DateInterval('P1D');
            $period = new \DatePeriod($start, $interval, (clone $end)->modify('+1 day'));
            foreach ($period as $date) {
                if ($date->format('Y') == $year) {
                    $diasEnOtrosCalendarios++;
                }
            }
        }
        return $diasEnOtrosCalendarios;
    }
}
