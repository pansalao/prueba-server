<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Planificación Académica #{{ $planificacion->planificacion_id }}</title>
    <style>
        @page {
            margin: 140px 30px 60px 30px;
        }

        header {
            position: fixed;
            top: -120px;
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 40px;
            text-align: center;
            font-size: 7pt;
            color: #333;
            border-top: 1px solid #000;
        }

        .footer-text {
            margin: 1px 0;
            line-height: 1.1;
        }

        body {
            font-family: sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            color: #000;
        }

        .pagenum:before {
            content: counter(page);
        }

        .table-header-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
        }

        .table-header-grid td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 7.5pt;
        }

        .label-cell {
            font-weight: bold;
            text-align: center;
            background-color: #f2f2f2;
        }

        .content-cell {
            text-align: center;
        }

        .planning-title {
            text-align: center;
            font-weight: bold;
            font-size: 10pt;
            padding: 5px;
            border: 1px solid #000;
            border-top: none;
            background-color: #f2f2f2;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .main-table th {
            text-align: center;
            font-weight: bold;
            background-color: #f2f2f2;
            font-size: 7.5pt;
        }

        .main-table td {
            font-size: 7.5pt;
        }

        .uppercase {
            text-transform: uppercase;
        }

        ul {
            margin: 0;
            padding-left: 12px;
        }

        li {
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ public_path('img/reportes.jpg') }}" style="width: 100%; height: auto; max-height: 100px;">
    </header>

    <footer>
        <div class="footer-text">U.P.T.P. - Avenida Circunvalación Sur, sector Bellas Artes, diagonal a la Cruz Roja.
            Acarigua Edo. Portuguesa, República Bolivariana de Venezuela. R.I.F.: G-20010200-4.</div>
        <div class="footer-text">Zona postal 3301, Apartado N° 108. Teléfonos: (0255) 623.7538 - 623.7519 - 623.6085 |
            http://uptp.sytes.net</div>
        <div class="footer-text">Página <span class="pagenum"></span> - {{ now()->format('d/m/Y h:i a') }} - INGJ.T
        </div>
    </footer>

    {{-- Nuevo Header Grid --}}
    <table class="table-header-grid">
        <tr>
            <td class="label-cell" width="25%">PNF<br><span
                    class="uppercase">{{ $planificacion->nombre_pnf ?? 'INFORMÁTICA' }}</span></td>
            <td class="label-cell" colspan="2">Unidad curricular:<br><span
                    class="uppercase">{{ $planificacion->nombre_unidad_curricular }}</span></td>
            <td class="label-cell">Trayecto: <span
                    class="uppercase">{{ $planificacion->trayecto_unidad_curricular ?? 'N/A' }}</span></td>
            <td class="label-cell">Semestre: <span
                    class="uppercase">{{ $planificacion->duracion_unidad_curricular ?? 'N/A' }}</span></td>
            <td class="label-cell">Secciones: <span class="uppercase">{{ $planificacion->nombre_seccion }}</span></td>
        </tr>
        <tr>
            <td colspan="2" class="content-cell">Coordinador (a) de eje:
                <strong>{{ $planificacion->coordinador_nombre }} {{ $planificacion->coordinador_apellido }}</strong>
                V-{{ $planificacion->coordinador_cedula }}</td>
            <td class="content-cell">Horas semanales:
                <strong>{{ $planificacion->horas_semanales_unidad_curricular ?? '' }}</strong></td>
            <td class="content-cell">Lapso académico: <strong>{{ $planificacion->nombre_lapso }}</strong></td>
            <td class="content-cell">Código:<br><strong></strong></td>
            <td class="content-cell">Unidades de crédito:
                <strong>{{ $planificacion->unidades_credito_unidad_curricular ?? '' }}</strong></td>
        </tr>
        <tr>
            <td colspan="6" class="content-cell">Profesor(a) que administra la unidad curricular:
                <strong>{{ $planificacion->docente_nombre }} {{ $planificacion->docente_apellido }}</strong></td>
        </tr>
        <tr>
            <td colspan="6" class="content-cell">Propósito de la unidad curricular:
                {{ $planificacion->proposito_unidad_curricular ?? '' }}</td>
        </tr>
    </table>

    <div class="planning-title uppercase">PLANIFICACIÓN ACADÉMICA Y DE EVALUACIÓN</div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="6%">Semana (Horas y/o minutos)</th>
                <th width="18%">Temática general
                    <hr>Unidad/objetivo/ contenido
                </th>
                <th width="12%">Estrategia pedagógica
                    <hr>Actividad/ Técnica
                </th>
                <th width="8%">Recursos</th>
                <th width="13%">Indicadores de logros</th>
                <th width="13%">Técnicas /Instrumentos y tipos de Evaluación</th>
                <th width="12%">Forma de Participación</th>
                <th width="8%">Ponderación</th>
                <th width="10%">Bibliografía Sugerida</th>
            </tr>
        </thead>
        <tbody>
            @foreach($planificacion->unidades as $unidad)
                <tr>
                    {{-- Semana --}}
                    <td class="content-cell">
                        @foreach($unidad['evaluaciones'] as $eval)
                            {{ \Carbon\Carbon::parse($eval['fecha_evaluacion'])->format('d/m/y') }}<br>
                        @endforeach
                    </td>

                    {{-- Temática --}}
                    <td>
                        @foreach($unidad['contenidos'] as $cont)
                            <strong>{{ $cont['titulo_contenido'] }}</strong><br>
                            <small>Tema: {{ $cont['titulo_tema'] ?? '' }}</small>
                            <br><br>
                        @endforeach
                    </td>

                    {{-- Estrategia --}}
                    <td>
                        @foreach($unidad['estrategias'] as $est)
                            - {{ $est['titulo_tema'] ?? '' }}@if(!empty($est['actividad'])): {{ $est['actividad'] }}@endif<br>
                        @endforeach
                    </td>

                    {{-- Recursos --}}
                    <td>
                        @foreach($unidad['recursos'] as $rec)
                            - {{ $rec['recurso'] }}<br>
                        @endforeach
                    </td>

                    {{-- Indicadores --}}
                    <td>
                        {{ $unidad['indicadores_logro'] ?? '' }}
                    </td>

                    {{-- Técnicas / Instrumentos --}}
                    <td>
                        @foreach($unidad['evaluaciones'] as $eval)
                            - {{ $eval['tecnica'] }} ({{ $eval['evaluacion'] }})<br>
                        @endforeach
                    </td>

                    {{-- Forma de Participación --}}
                    <td class="content-cell">
                        @foreach($unidad['evaluaciones'] as $eval)
                            @if($eval['forma_participacion'] == 1) Individual
                            @elseif($eval['forma_participacion'] == 2) Grupal ({{ $eval['integrantes'] ?? '' }})
                            @endif
                            <br>
                        @endforeach
                    </td>

                    {{-- Ponderación --}}
                    <td class="content-cell">
                        @php $totalUnidad = 0; @endphp
                        @foreach($unidad['evaluaciones'] as $eval)
                            {{ $eval['ponderacion'] }}%<br>
                            @php $totalUnidad += $eval['ponderacion']; @endphp
                        @endforeach
                        <hr>
                        <strong>TOTAL {{ $totalUnidad }}%</strong>
                    </td>

                    {{-- Bibliografía --}}
                    <td>
                        @if(!empty($unidad['bibliografias']))
                            @foreach($unidad['bibliografias'] as $bib)
                                - {{ $bib['bibliografia'] ?? '' }}<br>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br><br>
    <table width="100%" style="margin-top: 30px; text-align: center; border: none;">
        <tr>
            <td width="33%" style="border: none;">
                @if(!empty($planificacion->firma_docente_b64))
                    <img src="{{ $planificacion->firma_docente_b64 }}" style="max-height: 50px; max-width: 150px;"><br>
                @else
                    <br><br><br>
                @endif
                ________________________________<br>
                <strong>Firma del Docente</strong>
            </td>
            <td width="33%" style="border: none;">
                @if(!empty($planificacion->firma_vocero_b64))
                    <img src="{{ $planificacion->firma_vocero_b64 }}" style="max-height: 50px; max-width: 150px;"><br>
                @else
                    <br><br><br>
                @endif
                ________________________________<br>
                <strong>Firma del Vocero(a)</strong>
            </td>
            <td width="33%" style="border: none;">
                @if(!empty($planificacion->firma_coordinador_b64))
                    <img src="{{ $planificacion->firma_coordinador_b64 }}" style="max-height: 50px; max-width: 150px;"><br>
                @else
                    <br><br><br>
                @endif
                ________________________________<br>
                <strong>Firma del Coordinador(a)</strong>
            </td>
        </tr>
    </table>

</body>

</html>