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

        // Preparar lógica de semanas (TR y NI)
        $iniciosLapso = []; $finesLapso = [];
        $iniciosIntro = []; $finesIntro = [];
        $iniciosIntensivo = []; $finesIntensivo = [];
        $semanasFestivasNormal = [];
        $semanasFestivasIntensivo = [];

        foreach ($eventosRaw as $ev) {
            $esp = $ev->tipo_evento == 4 || $ev->tipo_evento == 5 || $ev->tipo_evento == 6 ? collect(DB::select('SELECT especial_evento FROM evento WHERE id_evento = ?', [$ev->id_evento]))->first()->especial_evento ?? null : null;
            
            // Si el evento ya viene con especial_evento podemos usarlo, pero si no, hay que extraerlo.
            // Para simplificar, obtenemos todos los especial_evento en una consulta
        }

        // Mejor obtener los detalles completos de los eventos para tener 'especial_evento'
        $eventosCompletos = DB::table('evento')
            ->join('detalle_evento', 'evento.id_evento', '=', 'detalle_evento.id_evento')
            ->select('evento.especial_evento', 'detalle_evento.dia_inicio_detalle_evento', 'detalle_evento.dia_fin_detalle_evento')
            ->where('detalle_evento.id_calendario_academico', $calendario->id_calendario_academico)
            ->where('evento.estatus', 1)
            ->get();

        foreach ($eventosCompletos as $ev) {
            $esp = (string) $ev->especial_evento;
            if ($esp === '2') $iniciosLapso[] = $ev->dia_inicio_detalle_evento;
            elseif ($esp === '3') $finesLapso[] = $ev->dia_fin_detalle_evento;
            elseif ($esp === '7') $iniciosIntro[] = $ev->dia_inicio_detalle_evento;
            elseif ($esp === '8') $finesIntro[] = $ev->dia_fin_detalle_evento;
            elseif ($esp === '9') $iniciosIntensivo[] = $ev->dia_inicio_detalle_evento;
            elseif ($esp === '10') $finesIntensivo[] = $ev->dia_fin_detalle_evento;
            elseif ($esp === '4' || $esp === '5' || $esp === '1') {
                $d = Carbon::parse($ev->dia_inicio_detalle_evento)->startOfDay();
                $dFin = Carbon::parse($ev->dia_fin_detalle_evento)->startOfDay();
                while ($d <= $dFin) {
                    $monday = $d->copy()->startOfWeek();
                    $semanasFestivasNormal[$monday->format('Y-m-d')] = true;
                    if ($esp !== '1') {
                        $semanasFestivasIntensivo[$monday->format('Y-m-d')] = true;
                    }
                    $d->addDay();
                }
            }
        }

        sort($iniciosLapso); sort($finesLapso);
        sort($iniciosIntro); sort($finesIntro);
        sort($iniciosIntensivo); sort($finesIntensivo);

        $weekLogic = [
            'iniciosLapso' => $iniciosLapso, 'finesLapso' => $finesLapso,
            'iniciosIntro' => $iniciosIntro, 'finesIntro' => $finesIntro,
            'iniciosIntensivo' => $iniciosIntensivo, 'finesIntensivo' => $finesIntensivo,
            'semanasFestivasNormal' => $semanasFestivasNormal,
            'semanasFestivasIntensivo' => $semanasFestivasIntensivo
        ];

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
            'weekLogic'          => $weekLogic,
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

    public function getWeekLabels($weekDates, $weekLogic)
    {
        $trVal = '';
        $niVal = '';

        if (empty($weekDates)) return ['TR' => '', 'NI' => ''];

        $mondayCurrent = null;
        foreach ($weekDates as $dStr) {
            $d = Carbon::parse($dStr);
            // El grid de Excel empieza en Domingo (D L M M J V S).
            // Si evaluamos Domingo con startOfWeek() en Carbon, nos da el Lunes de la semana pasada.
            // Para alinear la fila de Excel con las semanas reales (Lunes-Domingo), 
            // tomamos cualquier día de la fila que no sea Domingo para encontrar el Lunes actual.
            if ($d->dayOfWeek !== Carbon::SUNDAY) {
                $mondayCurrent = $d->copy()->startOfWeek();
                break;
            }
        }
        if (!$mondayCurrent) {
            // Si la fila solo tiene Domingo (ej. fin de mes)
            $mondayCurrent = Carbon::parse($weekDates[0])->startOfWeek();
        }
        
        $mondayCurrentStr = $mondayCurrent->format('Y-m-d');

        $getWeekCount = function($lapsoInicioStr, $isIntensivo = false) use ($mondayCurrentStr, $mondayCurrent, $weekLogic) {
            $festivas = $isIntensivo ? $weekLogic['semanasFestivasIntensivo'] : $weekLogic['semanasFestivasNormal'];
            
            if (isset($festivas[$mondayCurrentStr])) {
                return '';
            }

            $lapsoDate = Carbon::parse($lapsoInicioStr);
            $mondayInicioLapso = $lapsoDate->copy()->startOfWeek();
            $tempMonday = $mondayInicioLapso->copy();
            $weekIndex = 0;

            while ($tempMonday <= $mondayCurrent) {
                if (!isset($festivas[$tempMonday->format('Y-m-d')])) {
                    $weekIndex++;
                }
                $tempMonday->addWeek();
            }
            return $weekIndex;
        };

        // Determine Lapso
        $activeLapsoIndex = -1;
        $activeLapsoInicio = null;
        foreach ($weekLogic['iniciosLapso'] as $k => $iniL) {
            $finL = $weekLogic['finesLapso'][$k] ?? null;
            if (!$finL) continue;
            $hasLapso = false;
            foreach ($weekDates as $dStr) {
                if ($dStr >= $iniL && $dStr <= $finL) {
                    $hasLapso = true;
                    break;
                }
            }
            if ($hasLapso) {
                $activeLapsoIndex = $k;
                $activeLapsoInicio = $iniL;
                break;
            }
        }

        // Determine Intro
        $activeIntroIndex = -1;
        $activeIntroInicio = null;
        foreach ($weekLogic['iniciosIntro'] as $k => $iniL) {
            $finL = $weekLogic['finesIntro'][$k] ?? null;
            if (!$finL) continue;
            $hasLapso = false;
            foreach ($weekDates as $dStr) {
                if ($dStr >= $iniL && $dStr <= $finL) {
                    $hasLapso = true;
                    break;
                }
            }
            if ($hasLapso) {
                $activeIntroIndex = $k;
                $activeIntroInicio = $iniL;
                break;
            }
        }

        // Determine Intensivo
        $activeIntensivoIndex = -1;
        $activeIntensivoInicio = null;
        foreach ($weekLogic['iniciosIntensivo'] as $k => $iniL) {
            $finL = $weekLogic['finesIntensivo'][$k] ?? null;
            if (!$finL) continue;
            $hasLapso = false;
            foreach ($weekDates as $dStr) {
                if ($dStr >= $iniL && $dStr <= $finL) {
                    $hasLapso = true;
                    break;
                }
            }
            if ($hasLapso) {
                $activeIntensivoIndex = $k;
                $activeIntensivoInicio = $iniL;
                break;
            }
        }

        if ($activeLapsoIndex !== -1) {
            $weekIndex = $getWeekCount($activeLapsoInicio, false);
            if ($weekIndex !== '') {
                $suffixes = ['I', 'II', 'III', 'IV', 'V'];
                $suffix = $suffixes[$activeLapsoIndex] ?? 'I';
                $trVal = "{$weekIndex}{$suffix}";
            }
        } elseif ($activeIntensivoIndex !== -1) {
            $weekIndex = $getWeekCount($activeIntensivoInicio, true);
            if ($weekIndex !== '') {
                $trVal = "{$weekIndex}IN";
            }
        }

        if ($activeIntroIndex !== -1) {
            $weekIndex = $getWeekCount($activeIntroInicio, false);
            if ($weekIndex !== '') {
                $suffixes = ['I', 'II', 'III', 'IV'];
                $suffix = $suffixes[$activeIntroIndex] ?? 'I';
                $niVal = "{$weekIndex}{$suffix}";
            }
        }

        return ['TR' => $trVal, 'NI' => $niVal];
    }
}
