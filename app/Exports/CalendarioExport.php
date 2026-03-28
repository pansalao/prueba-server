<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CalendarioExport implements FromView, ShouldAutoSize, WithStyles, WithDrawings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.calendario', $this->data);
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
}
