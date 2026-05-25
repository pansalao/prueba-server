<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Acuerdo de Aprendizaje #{{ $planificacion->planificacion_id }}</title>
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
            margin-bottom: 10px;
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
            background-color: #f2f2f2;
            margin-bottom: 10px;
        }

        .text-content {
            font-size: 8pt;
            text-align: justify;
            margin-bottom: 10px;
        }

        .text-content u {
            font-weight: bold;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: middle;
        }

        .main-table th {
            text-align: center;
            font-weight: bold;
            font-size: 7.5pt;
        }

        .main-table td {
            font-size: 7.5pt;
        }

        .uppercase {
            text-transform: uppercase;
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
        <div class="footer-text">Página <span class="pagenum"></span> - {{ now()->format('d/m/Y h:i a') }}
        </div>
    </footer>

    <div class="planning-title uppercase">ACUERDO DE APRENDIZAJE-LAPSO {{ $planificacion->nombre_lapso ?? '2023-II' }}</div>

    <div class="text-content">
        <p style="text-align: center; font-weight: bold; margin-top:0;">
            CUMPLIMIENTO DE LOS PLANES ACADÉMICO, DE EVALUACIÓN Y VALORACIÓN DEL DESEMPEÑO ESTUDIANTIL<br>
            <span style="font-weight: normal;">(Aprobada en Consejo Directivo Ordinario No. 06 de fecha 11.10.2010 y ratificado en Consejo Académico Extraordinario N° 5 de fecha 07 de junio de 2023)</span>
        </p>
        <p>
            Este acuerdo debe consignarse ante la coordinación del PNFI, acompañado de la Planificación de Evaluación. Lineamientos de evaluación del desempeño estudiantil en los PNF, en el Marco de la Misión Sucre y la Misión Alma Mater. Resolución Nº 2593, de fecha 10 de enero de 2012. Art. 14: “El plan de evaluación, con el debido respaldo de las firmas del docente y de los estudiantes (acuerdo de aprendizaje), será entregado en la tercera semana de actividades académicas”.
        </p>
        <p style="margin-bottom: 5px;">
            <strong>Propósito de la Unidad Curricular:</strong> <u>{{ $planificacion->proposito_unidad ?? 'No especificado' }}</u>
        </p>
        <p>
            Una vez discutido y aprobado por las y los estudiantes el plan académico y de evaluación de la Unidad Curricular: <u>{{ $planificacion->nombre_unidad_curricular }}</u> yo, <u>{{ $planificacion->docente_nombre }} {{ $planificacion->docente_apellido }}</u> (C.I. <u>{{ $planificacion->cedula }}</u>), me comprometo a cumplir lo relativo al desarrollo de las actividades programadas, así como reportar los resultados de las evaluaciones realizadas a las y los estudiantes en un lapso de ocho (8) días luego de ejecutada la evaluación. Así mismo, el estudiante tendrá derecho de recuperar una sola vez, una actividad de evaluación realizada donde no obtuvo la calificación mínima de aprobación (doce (12) puntos) y además el 60% de la población estudiantil de la sección de la unidad curricular no haya aprobado la evaluación. En tal sentido, firmo ________________ en fecha: <u>{{ now()->format('d/m/Y') }}</u>; y de igual manera, lo hacen los y las estudiantes de la Sección: <u>{{ $planificacion->nombre_seccion }}</u> Trayecto: <u>{{ $planificacion->trayecto_unidad_curricular ?? '____' }}</u>, Semestres: <u>{{ $planificacion->duracion_unidad_curricular ?? '_____' }}</u>, Del PNF: <u>{{ $planificacion->nombre_pnf ?? 'INFORMÁTICA' }}</u>, Turno: <u>{{ $planificacion->turno ?? '__________' }}</u>, Sede: <u>@if(count($sedes) > 0) @foreach($sedes as $sede){{ $sede->sed_nombre }}@if(!$loop->last) / @endif @endforeach @else _______________________________ @endif</u>, en señal de adquirir su correspondiente compromiso las partes (docente y estudiante).
        </p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">CÉD. DE IDENTIDAD</th>
                <th width="20%">APELLIDOS</th>
                <th width="20%">NOMBRES</th>
                <th width="12%">FIRMA</th>
                <th width="12%">TELEFONO</th>
                <th width="18%">CORREO ELECTRÓNICO</th>
            </tr>
        </thead>
        <tbody>
            @if(count($estudiantes) > 0)
                @foreach($estudiantes as $index => $estudiante)
                    <tr>
                        @php
                            $isRep = (isset($estudiante->ins_tipo) && $estudiante->ins_tipo == 'R') || ($estudiante->ins_cod_condicion_inscrito == 2);
                        @endphp
                        <td style="text-align: center;">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            @if($isRep)*@endif
                        </td>
                        <td style="text-align: center;">{{ $estudiante->per_cedula }}</td>
                        <td class="uppercase">{{ $estudiante->per_apellidos }}</td>
                        <td class="uppercase">
                            {{ $estudiante->per_nombres }}
                            @if($isRep)
                                <span style="font-size: 6.5pt; font-weight: bold;">(R)</span>
                            @endif
                        </td>
                        <td></td>
                        <td style="text-align: center;">{{ $estudiante->per_telefono ?? '' }}</td>
                        <td style="text-align: center; font-size: 6.5pt;">{{ $estudiante->per_email ?? '' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center; padding: 15px;">
                        No se encontraron estudiantes para la condición especificada en esta sección.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div style="font-size: 7pt; margin-top: 5px;">
        <strong>Nota:</strong> Los estudiantes marcados con <strong>(R)</strong> indican que están cursando la unidad curricular en condición de <strong>Repitencia</strong>.
    </div>

</body>

</html>
