<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Planificaciones</title>
    <style>
        @page {
            margin: 160px 40px 80px 40px;
        }

        header {
            position: fixed;
            top: -140px;
            left: 0px;
            right: 0px;
            height: 120px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 80px;
            text-align: center;
            font-size: 8pt;
            color: #333;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .footer-text {
            margin: 2px 0;
            line-height: 1.2;
        }

        body { font-family: sans-serif; font-size: 10pt; }

        .pagenum:before {
            content: counter(page);
        }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status { font-weight: bold; }
        .status-1 { color: green; }
        .status-2 { color: orange; }
        .status-3 { color: red; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('img/reportes.jpg') }}" style="width: 100%; height: auto; max-height: 120px;">
    </header>

    <footer>
        <div class="footer-text">U.P.T.P. - Avenida Circunvalación Sur, sector Bellas Artes, diagonal a la Cruz Roja. Acarigua Edo. Portuguesa, República Bolivariana de Venezuela. R.I.F.: G-20010200-4.</div>
        <div class="footer-text">Zona postal 3301, Apartado N° 108. Teléfonos: (0255) 623.7538 - 623.7519 - 623.6085 | http://uptp.sytes.net</div>
        <div class="footer-text">Página <span class="pagenum"></span> - {{ now()->format('d/m/Y h:i a') }} - INGJ.T</div>
    </footer>

    <div style="text-align: center; margin-bottom: 20px;">
        <h1 style="margin: 0; text-transform: uppercase; font-size: 16pt;">Reporte General de Planificaciones</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th>Docente</th>
                <th>Unidad Curricular</th>
                <th>Sección</th>
                <th>Trayecto</th>
                <th>PNF</th>
                <th>Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($planificaciones as $p)
                <tr>
                    <td>{{ $p->docente_nombre }} {{ $p->docente_apellido }}</td>
                    <td>{{ $p->nombre_unidad_curricular }}</td>
                    <td>{{ $p->nombre_seccion }}</td>
                    <td>Trayecto {{ $p->trayecto_unidad_curricular }}</td>
                    <td>{{ $p->nombre_pnf }}</td>
                    <td class="status status-{{ $p->estatus }}">
                        @if($p->estatus == 1) Aprobado 
                        @elseif($p->estatus == 2) Pendiente
                        @elseif($p->estatus == 3) Rechazado
                        @else Desconocido
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

