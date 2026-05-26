<table>
    {{-- Filas vacías iniciales para la imagen de cabecera --}}
    @for($i = 0; $i < 9; $i++)
        <tr>
            <td></td>
        </tr>
    @endfor

    @php
        $startDate = \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->startOfDay();
        $endDate = \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->endOfDay();
        $mesesNombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $f_nacionales = $eventos->where('tipo_evento', 1)->sortBy('dia_inicio_evento');
        $f_locales    = $eventos->where('tipo_evento', 2)->sortBy('dia_inicio_evento');
        $administrativos = $eventos->where('tipo_evento', 3)->sortBy('dia_inicio_evento');
        $academicos   = $eventos->where('tipo_evento', 4)->sortBy('dia_inicio_evento');
        $admin_acad   = $eventos->where('tipo_evento', 5)->sortBy('dia_inicio_evento');

        $vacacionesList = [];
        $otrosEventosAgrupados = [];

        // Helper function to check if it's a vacation event
        $isVacacion = function($e) {
            return stripos($e->descripcion_evento ?? '', 'vacacion') !== false || stripos($e->descripcion_evento ?? '', 'vacación') !== false;
        };

        // Helper function to format vacation ranges and count weekdays
        $formatearRangoVacaciones = function($e) {
            $mesesNombres = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
            $inicio = \Carbon\Carbon::parse($e->dia_inicio_evento);
            $fin = \Carbon\Carbon::parse($e->dia_fin_evento);
            
            $diasHabiles = 0;
            $current = $inicio->copy();
            $diasList = [];
            while ($current <= $fin) {
                if (!$current->isWeekend()) {
                    $diasHabiles++;
                    $diasList[] = $current->copy();
                }
                $current->addDay();
            }

            // Formatear texto
            $texto = $inicio->format('d/m/Y') . ' - ' . $fin->format('d/m/Y');

            return (object)[
                'isVacationRow' => true,
                'texto' => $texto,
                'dias' => $diasHabiles,
                'year' => $inicio->year
            ];
        };

        // Extraer Vacaciones agrupadas por año
        $vacacionesPorAno = [];
        foreach ($eventos as $e) {
            if ($isVacacion($e)) {
                $vacData = $formatearRangoVacaciones($e);
                if (!isset($vacacionesPorAno[$vacData->year])) {
                    $vacacionesPorAno[$vacData->year] = [];
                }
                $vacacionesPorAno[$vacData->year][] = $vacData;
            }
        }

        // Add other events to normal list
        if ($f_nacionales->count() > 0) {
            $otrosEventosAgrupados[] = (object)['isHeader' => true, 'label' => 'FERIADOS NACIONALES'];
            foreach ($f_nacionales as $e) { if (!$isVacacion($e)) $otrosEventosAgrupados[] = $e; }
        }
        if ($f_locales->count() > 0) {
            $otrosEventosAgrupados[] = (object)['isHeader' => true, 'label' => 'FERIADOS LOCALES'];
            foreach ($f_locales as $e) { if (!$isVacacion($e)) $otrosEventosAgrupados[] = $e; }
        }
        if ($administrativos->count() > 0) {
            $otrosEventosAgrupados[] = (object)['isHeader' => true, 'label' => 'ADMINISTRATIVO'];
            foreach ($administrativos as $e) { if (!$isVacacion($e)) $otrosEventosAgrupados[] = $e; }
        }
        if ($academicos->count() > 0) {
            $otrosEventosAgrupados[] = (object)['isHeader' => true, 'label' => 'ACADÉMICO'];
            foreach ($academicos as $e) { if (!$isVacacion($e)) $otrosEventosAgrupados[] = $e; }
        }
        if ($admin_acad->count() > 0) {
            $otrosEventosAgrupados[] = (object)['isHeader' => true, 'label' => 'ADMINISTRATIVO/ACADÉMICO'];
            foreach ($admin_acad as $e) { if (!$isVacacion($e)) $otrosEventosAgrupados[] = $e; }
        }

        // Clean up empty headers
        $cleanOtrosEventos = [];
        for ($i = 0; $i < count($otrosEventosAgrupados); $i++) {
            $curr = $otrosEventosAgrupados[$i];
            if (isset($curr->isHeader)) {
                $next = $otrosEventosAgrupados[$i + 1] ?? null;
                if ($next && !isset($next->isHeader)) {
                    $cleanOtrosEventos[] = $curr;
                }
            } else {
                $cleanOtrosEventos[] = $curr;
            }
        }

        // Merge to a single list with a spacer in between
        $eventosAgrupados = [];
        
        // Vacaciones Box
        if (count($vacacionesPorAno) > 0) {
            ksort($vacacionesPorAno);
            foreach ($vacacionesPorAno as $ano => $listaVacaciones) {
                $eventosAgrupados[] = (object)['isVacationYearHeader' => true, 'label' => 'VACACIONES COLECTIVAS'];
                $eventosAgrupados[] = (object)['isVacationSubHeader' => true];
                $totalDias = 0;
                foreach ($listaVacaciones as $v) {
                    $eventosAgrupados[] = $v;
                    $totalDias += $v->dias;
                }
                $eventosAgrupados[] = (object)['isVacationTotal' => true, 'total' => $totalDias];
            }
            $eventosAgrupados[] = (object)['isSpacer' => true];
        }

        // Eventos del Calendario Box
        if (count($cleanOtrosEventos) > 0) {
            $eventosAgrupados[] = (object)['isMainHeader' => true, 'label' => 'EVENTOS DEL CALENDARIO'];
            $eventosAgrupados[] = (object)['isSubHeader' => true];
            foreach ($cleanOtrosEventos as $e) {
                $eventosAgrupados[] = $e;
            }
        }

        $eventosSorted = collect($eventosAgrupados);
        $totalEventos = count($eventosSorted);
        $eventoIndex = 0;
        $isFirstYear = true;
    @endphp

    {{-- Título General con Rango de Años --}}
    <thead style="font-weight: bold;">
        <tr>
            <th colspan="39" style="text-align: center; font-size: 14pt;">CALENDARIO ACADÉMICO {{ $startYear }} -
                {{ $endYear }}</th>
        </tr>
        <tr>
            <th colspan="39" style="text-align: center;"><strong>Vigencia:</strong>
                {{ \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->format('d/m/Y') }} hasta
                {{ \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->format('d/m/Y') }}</th>
        </tr>
    </thead>

    @php
        // Recopilar todos los meses válidos de todos los años en una sola lista plana
        $listaMesesCompleta = [];
        foreach ($years as $yearLoop) {
            foreach (range(1, 12) as $mes) {
                $primerDiaMes = \Carbon\Carbon::create($yearLoop, $mes, 1)->startOfDay();
                $ultimoDiaMes = $primerDiaMes->copy()->endOfMonth()->endOfDay();
                if ($primerDiaMes <= $endDate && $ultimoDiaMes >= $startDate) {
                    $listaMesesCompleta[] = [
                        'year' => $yearLoop,
                        'month' => $mes
                    ];
                }
            }
        }
        $mesesChunks = array_chunk($listaMesesCompleta, 3);
    @endphp

    <tbody>
        @foreach($mesesChunks as $chunkIndex => $chunk)
            <tr>
                <td colspan="30"></td>
                @if($chunkIndex > 0)
                    @if($eventoIndex < $totalEventos)
                        @php $evento = $eventosSorted[$eventoIndex]; @endphp
                        @if(isset($evento->isVacationYearHeader))
                            <td colspan="9" style="text-align: center; font-weight: bold; color: red; border: 1px solid #000; font-size: 11pt;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isVacationSubHeader))
                            <td colspan="6" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="1" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                        @elseif(isset($evento->isVacationRow))
                            <td colspan="6" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->texto }}</td>
                            <td colspan="1" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->dias }}</td>
                            <td colspan="2" style="text-align: center; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isVacationTotal))
                            <td colspan="6" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">TOTAL</td>
                            <td colspan="1" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">{{ $evento->total }}</td>
                            <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isMainHeader))
                            <td colspan="9" style="text-align: center; background-color: #f2f2f2; border: 1px solid #000; font-size: 11pt; font-weight: bold;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isSubHeader))
                            <td colspan="4" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: left; padding-left: 5px;">Evento</td>
                            <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Condición</td>
                        @elseif(isset($evento->isSpacer))
                            <td colspan="9"></td>
                        @elseif(isset($evento->isHeader))
                            <td colspan="9" style="background-color: #f2f2f2; border: 1px solid #000; font-weight: bold; font-size: 11pt; text-align: center;">{{ $evento->label }}</td>
                        @else
                            <td style="border: 0.5px solid #000; background-color: {{ $eventColors[$evento->id_evento] ?? '#ffffff' }}; width: 10px;"></td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: left; padding-left: 5px;">{{ $evento->descripcion_evento }}</td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m/Y') }}
                            </td>
                            <td colspan="2" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ $evento->is_laborable_evento ? 'Laborable' : 'No Laborable' }}
                            </td>
                        @endif
                        @php $eventoIndex++; @endphp
                    @else
                        <td colspan="9"></td>
                    @endif
                @else
                    <td colspan="9"></td>
                @endif
            </tr>
            {{-- Nombres de meses --}}
            <tr style="font-weight: bold;">
                @foreach($chunk as $item)
                    @php 
                        $m = $item['month']; 
                        $y = $item['year'];
                    @endphp
                    <td colspan="9" style="text-align: center; border: 0.5px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold;">
                        {{ $mesesNombres[$m - 1] }} {{ $y }}</td>
                    <td style="width: 20px;"></td>
                @endforeach
                @if(count($chunk) < 3)
                    <td colspan="{{ (3 - count($chunk)) * 10 }}"></td>
                @endif
                @if($chunkIndex == 0)
                    @if($eventoIndex < $totalEventos)
                        @php $evento = $eventosSorted[$eventoIndex]; @endphp
                        @if(isset($evento->isVacationYearHeader))
                            <td colspan="9" style="text-align: center; font-weight: bold; color: red; border: 1px solid #000; font-size: 11pt;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isVacationSubHeader))
                            <td colspan="6" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="1" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                        @elseif(isset($evento->isVacationRow))
                            <td colspan="6" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->texto }}</td>
                            <td colspan="1" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->dias }}</td>
                            <td colspan="2" style="text-align: center; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isVacationTotal))
                            <td colspan="6" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">TOTAL</td>
                            <td colspan="1" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">{{ $evento->total }}</td>
                            <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isMainHeader))
                            <td colspan="9" style="text-align: center; background-color: #f2f2f2; border: 1px solid #000; font-size: 11pt; font-weight: bold;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isSubHeader))
                            <td colspan="4" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: left; padding-left: 5px;">Evento</td>
                            <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Condición</td>
                        @elseif(isset($evento->isSpacer))
                            <td colspan="9"></td>
                        @elseif(isset($evento->isHeader))
                            <td colspan="9" style="background-color: #f2f2f2; border: 1px solid #000; font-weight: bold; font-size: 11pt; text-align: center;">{{ $evento->label }}</td>
                        @else
                            <td style="border: 0.5px solid #000; background-color: {{ $eventColors[$evento->id_evento] ?? '#ffffff' }}; width: 10px;"></td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: left; padding-left: 5px;">{{ $evento->descripcion_evento }}</td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m/Y') }}
                            </td>
                            <td colspan="2" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ $evento->is_laborable_evento ? 'Laborable' : 'No Laborable' }}
                            </td>
                        @endif
                        @php $eventoIndex++; @endphp
                    @else
                        <td colspan="9"></td>
                    @endif
                @else
                    @if($eventoIndex < $totalEventos)
                        @php $evento = $eventosSorted[$eventoIndex]; @endphp
                        @if(isset($evento->isVacationYearHeader))
                            <td colspan="9" style="text-align: center; font-weight: bold; color: red; border: 1px solid #000; font-size: 11pt;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isVacationSubHeader))
                            <td colspan="6" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="1" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                        @elseif(isset($evento->isVacationRow))
                            <td colspan="6" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->texto }}</td>
                            <td colspan="1" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->dias }}</td>
                            <td colspan="2" style="text-align: center; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isVacationTotal))
                            <td colspan="6" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">TOTAL</td>
                            <td colspan="1" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">{{ $evento->total }}</td>
                            <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isMainHeader))
                            <td colspan="9" style="text-align: center; background-color: #f2f2f2; border: 1px solid #000; font-size: 11pt; font-weight: bold;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isSubHeader))
                            <td colspan="4" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: left; padding-left: 5px;">Evento</td>
                            <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Condición</td>
                        @elseif(isset($evento->isSpacer))
                            <td colspan="9"></td>
                        @elseif(isset($evento->isHeader))
                            <td colspan="9" style="background-color: #f2f2f2; border: 1px solid #000; font-weight: bold; font-size: 11pt; text-align: center;">{{ $evento->label }}</td>
                        @else
                            <td style="border: 0.5px solid #000; background-color: {{ $eventColors[$evento->id_evento] ?? '#ffffff' }}; width: 10px;"></td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: left; padding-left: 5px;">{{ $evento->descripcion_evento }}</td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m/Y') }}
                            </td>
                            <td colspan="2" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ $evento->is_laborable_evento ? 'Laborable' : 'No Laborable' }}
                            </td>
                        @endif
                        @php $eventoIndex++; @endphp
                    @else
                        <td colspan="9"></td>
                    @endif
                @endif
            </tr>

            {{-- Cabecera días --}}
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                @foreach($chunk as $item)
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 11pt;">D</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 11pt;">L</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 11pt;">M</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 11pt;">M</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 11pt;">J</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 11pt;">V</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 11pt;">S</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 10pt; color: #ef4444;">TR</td>
                    <td style="border: 0.5px solid #000; text-align: center; font-size: 10pt; color: #0ea5e9;">NI</td>
                    <td></td>
                @endforeach
                @if(count($chunk) < 3)
                    <td colspan="{{ (3 - count($chunk)) * 10 }}"></td>
                @endif
                @if($eventoIndex < $totalEventos)
                    @php $evento = $eventosSorted[$eventoIndex]; @endphp
                    @if(isset($evento->isVacationYearHeader))
                        <td colspan="9" style="text-align: center; font-weight: bold; color: red; border: 1px solid #000; font-size: 11pt;">{{ $evento->label }}</td>
                    @elseif(isset($evento->isVacationSubHeader))
                        <td colspan="6" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                        <td colspan="1" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                        <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                    @elseif(isset($evento->isVacationRow))
                        <td colspan="6" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->texto }}</td>
                        <td colspan="1" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->dias }}</td>
                        <td colspan="2" style="text-align: center; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                    @elseif(isset($evento->isVacationTotal))
                        <td colspan="6" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">TOTAL</td>
                        <td colspan="1" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">{{ $evento->total }}</td>
                        <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                    @elseif(isset($evento->isMainHeader))
                        <td colspan="9" style="text-align: center; background-color: #f2f2f2; border: 1px solid #000; font-size: 11pt; font-weight: bold;">{{ $evento->label }}</td>
                    @elseif(isset($evento->isSubHeader))
                        <td style="border: 1px solid #000; background-color: #f2f2f2; width: 10px;"></td>
                        <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: left; padding-left: 5px;">Evento</td>
                        <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                        <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Condición</td>
                    @elseif(isset($evento->isSpacer))
                        <td colspan="9"></td>
                    @elseif(isset($evento->isHeader))
                        <td colspan="9" style="background-color: #f2f2f2; border: 1px solid #000; font-weight: bold; font-size: 11pt; text-align: center;">{{ $evento->label }}</td>
                    @else
                        <td style="border: 0.5px solid #000; background-color: {{ $eventColors[$evento->id_evento] ?? '#ffffff' }}; width: 10px;"></td>
                        <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: left; padding-left: 5px;">{{ $evento->descripcion_evento }}</td>
                        <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                            {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m/Y') }}
                        </td>
                        <td colspan="2" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                            {{ $evento->is_laborable_evento ? 'Laborable' : 'No Laborable' }}
                        </td>
                    @endif
                    @php $eventoIndex++; @endphp
                @else
                    <td colspan="9"></td>
                @endif
            </tr>

            {{-- Semanas --}}
            @for($numFila = 0; $numFila < 6; $numFila++)
                <tr>
                    @foreach($chunk as $item)
                        @php
                            $yLoop = $item['year'];
                            $mLoop = $item['month'];
                            $eventDays = $eventDaysByYear[$yLoop] ?? [];
                            $currentMonth = \Carbon\Carbon::create($yLoop, $mLoop, 1);
                            $daysInMonth = $currentMonth->daysInMonth;
                            $startDayOfWeek = $currentMonth->dayOfWeek;
                        @endphp
                        @for($col = 0; $col < 7; $col++)
                            @php
                                $diaNum = ($numFila * 7 + $col) - $startDayOfWeek + 1;
                                $cellDate = null;
                                if ($diaNum >= 1 && $diaNum <= $daysInMonth) {
                                    $cellDate = \Carbon\Carbon::create($yLoop, $mLoop, $diaNum)->startOfDay();
                                }
                                $isVigente = ($cellDate && $cellDate->between($startDate, $endDate));
                                $eventData = ($cellDate && isset($eventDays[$cellDate->format('Y-m-d')])) ? $eventDays[$cellDate->format('Y-m-d')] : null;
                                $eventId = $eventData ? $eventData['ids'][0] : null;
                                $isWeekend = ($col == 0 || $col == 6);

                                if ($eventId && !$isWeekend) {
                                    $bgColor = $eventColors[$eventId] ?? '#ffffff';
                                    $textColor = '#ffffff';
                                } elseif ($isWeekend) {
                                    $bgColor = '#ffffff';
                                    $textColor = ($isVigente || $cellDate) ? '#DC3545' : '#ffffff';
                                } else {
                                    $bgColor = '#ffffff';
                                    $textColor = $isVigente ? '#000000' : ($cellDate ? '#bbbbbb' : '#ffffff');
                                }
                            @endphp
                            <td
                                style="border: 0.5px solid #000; text-align: center; background-color: {{ $bgColor }}; color: {{ $textColor }}; font-size: 11pt; {{ ($isVigente && !$eventId) ? 'font-weight: bold;' : '' }}">
                                {{ ($diaNum >= 1 && $diaNum <= $daysInMonth) ? $diaNum : '' }}
                            </td>
                        @endfor

                        {{-- Lógica TR y NI en Excel --}}
                        @php
                            $weekDates = [];
                            for($col = 0; $col < 7; $col++) {
                                $diaNum = ($numFila * 7 + $col) - $startDayOfWeek + 1;
                                if ($diaNum >= 1 && $diaNum <= $daysInMonth) {
                                    $weekDates[] = \Carbon\Carbon::create($yLoop, $mLoop, $diaNum)->format('Y-m-d');
                                }
                            }
                            $repo = new \App\Repositories\Calendario\CalendarioExcelRepo();
                            $labels = $repo->getWeekLabels($weekDates, $weekLogic);
                        @endphp
                        <td style="border: 0.5px solid #000; text-align: center; background-color: #f9fafb; color: #111827; font-size: 10pt; font-weight: bold;">
                            {{ $labels['TR'] }}
                        </td>
                        <td style="border: 0.5px solid #000; text-align: center; background-color: #f0f9ff; color: #0ea5e9; font-size: 10pt; font-weight: bold;">
                            {{ $labels['NI'] }}
                        </td>

                        <td></td>
                    @endforeach
                    @if(count($chunk) < 3)
                        <td colspan="{{ (3 - count($chunk)) * 10 }}"></td>
                    @endif

                    {{-- Eventos --}}
                    @if($eventoIndex < $totalEventos)
                        @php $evento = $eventosSorted[$eventoIndex]; @endphp
                        @if(isset($evento->isVacationYearHeader))
                            <td colspan="9" style="text-align: center; font-weight: bold; color: red; border: 1px solid #000; font-size: 11pt;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isVacationSubHeader))
                            <td colspan="6" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="1" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;"></td>
                        @elseif(isset($evento->isVacationRow))
                            <td colspan="6" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->texto }}</td>
                            <td colspan="1" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->dias }}</td>
                            <td colspan="2" style="text-align: center; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isVacationTotal))
                            <td colspan="6" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">TOTAL</td>
                            <td colspan="1" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">{{ $evento->total }}</td>
                            <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                        @elseif(isset($evento->isMainHeader))
                            <td colspan="9" style="text-align: center; background-color: #f2f2f2; border: 1px solid #000; font-size: 11pt; font-weight: bold;">{{ $evento->label }}</td>
                        @elseif(isset($evento->isSubHeader))
                            <td colspan="4" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: left; padding-left: 5px;">Evento</td>
                            <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                            <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Condición</td>
                        @elseif(isset($evento->isSpacer))
                            <td colspan="9"></td>
                        @elseif(isset($evento->isHeader))
                            <td colspan="9" style="background-color: #f2f2f2; border: 1px solid #000; font-weight: bold; font-size: 11pt; text-align: center;">{{ $evento->label }}</td>
                        @else
                            <td
                                style="border: 0.5px solid #000; background-color: {{ $eventColors[$evento->id_evento] ?? '#ffffff' }}; width: 10px;">
                            </td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: left; padding-left: 5px;">{{ $evento->descripcion_evento }}</td>
                            <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m/Y') }}
                            </td>
                            <td colspan="2" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                                {{ $evento->is_laborable_evento ? 'Laborable' : 'No Laborable' }}
                            </td>
                        @endif
                        @php $eventoIndex++; @endphp
                    @else
                        <td colspan="9"></td>
                    @endif
                </tr>
            @endfor
        @endforeach

        {{-- Eventos restantes --}}
        @while($eventoIndex < $totalEventos)
            <tr>
                <td colspan="30"></td>
                @php $evento = $eventosSorted[$eventoIndex]; @endphp
                @if(isset($evento->isVacationYearHeader))
                    <td colspan="9" style="text-align: center; font-weight: bold; color: red; border: 1px solid #000; font-size: 11pt;">{{ $evento->label }}</td>
                @elseif(isset($evento->isVacationRow))
                    <td colspan="6" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->texto }}</td>
                    <td colspan="1" style="text-align: center; border: 1px solid #000; font-size: 11pt;">{{ $evento->dias }}</td>
                    <td colspan="2" style="text-align: center; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                @elseif(isset($evento->isVacationTotal))
                    <td colspan="6" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">TOTAL</td>
                    <td colspan="1" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">{{ $evento->total }}</td>
                    <td colspan="2" style="text-align: center; font-weight: bold; border: 1px solid #000; font-size: 11pt;">DIAS</td>
                @elseif(isset($evento->isMainHeader))
                    <td colspan="9" style="text-align: center; background-color: #f2f2f2; border: 1px solid #000; font-size: 11pt; font-weight: bold;">{{ $evento->label }}</td>
                @elseif(isset($evento->isSubHeader))
                    <td colspan="4" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: left; padding-left: 5px;">Evento</td>
                    <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Fecha</td>
                    <td colspan="2" style="border: 1px solid #000; background-color: #f2f2f2; font-size: 11pt; font-weight: bold; text-align: center;">Condición</td>
                @elseif(isset($evento->isSpacer))
                    <td colspan="9"></td>
                @elseif(isset($evento->isHeader))
                    <td colspan="9" style="background-color: #f2f2f2; border: 1px solid #000; font-weight: bold; font-size: 11pt; text-align: center;">{{ $evento->label }}</td>
                @else
                    <td
                        style="border: 0.5px solid #000; background-color: {{ $eventColors[$evento->id_evento] ?? '#ffffff' }}; width: 10px;">
                    </td>
                    <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: left; padding-left: 5px;">{{ $evento->descripcion_evento }}</td>
                    <td colspan="3" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                        {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m/Y') }} -
                        {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m/Y') }}
                    </td>
                    <td colspan="2" style="border: 0.5px solid #000; font-size: 11pt; text-align: center;">
                        {{ $evento->is_laborable_evento ? 'Laborable' : 'No Laborable' }}
                    </td>
                @endif
                @php $eventoIndex++; @endphp
            </tr>
        @endwhile
    </tbody>
</table>