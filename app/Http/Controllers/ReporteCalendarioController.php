<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\CalendarioExport;
use Maatwebsite\Excel\Facades\Excel;

class ReporteCalendarioController extends Controller
{
    /**
     * Genera el reporte del último calendario académico.
     */
    public function reporteUltimoCalendario()
    {
        $calendario = DB::table('calendario_academico')
            ->where('estatus', 1)
            ->orderBy('id_calendario_academico', 'desc')
            ->first();

        if (!$calendario) {
            return redirect()->back()->with('error', 'No existe ningún calendario académico para imprimir.');
        }

        // Determinar el año a mostrar (el año del inicio del calendario)
        $year = Carbon::parse($calendario->dia_inicio_calendario_academico)->year;

        // Obtener días con eventos para este año
        $eventosRaw = DB::table('evento')
            ->whereYear('dia_inicio_evento', $year)
            ->where('estatus', 1)
            ->get();

        $eventDays = [];
        $eventColors = [];
        $palette = [
            '#007BFF', '#28A745', '#DC3545', '#FD7E14', '#6610F2', 
            '#E83E8C', '#20C997', '#17A2B8', '#FFC107', '#6F42C1',
            '#004085', '#155724', '#721C24', '#856404', '#0C5460'
        ];

        foreach($eventosRaw as $index => $eventoItem) {
            // Asignar color único al evento (si hay muchos repetirá paleta)
            $color = $palette[$index % count($palette)];
            $eventColors[$eventoItem->id_evento] = $color;

            $start = Carbon::parse($eventoItem->dia_inicio_evento);
            $end = Carbon::parse($eventoItem->dia_fin_evento);
            
            while($start <= $end) {
                if ($start->year == $year) {
                    $eventDays[$start->format('Y-m-d')] = $eventoItem->id_evento;
                }
                $start->addDay();
            }
        }

        return Excel::download(new CalendarioExport([
            'calendario' => $calendario,
            'year' => $year,
            'eventDays' => $eventDays,
            'eventColors' => $eventColors,
            'eventos' => $eventosRaw
        ]), 'calendario_academico_' . $year . '.xlsx');
    }
}
