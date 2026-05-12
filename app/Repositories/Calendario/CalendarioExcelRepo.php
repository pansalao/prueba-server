<?php

namespace App\Repositories\Calendario;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CalendarioExcelRepo
{
    /**
     * Obtiene el último calendario activo.
     */
    public function obtenerUltimoActivo()
    {
        return DB::table('calendario_academico')
            ->where('estatus', 1)
            ->orderBy('id_calendario_academico', 'desc')
            ->first();
    }

    /**
     * Obtiene un calendario por ID.
     */
    public function obtenerPorId($id)
    {
        return DB::table('calendario_academico')
            ->where('id_calendario_academico', $id)
            ->first();
    }

    /**
     * Prepara toda la data necesaria para la exportación.
     */
    public function prepararDataExportacion($calendario)
    {
        $startDate = Carbon::parse($calendario->dia_inicio_calendario_academico)->startOfDay();
        $endDate   = Carbon::parse($calendario->dia_fin_calendario_academico)->endOfDay();
        $startYear = $startDate->year;
        $endYear   = $endDate->year;

        // Obtener TODOS los eventos del calendario
        $eventosRaw = DB::table('evento')
            ->join('detalle_evento', 'evento.id_evento', '=', 'detalle_evento.id_evento')
            ->leftJoin('color', 'evento.id_color', '=', 'color.id_color')
            ->select(
                'evento.id_evento',
                'evento.nombre_evento as descripcion_evento',
                'detalle_evento.dia_inicio_detalle_evento as dia_inicio_evento',
                'detalle_evento.dia_fin_detalle_evento as dia_fin_evento',
                'color.codigo_color',
                'evento.is_laborable_evento',
                'evento.tipo_evento'
            )
            ->where('detalle_evento.id_calendario_academico', $calendario->id_calendario_academico)
            ->where('evento.estatus', 1)
            ->get();

        // Construir paleta de colores por evento
        $eventColors = [];
        $palette = ['#007BFF', '#28A745', '#DC3545', '#FD7E14', '#6610F2'];
        foreach ($eventosRaw as $index => $eventoItem) {
            $color = $eventoItem->codigo_color ?? $palette[$index % count($palette)];
            $eventColors[$eventoItem->id_evento] = $color;
        }

        // Construir mapa de días con eventos por AÑO
        $eventDaysByYear = [];
        for ($y = $startYear; $y <= $endYear; $y++) {
            $eventDaysByYear[$y] = [];
        }

        foreach ($eventosRaw as $eventoItem) {
            $start = Carbon::parse($eventoItem->dia_inicio_evento);
            $end   = Carbon::parse($eventoItem->dia_fin_evento);

            while ($start <= $end) {
                $y = $start->year;
                if (isset($eventDaysByYear[$y])) {
                    $dateStr = $start->format('Y-m-d');
                    if (!isset($eventDaysByYear[$y][$dateStr])) {
                        $eventDaysByYear[$y][$dateStr] = ['ids' => [], 'nombres' => []];
                    }
                    $eventDaysByYear[$y][$dateStr]['ids'][]     = $eventoItem->id_evento;
                    $eventDaysByYear[$y][$dateStr]['nombres'][] = $eventoItem->descripcion_evento;
                }
                $start->addDay();
            }
        }

        $years = range($startYear, $endYear);

        return [
            'calendario'         => $calendario,
            'years'              => $years,
            'startYear'          => $startYear,
            'endYear'            => $endYear,
            'eventDaysByYear'    => $eventDaysByYear,
            'eventColors'        => $eventColors,
            'eventos'            => $eventosRaw,
            'year'               => $startYear,
            'eventDays'          => $eventDaysByYear[$startYear] ?? [],
            'listaMesesCompleta' => $this->obtenerListaMesesCompleta($years, $startDate, $endDate),
        ];
    }

    /**
     * Calcula la lista plana de meses que solapan con la vigencia.
     */
    public function obtenerListaMesesCompleta($years, $startDate, $endDate)
    {
        $lista = [];
        foreach ($years as $yearLoop) {
            foreach (range(1, 12) as $mes) {
                $primerDiaMes = Carbon::create($yearLoop, $mes, 1)->startOfDay();
                $ultimoDiaMes = $primerDiaMes->copy()->endOfMonth()->endOfDay();
                if ($primerDiaMes <= $endDate && $ultimoDiaMes >= $startDate) {
                    $lista[] = [
                        'year'  => $yearLoop,
                        'month' => $mes
                    ];
                }
            }
        }
        return $lista;
    }
}
