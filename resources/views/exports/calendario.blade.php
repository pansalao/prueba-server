<table>
    {{-- Filas vacías iniciales para empujar el título a la fila 8 --}}
    @for($i = 0; $i < 9; $i++)
        <tr><td></td></tr>
    @endfor
    
    <thead style="font-weight: bold;">
        <tr>
            <th colspan="23" style="text-align: center; font-size: 14pt;">CALENDARIO ACADÉMICO {{ $year }}</th>
        </tr>
        <tr>
            <th colspan="23" style="text-align: center;"><strong>Vigencia:</strong> {{ \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->format('d/m/Y') }} hasta {{ \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->format('d/m/Y') }}</th>
        </tr>
    </thead>
    <tbody>
        @php
            $startDate = \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->startOfDay();
            $endDate = \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->endOfDay();
            $mesesNombres = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            
            // Filtrar solo meses que tengan al menos un día dentro de la vigencia
            $mesesValidos = [];
            foreach (range(1, 12) as $mes) {
                $primerDiaMes = \Carbon\Carbon::create($year, $mes, 1)->startOfDay();
                $ultimoDiaMes = $primerDiaMes->copy()->endOfMonth()->endOfDay();
                // El mes es válido si su rango se solapa con la vigencia del calendario
                if ($primerDiaMes <= $endDate && $ultimoDiaMes >= $startDate) {
                    $mesesValidos[] = $mes;
                }
            }
            // Agrupar meses válidos en filas de 3
            $mesesChunks = array_chunk($mesesValidos, 3);
            $eventosSorted = $eventos->sortBy('dia_inicio_evento')->values();
            $totalEventos = count($eventosSorted);
            $eventoIndex = 0;
        @endphp

        @foreach($mesesChunks as $chunkIndex => $chunk)
            <tr><td colspan="31"></td></tr>
            {{-- Fila de Nombres de Mes --}}
            <tr style="font-weight: bold;">
                @foreach($chunk as $m)
                    <td colspan="7" style="text-align: center; border: 0.5px solid #000; background-color: #f2f2f2;">{{ $mesesNombres[$m-1] }} {{ $year }}</td>
                    <td style="width: 20px;"></td> {{-- Siempre un espacio --}}
                @endforeach
                {{-- Cabecera de Eventos solo en la primera fila de meses --}}
                @if($chunkIndex == 0)
                    <td colspan="7" style="text-align: center; background-color: #f2f2f2; border: 1px solid #000;">EVENTOS DEL CALENDARIO</td>
                @else
                    <td colspan="7"></td>
                @endif
            </tr>
            
            {{-- Fila de Cabecera de Días (D L M M J V S) y Cabecera de tabla de eventos --}}
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                @foreach($chunk as $m)
                    <td style="border: 0.5px solid #000; text-align: center;">D</td>
                    <td style="border: 0.5px solid #000; text-align: center;">L</td>
                    <td style="border: 0.5px solid #000; text-align: center;">M</td>
                    <td style="border: 0.5px solid #000; text-align: center;">M</td>
                    <td style="border: 0.5px solid #000; text-align: center;">J</td>
                    <td style="border: 0.5px solid #000; text-align: center;">V</td>
                    <td style="border: 0.5px solid #000; text-align: center;">S</td>
                    <td></td>
                @endforeach
                @if($chunkIndex == 0)
                    <td colspan="4" style="border: 1px solid #000; background-color: #f2f2f2;">Evento</td>
                    <td colspan="3" style="border: 1px solid #000; background-color: #f2f2f2;">Fecha</td>
                @else
                    <td colspan="7"></td>
                @endif
            </tr>

            {{-- Filas de Semanas + Items de Eventos dinámicos --}}
            @for($numFila = 0; $numFila < 6; $numFila++)
                <tr>
                    @foreach($chunk as $m)
                        @php
                            $currentMonth = \Carbon\Carbon::create($year, $m, 1);
                            $daysInMonth = $currentMonth->daysInMonth;
                            $startDayOfWeek = $currentMonth->dayOfWeek;
                        @endphp
                        
                        @for($col = 0; $col < 7; $col++)
                            @php
                                $diaNum = ($numFila * 7 + $col) - $startDayOfWeek + 1;
                                $cellDate = null;
                                if($diaNum >= 1 && $diaNum <= $daysInMonth) {
                                    $cellDate = \Carbon\Carbon::create($year, $m, $diaNum)->startOfDay();
                                }
                                $isVigente = ($cellDate && $cellDate->between($startDate, $endDate));
                                $eventId = ($cellDate && isset($eventDays[$cellDate->format('Y-m-d')])) ? $eventDays[$cellDate->format('Y-m-d')] : null;
                                
                                // Color para fines de semana (D=0, S=6)
                                $isWeekend = ($col == 0 || $col == 6);
                                
                                if ($eventId) {
                                    $bgColor = $eventColors[$eventId] ?? '#ffffff';
                                    // Condición solicitada: evento + fin de semana = letras rojas, sino blancas
                                    $textColor = $isWeekend ? '#DC3545' : '#ffffff';
                                } elseif ($isVigente && $isWeekend) {
                                    $bgColor = '#ffffff'; 
                                    $textColor = '#DC3545'; // Letras en Rojo (fin de semana sin evento)
                                } else {
                                    $bgColor = '#ffffff';
                                    $textColor = $isVigente ? '#000000' : ($cellDate ? '#bbbbbb' : '#ffffff');
                                }
                            @endphp
                            <td style="border: 0.5px solid #000; text-align: center; background-color: {{ $bgColor }}; color: {{ $textColor }}; {{ ($isVigente && !$eventId) ? 'font-weight: bold;' : '' }}">
                                {{ ($diaNum >= 1 && $diaNum <= $daysInMonth) ? $diaNum : '' }}
                            </td>
                        @endfor
                        <td></td>
                    @endforeach
                    
                    {{-- Imprimir el siguiente evento si existe --}}
                    @if($eventoIndex < $totalEventos)
                        @php $evento = $eventosSorted[$eventoIndex]; @endphp
                        <td style="border: 0.5px solid #000; background-color: {{ $eventColors[$evento->id_evento] ?? '#ffffff' }}; width: 10px;"></td>
                        <td colspan="3" style="border: 0.5px solid #000; font-size: 8pt;">{{ $evento->descripcion_evento }}</td>
                        <td colspan="3" style="border: 0.5px solid #000; font-size: 8pt; text-align: center;">
                            {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m') }} - {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m') }}
                        </td>
                        @php $eventoIndex++; @endphp
                    @else
                        <td colspan="7"></td>
                    @endif
                </tr>
            @endfor
        @endforeach
        
        {{-- Si sobran eventos, los imprimimos al final --}}
        @while($eventoIndex < $totalEventos)
            <tr>
                <td colspan="24"></td>
                @php $evento = $eventosSorted[$eventoIndex]; @endphp
                <td colspan="4" style="border: 0.5px solid #000; font-size: 8pt;">{{ $evento->descripcion_evento }}</td>
                <td colspan="3" style="border: 0.5px solid #000; font-size: 8pt; text-align: center;">
                    {{ \Carbon\Carbon::parse($evento->dia_inicio_evento)->format('d/m') }} - {{ \Carbon\Carbon::parse($evento->dia_fin_evento)->format('d/m') }}
                </td>
            </tr>
            @php $eventoIndex++; @endphp
        @endwhile
    </tbody>
</table>
