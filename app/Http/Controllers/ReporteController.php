<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CalendarioAcademico;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ReporteController extends Controller
{
    /**
     * Muestra las estadísticas de cumplimiento de entregas de planificaciones.
     */
    public function cumplimiento(Request $request)
    {
        // 1. Validar que el usuario esté autenticado y tenga el rol de Coordinador
        if (!Auth::check() || !in_array(Auth::user()->usu_cod_rol, [1, 5, 11])) {
            abort(403, 'Acceso denegado. Este módulo está restringido al rol de Coordinador.');
        }

        $dbSogc = config('database.connections.emulacion_sogac_2.database');

        // 2. Obtener todos los periodos / calendarios académicos para el filtro
        // Inactivar calendarios vencidos antes de listarlos
        CalendarioAcademico::inactivarVencidos();
        $calendarios = CalendarioAcademico::orderBy('id_calendario_academico', 'desc')->get();

        // 3. Determinar el calendario académico activo o seleccionado
        $calendarioSeleccionado = null;
        if ($request->filled('periodo')) {
            $calendarioSeleccionado = CalendarioAcademico::find($request->query('periodo'));
        } else {
            // Si no hay filtro, tomamos el calendario activo (estatus = 1)
            $calendarioSeleccionado = CalendarioAcademico::where('estatus', '1')->first();
            
            // Si no hay calendario activo, tomamos el más reciente como fallback
            if (!$calendarioSeleccionado) {
                $calendarioSeleccionado = CalendarioAcademico::orderBy('id_calendario_academico', 'desc')->first();
            }
        }

        // 4. Definir la fecha límite basándonos en los eventos especiales del calendario
        $fecha_inicio_lapso = null;
        $fecha_fin_lapso = null;

        if ($calendarioSeleccionado) {
            // Buscar Inicio del Lapso Académico (especial_evento = 2)
            $eventoInicio = DB::table('detalle_evento as de')
                ->join('evento as e', 'de.id_evento', '=', 'e.id_evento')
                ->where('de.id_calendario_academico', $calendarioSeleccionado->id_calendario_academico)
                ->where('e.especial_evento', '2')
                ->select('de.dia_inicio_detalle_evento')
                ->first();

            // Buscar Fin del Lapso Académico (especial_evento = 3)
            $eventoFin = DB::table('detalle_evento as de')
                ->join('evento as e', 'de.id_evento', '=', 'e.id_evento')
                ->where('de.id_calendario_academico', $calendarioSeleccionado->id_calendario_academico)
                ->where('e.especial_evento', '3')
                ->select('de.dia_inicio_detalle_evento')
                ->first();

            if ($eventoInicio) {
                $fecha_inicio_lapso = Carbon::parse($eventoInicio->dia_inicio_detalle_evento);
            }
            if ($eventoFin) {
                $fecha_fin_lapso = Carbon::parse($eventoFin->dia_inicio_detalle_evento);
            }
        }

        // Fallbacks si no se consiguen los eventos especiales
        if (!$fecha_inicio_lapso && $calendarioSeleccionado) {
            $fecha_inicio_lapso = Carbon::parse($calendarioSeleccionado->dia_inicio_calendario_academico);
        }
        if (!$fecha_fin_lapso && $calendarioSeleccionado) {
            $fecha_fin_lapso = Carbon::parse($calendarioSeleccionado->dia_fin_calendario_academico);
        }

        // La fecha límite para la entrega de planificaciones es el inicio del lapso académico
        $fecha_limite = $fecha_inicio_lapso ?: now();

        // 5. Consultar los docentes con sus asignaciones y planificaciones asociadas
        $query = DB::table("$dbSogc.seccion_unidad_docente as sud")
            ->leftJoin("$dbSogc.usuario as u", 'sud.sud_ced_docente', '=', 'u.usu_cedula')
            ->leftJoin("$dbSogc.persona as per", 'u.usu_cedula', '=', 'per.per_cedula')
            ->leftJoin("$dbSogc.unidad_curricular as uc", 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
            ->leftJoin("$dbSogc.seccion as s", 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->leftJoin('planificacion as p', 'p.id_profesor_asignado', '=', 'sud.sud_codigo')
            ->select(
                'sud.sud_codigo as asignacion_id',
                'per.per_nombres as docente_nombres',
                'per.per_apellidos as docente_apellidos',
                'per.per_cedula as docente_cedula',
                'uc.ucu_nombre as nombre_unidad_curricular',
                's.sec_nombre as nombre_seccion',
                'p.id_planificacion as planificacion_id',
                'p.created_at as fecha_entrega',
                'p.estatus as planificacion_estatus'
            )
            ->where('sud.sud_estatus', 'A') // Asignación activa
            ->distinct();

        // Aplicar filtro por docente (búsqueda por nombre, apellido o cédula)
        if ($request->filled('docente')) {
            $search = $request->query('docente');
            $query->where(function ($q) use ($search) {
                $q->where('per.per_nombres', 'like', "%{$search}%")
                  ->orWhere('per.per_apellidos', 'like', "%{$search}%")
                  ->orWhere('per.per_cedula', 'like', "%{$search}%");
            });
        }

        $allRecords = $query->get();

        // 6. Calcular dinámicamente los estados por cada registro en base al calendario seleccionado
        $processed = $allRecords->map(function ($row) use ($fecha_limite) {
            $fechaEntrega = $row->fecha_entrega ? Carbon::parse($row->fecha_entrega) : null;
            $now = Carbon::now();

            $estado = '';
            $diasDiferencia = 0;
            $diasDiferenciaTexto = '0';

            if ($fechaEntrega) {
                if ($fechaEntrega->lte($fecha_limite)) {
                    $estado = 'A tiempo';
                    // Calcular días de anticipación (opcional, o 0)
                    $diasDiferencia = (int) round($fechaEntrega->diffInDays($fecha_limite));
                    $diasDiferenciaTexto = $diasDiferencia > 0 ? "{$diasDiferencia} días antes" : "En el límite";
                } else {
                    $estado = 'Atrasado';
                    $diasDiferencia = (int) round($fecha_limite->diffInDays($fechaEntrega));
                    $diasDiferenciaTexto = "{$diasDiferencia} días de retraso";
                }
            } else {
                if ($now->lte($fecha_limite)) {
                    $estado = 'Pendiente';
                    $diasDiferencia = (int) round($now->diffInDays($fecha_limite));
                    $diasDiferenciaTexto = "{$diasDiferencia} días restantes";
                } else {
                    $estado = 'Vencido/No entregado';
                    $diasDiferencia = (int) round($fecha_limite->diffInDays($now));
                    $diasDiferenciaTexto = "{$diasDiferencia} días de retraso";
                }
            }

            $row->estado = $estado;
            $row->dias_diferencia_val = $diasDiferencia;
            $row->dias_diferencia_texto = $diasDiferenciaTexto;

            return $row;
        });

        // 7. Calcular las estadísticas para los KPIs superiores
        $totalATiempo = $processed->where('estado', 'A tiempo')->count();
        $totalAtrasados = $processed->where('estado', 'Atrasado')->count();
        $totalPendientes = $processed->where('estado', 'Pendiente')->count();
        $totalVencidos = $processed->where('estado', 'Vencido/No entregado')->count();

        // Combinamos pendientes y vencidos para el resumen general o mostramos ambos de forma premium
        $totalPendientesGeneral = $totalPendientes + $totalVencidos;

        // 8. Paginación manual de la colección procesada
        $page = $request->query('page', 1);
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator(
            $processed->forPage($page, $perPage),
            $processed->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );

        return view('livewire.pages.planificacion.reporte', [
            'docentes' => $paginatedItems,
            'calendarios' => $calendarios,
            'calendarioSeleccionado' => $calendarioSeleccionado,
            'totalATiempo' => $totalATiempo,
            'totalAtrasados' => $totalAtrasados,
            'totalPendientes' => $totalPendientesGeneral,
            'totalPendientesSolo' => $totalPendientes,
            'totalVencidosSolo' => $totalVencidos,
            'filtroDocente' => $request->query('docente', ''),
            'filtroPeriodo' => $request->query('periodo', ''),
            'fechaLimite' => $fecha_limite
        ]);
    }
}
