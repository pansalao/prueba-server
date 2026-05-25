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

    /**
     * Genera el Acuerdo de Aprendizaje en PDF.
     */
    public function acuerdoAprendizaje($id)
    {
        $details = $this->planificacionViewRepo->getDetallesPlanificacion($id);

        if (!$details) {
            abort(404, 'Planificación no encontrada');
        }

        $planificacion = (object) $details;

        // Obtener el tipo de planificación (Regular, Repitencia)
        $tipoPlanificacionJson = \Illuminate\Support\Facades\DB::table('planificacion')
            ->where('id_planificacion', $id)
            ->value('tipo_planificacion');
            
        $tipos = json_decode($tipoPlanificacionJson, true) ?? [];
        $esRegular = in_array('Regular', $tipos);
        $esRepitencia = in_array('Repitencia', $tipos);

        // Obtener sud_codigo (id_profesor_asignado)
        $sud_codigo = \Illuminate\Support\Facades\DB::table('planificacion')
            ->where('id_planificacion', $id)
            ->value('id_profesor_asignado');

        $dbSogc = config('database.connections.emulacion_sogac_2.database');
        
        $queryEstudiantes = \Illuminate\Support\Facades\DB::table("$dbSogc.inscripcion as ins")
            ->join("$dbSogc.persona as per", 'ins.ins_cedula', '=', 'per.per_cedula')
            ->where('ins.ins_cod_seccion_unidad_docente', $sud_codigo)
            ->where('ins.ins_estatus', 'A')
            ->select('per.per_cedula', 'per.per_nombres', 'per.per_apellidos', 'per.per_email', 'per.per_telefono_movil as per_telefono', 'ins.ins_tipo', 'ins.ins_cod_condicion_inscrito');

        // Filtro rudimentario de tipos
        if ($esRegular && !$esRepitencia) {
            $queryEstudiantes->where(function($q) {
                $q->where('ins.ins_tipo', 'N')->orWhere('ins.ins_cod_condicion_inscrito', '!=', 2);
            });
        } elseif (!$esRegular && $esRepitencia) {
            $queryEstudiantes->where(function($q) {
                $q->where('ins.ins_tipo', 'R')->orWhere('ins.ins_cod_condicion_inscrito', 2);
            });
        }
        // Si tiene ambos, trae todos

        $estudiantes = $queryEstudiantes->orderBy('per.per_apellidos')->orderBy('per.per_nombres')->get();

        // Obtener sedes
        $sedes = \Illuminate\Support\Facades\DB::table("$dbSogc.sede")
            ->whereIn('sed_estatus', ['A', '1'])
            ->orderBy('sed_nombre')
            ->get();

        $pdf = Pdf::loadView('livewire.pages.planificacion.pdf-acuerdo-aprendizaje', [
            'planificacion' => $planificacion,
            'estudiantes' => $estudiantes,
            'sedes' => $sedes
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('acuerdo_aprendizaje_' . $id . '.pdf');
    }
}
