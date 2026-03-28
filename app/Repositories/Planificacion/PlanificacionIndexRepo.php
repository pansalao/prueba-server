<?php

namespace App\Repositories\Planificacion;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\{Facades\DB, Facades\Auth, Facades\Log};

class PlanificacionIndexRepo
{
    /**
     * Obtiene una lista paginada de planificaciones con filtros.
     */
    public function listar(array $filters = [], int $perPage = 10, bool $onlyCurrentUserAndRole = false)
    {
        $dbSogc = config('database.connections.emulacion_sogac_2.database');

        $query = DB::table('planificacion as p')
            ->join("$dbSogc.seccion_unidad_docente as sud", 'p.id_profesor_asignado', '=', 'sud.sud_codigo')
            ->join("$dbSogc.usuario as u", 'sud.sud_ced_docente', '=', 'u.usu_cedula')
            ->join("$dbSogc.persona as per", 'u.usu_cedula', '=', 'per.per_cedula')
            ->join("$dbSogc.unidad_curricular as uc", 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
            ->join("$dbSogc.seccion as s", 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->select(
                'p.id_planificacion as planificacion_id',
                'per.per_nombres as docente_nombre',
                'per.per_apellidos as docente_apellido',
                'p.estatus',
                'uc.ucu_nombre as nombre_unidad_curricular',
                's.sec_nombre as nombre_seccion'
            );

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
            return $query->paginate($perPage);
        }

        return $query->get();
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
            \App\Models\UnidadCorte::where('id_planificacion', $planificacionId)
                ->update(['estatus' => 1]);

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
