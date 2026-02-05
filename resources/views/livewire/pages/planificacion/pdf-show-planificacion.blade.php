<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Planificación Académica #{{ $planificacion->planificacion_id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }

        .info-grid td {
            padding: 4px;
            border: none;
        }

        .label {
            font-weight: bold;
            width: 150px;
        }

        .section-title {
            background: #f0f0f0;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 4px solid #333;
            margin: 15px 0 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eee;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8pt;
        }

        .corte-header {
            font-size: 12pt;
            background: #333;
            color: #fff;
            padding: 8px;
            margin-top: 20px;
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 8pt;
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>PLANIFICACIÓN ACADÉMICA</h2>
        <p><strong>{{ $planificacion->nombre_unidad_curricular }}</strong></p>
    </div>

    <div class="info-section">
        <table class="info-grid">
            <tr>
                <td class="label">Docente:</td>
                <td>{{ $planificacion->docente_nombre }} {{ $planificacion->docente_apellido }}</td>
                <td class="label">C.I.:</td>
                <td>{{ $planificacion->cedula }}</td>
            </tr>
            <tr>
                <td class="label">PNF:</td>
                <td>{{ $planificacion->nombre_pnf ?? 'N/A' }}</td>
                <td class="label">Sección:</td>
                <td>{{ $planificacion->nombre_seccion }}</td>
            </tr>
            <tr>
                <td class="label">Lapso:</td>
                <td>{{ $planificacion->nombre_lapso }}</td>
                <td class="label">Estatus:</td>
                <td>
                    @if($planificacion->estatus == 1) Aprobado
                    @elseif($planificacion->estatus == 2) Pendiente
                    @elseif($planificacion->estatus == 3) Rechazado
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @foreach($planificacion->cortes as $corte)
        <div class="corte-header">CORTE {{ $corte['corte'] }}</div>

        <div class="section-title">Contenidos e Indicadores</div>
        <table>
            <thead>
                <tr>
                    <th width="40%">Tema/Contenido</th>
                    <th>Indicadores de Logro</th>
                </tr>
            </thead>
            <tbody>
                @foreach($corte['contenidos'] as $cont)
                    <tr>
                        <td><strong>{{ $cont['titulo_contenido'] }}</strong><br><small>{{ $cont['descripcion_contenido'] }}</small>
                        </td>
                        <td>
                            @if(!empty($cont['indicadores_logros']))
                                <ul style="margin: 0; padding-left: 15px;">
                                    @foreach($cont['indicadores_logros'] as $ind)
                                        <li>{{ $ind['descripcion_indicador'] }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="width: 100%;">
            <div style="width: 48%; float: left;">
                <div class="section-title">Recursos</div>
                <ul>
                    @foreach($corte['recursos'] as $rec)
                        <li>{{ $rec['recurso'] }}</li>
                    @endforeach
                </ul>
            </div>
            <div style="width: 48%; float: right;">
                <div class="section-title">Estrategias</div>
                <ul>
                    @foreach($corte['estrategias'] as $est)
                        <li>{{ $est['estrategia'] }}</li>
                    @endforeach
                </ul>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="section-title">Plan de Evaluación</div>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Evaluación</th>
                    <th>Técnica</th>
                    <th>Participación</th>
                    <th>Pond.</th>
                </tr>
            </thead>
            <tbody>
                @foreach($corte['evaluaciones'] as $eval)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($eval['fecha_evaluacion'])->format('d/m/Y') }}</td>
                        <td>{{ $eval['evaluacion'] }}</td>
                        <td>{{ $eval['tecnica'] }}</td>
                        <td>
                            @if($eval['forma_participacion'] == 1) Individual
                            @elseif($eval['forma_participacion'] == 2) Pareja
                            @elseif($eval['forma_participacion'] == 3) Grupal
                            @endif
                        </td>
                        <td><strong>{{ $eval['ponderacion'] }}%</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    @if(!empty($planificacion->bibliografias))
        <div class="section-title">Bibliografía</div>
        <ul>
            @foreach($planificacion->bibliografias as $bib)
                <li>{{ $bib['bibliografia'] }}</li>
            @endforeach
        </ul>
    @endif

    <div class="footer">
        <p>Generado automáticamente por SAUPA el {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>

</html>
