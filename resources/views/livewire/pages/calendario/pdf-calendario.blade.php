<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Calendario Académico</title>
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
            font-size: 10pt;
            line-height: 1.5;
            color: #000;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .info-table td {
            padding: 10px;
            border: 1px solid #000;
        }
        .label {
            font-weight: bold;
            background-color: #f2f2f2;
            width: 30%;
        }
        .calendar-container {
            width: 100%;
            text-align: center;
        }
        .month-box {
            display: inline-block;
            width: 22.5%;
            margin: 0.5%;
            vertical-align: top;
            border: 0.5pt solid #ccc;
            padding: 3px;
        }
        .month-name {
            text-align: center;
            font-weight: bold;
            background-color: #eee;
            margin-bottom: 3px;
            font-size: 8pt;
        }
        .month-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.5pt;
        }
        .month-table th, .month-table td {
            text-align: center;
            padding: 1px;
            border: 0.1pt solid #eee;
        }
        .month-table th {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .active-range {
            color: #000000;
            font-weight: bold;
            background-color: #ffffff;
        }
        .out-of-range {
            color: #bbbbbb;
            background-color: #ffffff;
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
        <div class="footer-text">{{ now()->format('d/m/Y h:i a') }} - Reporte de Calendario</div>
    </footer>

    <div class="title">CALENDARIO ACADÉMICO {{ $year }}</div>
    <div style="text-align: center; margin-bottom: 20px;">
        <strong>Lapso:</strong> {{ $calendario->nombre_lapso }} | 
        <strong>Vigencia:</strong> {{ \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->format('d/m/Y') }} 
        hasta {{ \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->format('d/m/Y') }}
    </div>

    @php
        $startDate = \Carbon\Carbon::parse($calendario->dia_inicio_calendario_academico)->startOfDay();
        $endDate = \Carbon\Carbon::parse($calendario->dia_fin_calendario_academico)->endOfDay();
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    @endphp

    <div class="calendar-container">
        @foreach(range(1, 12) as $m)
            @php
                $currentMonth = \Carbon\Carbon::create($year, $m, 1);
                $daysInMonth = $currentMonth->daysInMonth;
                $startDayOfWeek = $currentMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
                // Convert to Monday start if preferred, but Sunday is common. 
                // Let's use Sunday start for simplicity (0=Sun, 1=Mon, ..., 6=Sat)
            @endphp
            <div class="month-box">
                <div class="month-name">{{ $meses[$m-1] }}</div>
                <table class="month-table">
                    <thead>
                        <tr>
                            <th>D</th><th>L</th><th>M</th><th>M</th><th>j</th><th>V</th><th>S</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $dayCounter = 1; @endphp
                        @for($row = 0; $row < 6; $row++)
                            <tr>
                                @for($col = 0; $col < 7; $col++)
                                    @php
                                        $currentDayNum = ($row * 7 + $col) - $startDayOfWeek + 1;
                                        $cellDate = null;
                                        if($currentDayNum >= 1 && $currentDayNum <= $daysInMonth) {
                                            $cellDate = \Carbon\Carbon::create($year, $m, $currentDayNum)->startOfDay();
                                        }
                                        $isActive = false;
                                        if($cellDate && $cellDate->between($startDate, $endDate)) {
                                            $isActive = true;
                                        }
                                    @endphp
                                    <td class="{{ $isActive ? 'active-range' : ($cellDate ? 'out-of-range' : '') }}">
                                        {{ ($currentDayNum >= 1 && $currentDayNum <= $daysInMonth) ? $currentDayNum : '' }}
                                    </td>
                                @endfor
                            </tr>
                            @if($currentDayNum >= $daysInMonth) @break @endif
                        @endfor
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

</body>
</html>
