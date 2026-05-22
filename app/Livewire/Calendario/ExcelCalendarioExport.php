<?php

namespace App\Livewire\Calendario;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExcelCalendarioExport implements FromView, WithStyles, WithDrawings, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Método estático para gestionar la descarga directamente.
     */
    public static function descargar($id = null)
    {
        $repo = new \App\Repositories\Calendario\CalendarioExcelRepo();

        $calendario = $id
            ? $repo->obtenerPorId($id)
            : $repo->obtenerUltimoActivo();

        if (!$calendario) {
            return redirect()->back()->with('error', 'El calendario solicitado no existe o no hay calendarios activos.');
        }

        // Solo permitir descargar si el estatus es 1 (Activo)
        if ($calendario->estatus != 1) {
            return redirect()->back()->with('error', 'Solo se pueden imprimir calendarios aprobados (Activos).');
        }

        $data = $repo->prepararDataExportacion($calendario);

        return \Maatwebsite\Excel\Facades\Excel::download(
            new self($data),
            'calendario_academico_' . $data['startYear'] . '-' . $data['endYear'] . '.xlsx'
        );
    }

    public function view(): View
    {
        return view('livewire.pages.calendario.excel-calendario', $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Estilos para el título principal en la fila 10
            10 => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Header');
        $drawing->setDescription('Header de Reporte');
        $drawing->setPath(public_path('img/reportes.jpg'));
        $drawing->setHeight(110); // Ajustar altura
        $drawing->setCoordinates('A1'); // Colocar en A1

        return $drawing;
    }

    /**
     * Registra eventos para manipular la hoja después de generada.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $data = $this->data;
                $calendario = $data['calendario'];
                $years = $data['years'] ?? [$data['year']];
                $eventDaysByYear = $data['eventDaysByYear'] ?? [$data['year'] => ($data['eventDays'] ?? [])];
                $listaMesesCompleta = $data['listaMesesCompleta'] ?? [];
                $eventos = $data['eventos'] ?? collect();

                // ─── Calcular el ancho dinámico de la columna "Evento" ───────────────
                // Factor de escala: aprox. 1.2 caracteres → 1 unidad Excel
                $maxLen = 10; // mínimo base
                foreach ($eventos as $ev) {
                    $len = mb_strlen($ev->descripcion_evento ?? '');
                    if ($len > $maxLen) {
                        $maxLen = $len;
                    }
                }

                // La columna Evento ocupa 3 sub-columnas (cols 26, 27, 28)
                // Distribuimos el ancho total entre las 3
                $eventoTotalWidth = $maxLen * 1.03; // escala heurística ajustada
                $eventoColWidth = ceil($eventoTotalWidth / 3);

                // ─── Fijar anchos de todas las columnas ──────────────────────────────
                // Días del calendario: 3 meses × 8 cols (7 días + 1 spacer)
                for ($col = 1; $col <= 24; $col++) {
                    $letter = Coordinate::stringFromColumnIndex($col);
                    // Espaciadores (cols 8, 16) y Separador principal (col 24)
                    if ($col % 8 === 0) {
                        $width = ($col === 24) ? 10 : 1.5;
                        $sheet->getColumnDimension($letter)->setWidth($width);
                    } else {
                        $sheet->getColumnDimension($letter)->setWidth(5.5);
                    }
                }

                // Col 25: tira de color del evento
                $sheet->getColumnDimension(Coordinate::stringFromColumnIndex(25))->setWidth(2);

                // Cols 26-28: Evento (ancho dinámico)
                for ($col = 26; $col <= 28; $col++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth($eventoColWidth);
                }

                // Cols 29-31: Fecha
                for ($col = 29; $col <= 31; $col++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth(9);
                }

                // Cols 32-33: Condición
                for ($col = 32; $col <= 33; $col++) {
                    $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($col))->setWidth(7);
                }

                // ─── Aplicar Alineación a Columnas de Eventos ───────────────
                $lastRow = $sheet->getHighestRow();
                // Evento (Z-AB): Izquierda
                $sheet->getStyle('Z11:AB' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('Z11:AB' . $lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $sheet->getStyle('Z11:AB' . $lastRow)->getAlignment()->setIndent(1); // Pequeño margen
    
                // Fecha y Condición (AC-AG): Centro
                $sheet->getStyle('AC11:AG' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('AC11:AG' . $lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // ─── Añadir comentarios en las celdas con eventos ────────────────────
                $mesesChunks = array_chunk($listaMesesCompleta, 3);
                $currentBaseRow = 15;

                foreach ($mesesChunks as $chunkIndex => $chunk) {
                    $baseRow = $currentBaseRow + ($chunkIndex * 9);

                    foreach ($chunk as $mPos => $item) {
                        $y = $item['year'];
                        $m = $item['month'];
                        $eventDays = $eventDaysByYear[$y] ?? [];

                        $currentMonth = \Carbon\Carbon::create($y, $m, 1);
                        $daysInMonth = $currentMonth->daysInMonth;
                        $startDayOfWeek = $currentMonth->dayOfWeek;

                        // BaseCol: A=1, I=9, Q=17
                        $baseCol = 1 + ($mPos * 8);

                        for ($numFila = 0; $numFila < 6; $numFila++) {
                            for ($col = 0; $col < 7; $col++) {
                                $diaNum = ($numFila * 7 + $col) - $startDayOfWeek + 1;

                                if ($diaNum >= 1 && $diaNum <= $daysInMonth) {
                                    $cellDate = \Carbon\Carbon::create($y, $m, $diaNum)->startOfDay();
                                    $dateStr = $cellDate->format('Y-m-d');

                                    if (isset($eventDays[$dateStr])) {
                                        $eventNames = $eventDays[$dateStr]['nombres'];
                                        $commentText = "Eventos:\n\n";
                                        foreach ($eventNames as $i => $name) {
                                            $commentText .= ($i + 1) . ".- " . $name . "\n";
                                        }

                                        $excelRow = $baseRow + $numFila;
                                        $excelCol = $baseCol + $col;
                                        $cellCoord = Coordinate::stringFromColumnIndex($excelCol) . $excelRow;

                                        $run = $sheet->getComment($cellCoord)
                                            ->getText()
                                            ->createTextRun($commentText);
                                        $run->getFont()->setSize(12); // Texto más grande
    
                                        // ─── Calcular ancho dinámico para la nota ───
                                        $maxNoteLen = 10;
                                        foreach ($eventNames as $name) {
                                            $len = mb_strlen($name);
                                            if ($len > $maxNoteLen) {
                                                $maxNoteLen = $len;
                                            }
                                        }
                                        // Escala: aprox 6.5pt de ancho por carácter en fuente 12pt
                                        $noteWidth = $maxNoteLen * 6.5;
                                        if ($noteWidth < 150)
                                            $noteWidth = 150; // Ancho mínimo
    
                                        $height = 45 + (count($eventNames) * 18);
                                        $sheet->getComment($cellCoord)->setWidth($noteWidth . 'pt');
                                        $sheet->getComment($cellCoord)->setHeight($height . 'pt');
                                    }
                                }
                            }
                        }
                    }
                }
            },
        ];
    }
}

