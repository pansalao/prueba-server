<?php

namespace App\Repositories\Planificacion;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\{Facades\DB, Facades\Auth, Facades\Log};
use Illuminate\Support\Facades\Mail;
use App\Mail\PlanificacionAceptada;
use App\Mail\PlanificacionRechazada;
use App\Models\Notification; // Not actually using DB notifications, maybe just send email. Wait, the user asked for a notification icon in the UI. I'll handle that via an event or database table if they have one. Or simply send the email.

class PlanificacionIndexRepo
{
    /**
     * Obtiene una lista paginada de planificaciones con filtros.
     */
    public function listar(array $filters = [], int $perPage = 10, bool $onlyCurrentUserAndRole = false)
    {
        $dbSogc = config('database.connections.emulacion_sogac_2.database');

        $query = DB::table('planificacion as p')
            ->leftJoin("$dbSogc.seccion_unidad_docente as sud", 'p.id_profesor_asignado', '=', 'sud.sud_codigo')
            ->leftJoin("$dbSogc.usuario as u", 'sud.sud_ced_docente', '=', 'u.usu_cedula')
            ->leftJoin("$dbSogc.persona as per", 'u.usu_cedula', '=', 'per.per_cedula')
            ->leftJoin("$dbSogc.unidad_curricular as uc", 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
            ->leftJoin("$dbSogc.seccion as s", 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->leftJoin("$dbSogc.malla as ma", 'uc.ucu_cod_malla', '=', 'ma.mal_codigo')
            ->leftJoin("$dbSogc.programa as pr", 'ma.mal_cod_programa', '=', 'pr.pro_codigo')
            ->leftJoin("$dbSogc.semestre as sem", 's.sec_cod_semestre', '=', 'sem.sem_codigo')
            ->leftJoin("$dbSogc.trayecto as tr", 'sem.sem_cod_trayecto', '=', 'tr.tra_codigo')
            ->select(
                'p.id_planificacion as planificacion_id',
                'per.per_nombres as docente_nombre',
                'per.per_apellidos as docente_apellido',
                'uc.ucu_nombre as nombre_unidad_curricular',
                's.sec_nombre as nombre_seccion',
                'p.estatus',
                'pr.pro_nombre as nombre_pnf',
                'tr.tra_nombre as trayecto_unidad_curricular'
            )
            ->distinct();

        if (isset($filters['search_term']) && !empty($filters['search_term'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('per.per_nombres', 'like', '%' . $filters['search_term'] . '%')
                    ->orWhere('per.per_apellidos', 'like', '%' . $filters['search_term'] . '%')
                    ->orWhere('uc.ucu_nombre', 'like', '%' . $filters['search_term'] . '%');
            });
        }

        if ($onlyCurrentUserAndRole && Auth::check()) {
            $userId = Auth::id();
            $query->where('u.usu_codigo', $userId);
        }

        $query->orderByDesc('p.id_planificacion');

        if ($perPage > 0) {
            $results = $query->paginate($perPage);
        } else {
            $results = $query->get();
        }

        // \Illuminate\Support\Facades\Log::info("Listar Planificaciones SQL: " . $query->toSql());
        // \Illuminate\Support\Facades\Log::info("Listar Planificaciones Count: " . $results->count());

        return $results;
    }

    /**
     * Aprueba una planificación y todos sus cortes asociados.
     */
    public function aprobarPlanificacion(int $planificacionId): bool
    {
        DB::beginTransaction();
        try {
            $planificacion = \App\Models\Planificacion::find($planificacionId);
            if ($planificacion) {
                $planificacion->update(['estatus' => 1]);
            }

            // Actualizar todas las unidades asociadas a la planificación
            $unidades = \App\Models\UnidadCorte::where('id_planificacion', $planificacionId)->get();
            foreach ($unidades as $unidad) {
                $unidad->update(['estatus' => 1]);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al aprobar planificación: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rechaza una planificación completa.
     */
    public function rechazarPlanificacionConCortes(int $planificacionId, array $cortesRechazados): bool
    {
        DB::beginTransaction();
        try {
            // Actualizar estatus de la planificación a 'Rechazada' (3)
            $planificacion = \App\Models\Planificacion::find($planificacionId);
            if ($planificacion) {
                $planificacion->update(['estatus' => 3]);
            }

            foreach ($cortesRechazados as $rechazo) {
                $corteId = $rechazo['detalle_id'];
                $motivo = $rechazo['motivo'];

                // Marcar la unidad_corte como rechazada (3) y guardar el motivo
                $corte = \App\Models\UnidadCorte::find($corteId);
                if ($corte) {
                    $corte->update([
                        'estatus' => 3,
                        'descripcion_motivo_rechazo_unidad_corte' => $motivo,
                    ]);
                }
            }

            // Enviar correo de rechazo
            try {
                $dbSogc = config('database.connections.emulacion_sogac_2.database');
                $profesor = DB::table("$dbSogc.usuario as u")
                    ->join("$dbSogc.seccion_unidad_docente as sud", 'u.usu_cedula', '=', 'sud.sud_ced_docente')
                    ->join("$dbSogc.persona as p", 'u.usu_cedula', '=', 'p.per_cedula')
                    ->where('sud.sud_codigo', $planificacion->id_profesor_asignado)
                    ->select('u.usu_nombre', 'p.per_email', 'p.per_nombres', 'p.per_apellidos')
                    ->first();

                $planificacionDetalles = DB::table('planificacion as p')
                    ->join("$dbSogc.seccion_unidad_docente as sud", 'p.id_profesor_asignado', '=', 'sud.sud_codigo')
                    ->join("$dbSogc.unidad_curricular as uc", 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
                    ->join("$dbSogc.seccion as s", 'sud.sud_cod_seccion', '=', 's.sec_codigo')
                    ->where('p.id_planificacion', $planificacionId)
                    ->select('uc.ucu_nombre as nombre_unidad_curricular', 's.sec_nombre as nombre_seccion')
                    ->first();

                $motivosFormat = [];
                foreach ($cortesRechazados as $r) {
                    $corteObj = \App\Models\UnidadCorte::find($r['detalle_id']);
                    $motivosFormat[] = [
                        'numero' => $corteObj->numero_unidad_corte ?? '?',
                        'motivo' => $r['motivo']
                    ];
                }

                if ($profesor && !empty($profesor->per_email)) {
                    Mail::to($profesor->per_email)->send(new PlanificacionRechazada($profesor, $planificacionDetalles, $motivosFormat));
                }
            } catch (\Exception $e) {
                Log::error("Error al enviar correo de rechazo: " . $e->getMessage());
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al rechazar planificación: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarMotivoRechazoPorCorte($detalleId)
    {
        try {
            // Limpiar el motivo de rechazo y volver a estado 'Pendiente' (2)
            $corte = \App\Models\UnidadCorte::find($detalleId);
            if ($corte) {
                $corte->update([
                    'descripcion_motivo_rechazo_unidad_corte' => null,
                    'estatus' => 2,
                ]);

                $planificacionId = $corte->id_planificacion;
                $hayRechazados = \App\Models\UnidadCorte::where('id_planificacion', $planificacionId)
                    ->where('estatus', 3)
                    ->exists();

                // Si no hay cortes rechazados, pasar la planificación a 'Pendiente' (2)
                if (!$hayRechazados) {
                    $planificacion = \App\Models\Planificacion::find($planificacionId);
                    if ($planificacion && $planificacion->estatus != 1) {
                        $planificacion->update(['estatus' => 2]);
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Error al eliminar motivo de rechazo: " . $e->getMessage());
            return false;
        }
    }

    public function aprobarCorte(int $corteId): bool
    {
        try {
            $corte = \App\Models\UnidadCorte::find($corteId);
            if ($corte) {
                $corte->update(['estatus' => 1]);

                // Verificar si todas las unidades de la misma planificación están aprobadas
                $planificacionId = $corte->id_planificacion;
                $todosAprobados = !\App\Models\UnidadCorte::where('id_planificacion', $planificacionId)
                    ->where('estatus', '!=', 1)
                    ->exists();

                if ($todosAprobados) {
                    $planificacion = \App\Models\Planificacion::find($planificacionId);
                    if ($planificacion) {
                        $planificacion->update(['estatus' => 1]);

                        // Enviar correo electrónico al profesor
                        try {
                            $dbSogc = config('database.connections.emulacion_sogac_2.database');
                            $profesor = DB::table("$dbSogc.usuario as u")
                                ->join("$dbSogc.seccion_unidad_docente as sud", 'u.usu_cedula', '=', 'sud.sud_ced_docente')
                                ->join("$dbSogc.persona as p", 'u.usu_cedula', '=', 'p.per_cedula')
                                ->where('sud.sud_codigo', $planificacion->id_profesor_asignado)
                                ->select('u.usu_nombre', 'p.per_email', 'p.per_nombres', 'p.per_apellidos')
                                ->first();

                            // Buscar detalles extra de la planificación (seccion y uc)
                            $planificacionDetalles = DB::table('planificacion as p')
                                ->join("$dbSogc.seccion_unidad_docente as sud", 'p.id_profesor_asignado', '=', 'sud.sud_codigo')
                                ->join("$dbSogc.unidad_curricular as uc", 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
                                ->join("$dbSogc.seccion as s", 'sud.sud_cod_seccion', '=', 's.sec_codigo')
                                ->where('p.id_planificacion', $planificacionId)
                                ->select('uc.ucu_nombre as nombre_unidad_curricular', 's.sec_nombre as nombre_seccion')
                                ->first();

                            if ($profesor && !empty($profesor->per_email)) {
                                Mail::to($profesor->per_email)->send(new PlanificacionAceptada($profesor, $planificacionDetalles));
                            }
                        } catch (\Exception $e) {
                            Log::error("Error al enviar correo de planificación aceptada: " . $e->getMessage());
                        }
                    }
                }
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Error al aprobar corte: " . $e->getMessage());
            return false;
        }
    }
}
