<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Planificacion\PlanificacionIndexRepo;
use App\Repositories\Planificacion\PlanificacionViewRepo;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportePlanificacionController extends Controller
{
    protected $planificacionIndexRepo;
    protected $planificacionViewRepo;

    public function __construct()
    {
        $this->planificacionIndexRepo = new PlanificacionIndexRepo();
        $this->planificacionViewRepo = new PlanificacionViewRepo();
    }

    /**
     * Genera el reporte general de planificaciones.
     */
    public function reporteGeneral(Request $request)
    {
        $filters = [
            'search_term' => $request->query('search', ''),
        ];

        // Obtener datos (perPage = 0 para traer todos)
        $planificaciones = $this->planificacionIndexRepo->listar($filters, 0);

        $pdf = Pdf::loadView('livewire.pages.planificacion.pdf-list-planificacion', [
            'planificaciones' => $planificaciones
        ]);

        return $pdf->stream('reporte_general_planificaciones.pdf');
    }

    /**
     * Genera el reporte detallado de una planificación.
     */
    public function reporteDetalle($id)
    {
        $details = $this->planificacionViewRepo->getDetallesPlanificacion($id);

        if (!$details) {
            abort(404, 'Planificación no encontrada');
        }

        // Convertir a objeto para mantener consistencia con la vista
        $planificacion = (object) $details;

        $pdf = Pdf::loadView('livewire.pages.planificacion.pdf-show-planificacion', [
            'planificacion' => $planificacion
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('planificacion_' . $id . '.pdf');
    }
}
