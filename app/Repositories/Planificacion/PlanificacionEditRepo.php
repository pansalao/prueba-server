<?php

namespace App\Repositories\Planificacion;

use Illuminate\Support\{Facades\DB, Facades\Log};

class PlanificacionEditRepo
{
    /**
     * Guarda (actualiza) una planificación existente y sus detalles.
     */
    public function updatePlanificacion(int $planificacionId, array $data): bool
    {
        DB::beginTransaction();
        try {
            // --- SINCRONIZACIÓN DE UNIDADES (tabla 'unidad_corte') ---
            $oldActiveCortes = DB::table('unidad_corte')
                ->where('id_planificacion', $planificacionId)
                ->whereIn('estatus', ['1', '2', '3']) // Activos, Pendientes o Rechazados
                ->get()
                ->keyBy('id_unidad_corte');

            $newCortes = collect($data['cortes']);
            $processedOldCorteIds = [];

            foreach ($newCortes as $corteData) {
                $corteNumero = $corteData['corte']; // 'corte' es el numero (1, 2, 3)
                $foundOldCorte = null;

                // Buscar orden existente por número
                foreach ($oldActiveCortes as $oldCorte) {
                    if ($oldCorte->numero_unidad_corte == $corteNumero && $oldCorte->estatus != '3') {
                        $foundOldCorte = $oldCorte;
                        break;
                    }
                }

                // Si no encontramos uno activo (ej. estaba rechazado, o no existía), buscamos por ID si viniere, pero aquí nos basamos en numero
                if (!$foundOldCorte) {
                    // Buscar unidad rechazada para reactivar/actualizar
                    $foundOldCorte = DB::table('unidad_corte')
                        ->where('id_planificacion', $planificacionId)
                        ->where('numero_unidad_corte', $corteNumero)
                        ->first();
                }

                // NUEVO: Si el corte está Aprobado (1) o Pendiente (2), no lo tocamos.
                // Solo guardamos su ID para no borrarlo al final.
                if ($foundOldCorte && in_array($foundOldCorte->estatus, ['1', '2'])) {
                    $processedOldCorteIds[] = $foundOldCorte->id_unidad_corte;
                    continue;
                }

                $corteId = null;
                if ($foundOldCorte) {
                    $corteId = $foundOldCorte->id_unidad_corte;
                    $processedOldCorteIds[] = $corteId;

                    $corteModel = \App\Models\UnidadCorte::find($corteId);
                    if ($corteModel) {
                        $corteModel->update([
                            'indicador_logro_unidad_corte' => $corteData['indicadores_logro'] ?? null,
                            'estatus' => '2', // Reactivar a pendiente/guardado
                            'descripcion_motivo_rechazo_unidad_corte' => null // Limpiar motivos de rechazo previos
                        ]);
                    }
                } else {
                    $corteModel = \App\Models\UnidadCorte::create([
                        'id_planificacion' => $planificacionId,
                        'numero_unidad_corte' => $corteNumero,
                        'indicador_logro_unidad_corte' => $corteData['indicadores_logro'] ?? null,
                        'estatus' => '2',
                        'fecha_creacion' => now(),
                    ]);
                    $corteId = $corteModel->getKey();
                }

                $evaluacionesData = array_map(function ($eval) {
                    return [
                        'id_tipo_evaluacion' => $eval['evaluacion_id'],
                        'id_tecnica_evaluacion' => $eval['tecnica_id'],
                        'ponderacion_detalle_evaluacion' => $eval['ponderacion'],
                        'fecha_evaluacion_detalle_evaluacion' => $eval['fecha_evaluacion'],
                        'forma_participacion_detalle_evaluacion' => $eval['forma_participacion'],
                        'integrantes_detalle_evaluacion' => ($eval['forma_participacion'] == '2') ? ($eval['integrantes'] ?? null) : 1,
                    ];
                }, $corteData['evaluaciones']);

                $this->syncCorteDetails(
                    'detalle_evaluacion',
                    'id_tipo_evaluacion',
                    $corteId,
                    $evaluacionesData,
                    'id_tipo_evaluacion',
                    ['id_tecnica_evaluacion', 'ponderacion_detalle_evaluacion', 'fecha_evaluacion_detalle_evaluacion', 'forma_participacion_detalle_evaluacion', 'integrantes_detalle_evaluacion']
                );

                // --- Sincronizar Estrategias y Contenidos ---
                // Esta parte necesita ser adaptada al nuevo esquema donde estrategias son tablas y contenidos tambien
                $this->syncEstrategiasCorte($corteId, $corteData['estrategias']);
                $this->syncContenidosCorte($corteId, $corteData['contenidos']);

                // --- Sincronizar Bibliografías por Corte ---
                $this->syncBibliografiasCorte($corteId, $corteData['bibliografias'] ?? []);
            }

            // Unidades antiguas que no están en la nueva data (marcar eliminados?)
            // En este caso, si el usuario quita una unidad de la UI (aunque no se permite borrar cortes usualmente, solo vaciar), lo marcamos inactivo
            foreach ($oldActiveCortes as $oldCorteId => $oldCorteData) {
                if (!in_array($oldCorteId, $processedOldCorteIds)) {
                    $corteToDelete = \App\Models\UnidadCorte::find($oldCorteId);
                    if ($corteToDelete) {
                        $corteToDelete->update(['estatus' => '2']);
                    }
                }
            }

            // Actualizar estatus general de la planificación a 'En Revisión' (2) si estaba Aprobada(1) o Rechazada(3)
            // O mantener en Borrador si estaba en borrador? 
            // Usualmente al editar se vuelve a someter.
            $planificacionToUpdate = \App\Models\Planificacion::find($planificacionId);
            if ($planificacionToUpdate && in_array($planificacionToUpdate->estatus, ['1', '3'])) {
                $planificacionToUpdate->update(['estatus' => '2']);
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al actualizar planificación: " . $e->getMessage() . " trace: " . $e->getTraceAsString());
            return false;
        }
    }

    private function syncCorteDetails(string $tableName, string $foreignIdColumn, int $corteId, array $newData, string $newIdKey, array $additionalColumns = [])
    {
        // Obtener IDs actuales activos
        $currentIds = DB::table($tableName)
            ->where('id_unidad_corte', $corteId)
            ->where('estatus', '1')
            ->pluck($foreignIdColumn)
            ->toArray();

        $newIds = collect($newData)->pluck($newIdKey)->filter()->toArray();

        // Desactivar los que ya no están
        $toDeactivate = array_diff($currentIds, $newIds);
        if (!empty($toDeactivate)) {
            \App\Models\DetalleEvaluacion::where('id_unidad_corte', $corteId)
                ->whereIn($foreignIdColumn, $toDeactivate)
                ->update(['estatus' => '2']);
        }

        foreach ($newData as $item) {
            $itemId = $item[$newIdKey] ?? null;
            if (empty($itemId))
                continue;

            $insertData = [
                'id_unidad_corte' => $corteId,
                $foreignIdColumn => $itemId,
                'estatus' => '1',
            ];

            foreach ($additionalColumns as $col) {
                if (isset($item[$col])) {
                    $insertData[$col] = $item[$col];
                }
            }

            $itemToUpdate = \App\Models\DetalleEvaluacion::where('id_unidad_corte', $corteId)
                ->where($foreignIdColumn, $itemId)
                ->first();

            if ($itemToUpdate) {
                $itemToUpdate->update($insertData);
            } else {
                $insertData['fecha_creacion'] = now();
                \App\Models\DetalleEvaluacion::create($insertData);
            }
        }
    }

    private function syncEstrategiasCorte(int $corteId, array $estrategiasData)
    {
        // Desactivar actuales
        \App\Models\DetalleEstrategia::where('id_unidad_corte', $corteId)
            ->update(['estatus' => '2']);

        foreach ($estrategiasData as $est) {
            $temaId = $est['tema_id'] ?? null;
            if (!$temaId)
                continue;

            \App\Models\DetalleEstrategia::create([
                'id_unidad_corte' => $corteId,
                'id_tema_unidad' => $temaId,
                'actividad' => $est['actividad'] ?? '',
                'estatus' => '1',
                'fecha_creacion' => now(),
            ]);
        }
    }

    private function syncContenidosCorte(int $corteId, array $contenidosData)
    {
        // Desactivar actuales
        \App\Models\DetalleContenido::where('id_unidad_corte', $corteId)
            ->update(['estatus' => '2']);

        foreach ($contenidosData as $cont) {
            $contenidoId = $cont['contenido_id'] ?? null;
            if (!$contenidoId)
                continue;

            \App\Models\DetalleContenido::create([
                'id_unidad_corte' => $corteId,
                'id_contenido' => $contenidoId,
                'estatus' => '1',
                'fecha_creacion' => now(),
            ]);
        }
    }

    private function syncBibliografiasCorte(int $corteId, array $bibliografiasData)
    {
        // Desactivar actuales
        \App\Models\DetalleBibliografia::where('id_unidad_corte', $corteId)
            ->update(['estatus' => '2']);

        foreach ($bibliografiasData as $bib) {
            $bibId = $bib['bibliografia_id'] ?? null;
            if (!$bibId)
                continue;

            \App\Models\DetalleBibliografia::create([
                'id_unidad_corte' => $corteId,
                'id_bibliografia' => $bibId,
                'estatus' => '1',
                'fecha_creacion' => now(),
            ]);
        }
    }
}
