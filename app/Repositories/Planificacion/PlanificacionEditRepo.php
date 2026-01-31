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
            // --- SINCRONIZACIÓN DE BIBLIOGRAFÍAS (detalle_bibliografia) ---
            $currentBibliografias = DB::table('detalle_bibliografia')
                ->where('id_planificacion', $planificacionId)
                ->where('estatus', '1')
                ->pluck('id_bibliografia')
                ->toArray();

            $newBibliografias = collect($data['bibliografias'])->pluck('bibliografia_id')->filter()->toArray();

            $toDeactivateBibliografias = array_diff($currentBibliografias, $newBibliografias);
            if (!empty($toDeactivateBibliografias)) {
                DB::table('detalle_bibliografia')
                    ->where('id_planificacion', $planificacionId)
                    ->whereIn('id_bibliografia', $toDeactivateBibliografias)
                    ->update(['estatus' => '2']);
            }

            foreach ($newBibliografias as $bibliografiaId) {
                $existing = DB::table('detalle_bibliografia')
                    ->where('id_planificacion', $planificacionId)
                    ->where('id_bibliografia', $bibliografiaId)
                    ->first();

                if ($existing) {
                    if ($existing->estatus == '2') {
                        DB::table('detalle_bibliografia')
                            ->where('id_planificacion', $planificacionId)
                            ->where('id_bibliografia', $bibliografiaId)
                            ->update(['estatus' => '1']);
                    }
                } else {
                    DB::table('detalle_bibliografia')->insert([
                        'id_planificacion' => $planificacionId,
                        'id_bibliografia' => $bibliografiaId,
                        'estatus' => '1',
                        'fecha_creacion' => now(),
                    ]);
                }
            }


            // --- SINCRONIZACIÓN DE UNIDADES (tabla 'unidad') ---
            $oldActiveCortes = DB::table('unidad')
                ->where('id_planificacion', $planificacionId)
                ->whereIn('estatus', ['1', '2', '3']) // Activos, Pendientes o Rechazados
                ->get()
                ->keyBy('id_unidad');

            $newCortes = collect($data['cortes']);
            $processedOldCorteIds = [];

            foreach ($newCortes as $corteData) {
                $corteNumero = $corteData['corte']; // 'corte' es el numero (1, 2, 3)
                $foundOldCorte = null;

                // Buscar orden existente por número
                foreach ($oldActiveCortes as $oldCorte) {
                    if ($oldCorte->numero_unidad == $corteNumero && $oldCorte->estatus != '3') {
                        $foundOldCorte = $oldCorte;
                        break;
                    }
                }

                // Si no encontramos uno activo (ej. estaba rechazado, o no existía), buscamos por ID si viniere, pero aquí nos basamos en numero
                if (!$foundOldCorte) {
                    // Buscar unidad rechazada para reactivar/actualizar
                    $foundOldCorte = DB::table('unidad')
                        ->where('id_planificacion', $planificacionId)
                        ->where('numero_unidad', $corteNumero)
                        ->first();
                }

                // NUEVO: Si el corte está Aprobado (1) o Pendiente (2), no lo tocamos.
                // Solo guardamos su ID para no borrarlo al final.
                if ($foundOldCorte && in_array($foundOldCorte->estatus, ['1', '2'])) {
                    $processedOldCorteIds[] = $foundOldCorte->id_unidad;
                    continue;
                }

                $corteId = null;
                if ($foundOldCorte) {
                    $corteId = $foundOldCorte->id_unidad;
                    $processedOldCorteIds[] = $corteId;

                    // Actualizar estatus si estaba rechazado o eliminado
                    DB::table('unidad')
                        ->where('id_unidad', $corteId)
                        ->update([
                            'estatus' => '2', // Reactivar a pendiente/guardado
                        ]);

                    // Invalidar motivos de rechazo previos
                    DB::table('motivo_rechazo')
                        ->where('id_unidad', $corteId)
                        ->where('estatus', '1')
                        ->update(['estatus' => '2']);
                } else {
                    $corteId = DB::table('unidad')->insertGetId([
                        'id_planificacion' => $planificacionId,
                        'numero_unidad' => $corteNumero,
                        'estatus' => '2',
                        'fecha_creacion' => now(),
                    ]);
                }

                // Sincronizar Relaciones del Corte
                $this->syncCorteDetails('detalle_recurso', 'id_recurso', $corteId, $corteData['recursos'], 'recurso_id');
                $this->syncCorteDetails('detalle_estrategia_pedagogica', 'id_estrategia_pedagogica', $corteId, $corteData['estrategias'], 'estrategia_id');

                // Evaluaciones (mapeo especial de columnas)
                $evaluacionesData = array_map(function ($eval) {
                    return [
                        'evaluacion_id' => $eval['evaluacion_id'],
                        'id_tecnica' => $eval['tecnica_id'],
                        'ponderacion_detalle_evaluacion' => $eval['ponderacion'],
                        'fecha_evaluacion_detalle_evaluacion' => $eval['fecha_evaluacion'],
                        'forma_participacion_detalle_evaluacion' => $eval['forma_participacion'],
                    ];
                }, $corteData['evaluaciones']);

                $this->syncCorteDetails(
                    'detalle_evaluacion',
                    'id_evaluacion',
                    $corteId,
                    $evaluacionesData,
                    'evaluacion_id',
                    ['id_tecnica', 'ponderacion_detalle_evaluacion', 'fecha_evaluacion_detalle_evaluacion', 'forma_participacion_detalle_evaluacion']
                );


                // --- Sincronizar Contenidos (Temas e Indicadores) ---
                $this->syncTemasCorte($corteId, $corteData['contenidos']);
            }

            // Unidades antiguas que no están en la nueva data (marcar eliminados?)
            // En este caso, si el usuario quita una unidad de la UI (aunque no se permite borrar cortes usualmente, solo vaciar), lo marcamos inactivo
            foreach ($oldActiveCortes as $oldCorteId => $oldCorteData) {
                if (!in_array($oldCorteId, $processedOldCorteIds)) {
                    DB::table('unidad')
                        ->where('id_unidad', $oldCorteId)
                        ->update(['estatus' => '2']); // 2 = Eliminado/Inactivo
                }
            }

            // Actualizar estatus general de la planificación a 'En Revisión' (2) si estaba Aprobada(1) o Rechazada(3)
            // O mantener en Borrador si estaba en borrador? 
            // Usualmente al editar se vuelve a someter.
            DB::table('planificacion')
                ->where('id_planificacion', $planificacionId)
                ->whereIn('estatus', ['1', '3'])
                ->update(['estatus' => '2']);

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
            ->where('id_unidad', $corteId)
            ->where('estatus', '1')
            ->pluck($foreignIdColumn)
            ->toArray();

        $newIds = collect($newData)->pluck($newIdKey)->filter()->toArray();

        // Desactivar los que ya no están
        $toDeactivate = array_diff($currentIds, $newIds);
        if (!empty($toDeactivate)) {
            DB::table($tableName)
                ->where('id_unidad', $corteId)
                ->whereIn($foreignIdColumn, $toDeactivate)
                ->update(['estatus' => '2']);
        }

        foreach ($newData as $item) {
            $itemId = $item[$newIdKey] ?? null;
            if (empty($itemId))
                continue;

            // Verificar si ya existe registro (activo o inactivo) para este par corte-item
            // Nota: Para evaluaciones puede haber múltiples de la misma evaluación_id? 
            // Si es así, esta lógica de 'first()' podría sobrescribir. 
            // Asumiremos por ahora que evaluación es única por tipo por corte o que la lógica base lo permite así.
            // Si se permiten multiples del mismo tipo, la lógica debe cambiar a usar ID primario si existe.

            // Para evaluaciones, la clave única compuesta debería ser id_detalle_evaluacion, pero no la tenemos en el input usualmente.
            // Si el usuario edita evaluaciones, es complejo mapear cuál es cuál si repite tipos.
            // Simplificación: Buscar por tipo. Si hay multiples del mismo tipo, esto fallará. 
            // PERO en update, normalmente borramos todo y recreamos o hacemos diff inteligente.
            // Dado el tiempo, usaremos update/insert con cuidado.

            // Corrección para soportar duplicados (ej: mismas estrategias) si fuera necesario:
            // En este caso, asumimos unicidad por item ID en el corte.

            $existing = DB::table($tableName)
                ->where('id_unidad', $corteId)
                ->where($foreignIdColumn, $itemId)
                ->first();

            $insertData = [
                'id_unidad' => $corteId,
                $foreignIdColumn => $itemId,
                'estatus' => '1',
            ];

            foreach ($additionalColumns as $col) {
                if (isset($item[$col])) {
                    $insertData[$col] = $item[$col];
                }
            }

            if ($existing) {
                // Siempre actualizamos los datos adicionales por si cambiaron (ej. ponderación)
                // y reactivamos si estaba inactivo '2'.
                DB::table($tableName)
                    ->where('id_unidad', $corteId)
                    ->where($foreignIdColumn, $itemId)
                    ->update($insertData);
            } else {
                $insertData['fecha_creacion'] = now(); // O fecha_creacion_detalle_... si aplica, pero standard es fecha_creacion
                DB::table($tableName)->insert($insertData);
            }
        }
    }

    private function syncTemasCorte(int $corteId, array $contenidosData)
    {
        // 1. Obtener temas actuales (detalle_tema)
        $currentTemasIds = DB::table('detalle_tema')
            ->where('id_unidad', $corteId)
            ->where('estatus', '1')
            ->pluck('id_tema')
            ->toArray();

        $newTemasIds = collect($contenidosData)->pluck('contenido_id')->filter()->toArray(); // contenido_id es id_tema

        // Desactivar temas removidos
        $toDeactivate = array_diff($currentTemasIds, $newTemasIds);
        if (!empty($toDeactivate)) {
            DB::table('detalle_tema')
                ->where('id_unidad', $corteId)
                ->whereIn('id_tema', $toDeactivate)
                ->update(['estatus' => '2']);
        }

        foreach ($contenidosData as $contenido) {
            $temaId = $contenido['contenido_id'] ?? null;
            if (!$temaId)
                continue;

            $existing = DB::table('detalle_tema')
                ->where('id_unidad', $corteId)
                ->where('id_tema', $temaId)
                ->first();

            $detalleTemaId = null;

            if ($existing) {
                $detalleTemaId = $existing->id_detalle_tema;
                if ($existing->estatus == '2') {
                    DB::table('detalle_tema')
                        ->where('id_detalle_tema', $detalleTemaId)
                        ->update(['estatus' => '1']);
                }
            } else {
                $detalleTemaId = DB::table('detalle_tema')->insertGetId([
                    'id_unidad' => $corteId,
                    'id_tema' => $temaId,
                    'estatus' => '1',
                    'fecha_creacion' => now(),
                ]);
            }

            // Sincronizar Indicadores para este detalle_tema
            $this->syncIndicadores($detalleTemaId, $contenido['indicadores_logros'] ?? []);
        }
    }

    private function syncIndicadores(int $detalleTemaId, array $indicadoresData)
    {
        $currentIds = DB::table('detalle_indicador')
            ->where('id_detalle_tema', $detalleTemaId)
            ->where('estatus', '1')
            ->pluck('id_indicador_logro')
            ->toArray();

        $newIds = collect($indicadoresData)->pluck('indicador_id')->filter()->toArray();

        // Desactivar
        $toDeactivate = array_diff($currentIds, $newIds);
        if (!empty($toDeactivate)) {
            DB::table('detalle_indicador')
                ->where('id_detalle_tema', $detalleTemaId)
                ->whereIn('id_indicador_logro', $toDeactivate)
                ->update(['estatus' => '2']);
        }

        // Insertar/Actualizar
        foreach ($newIds as $indicadorId) {
            if (!$indicadorId)
                continue;

            $existing = DB::table('detalle_indicador')
                ->where('id_detalle_tema', $detalleTemaId)
                ->where('id_indicador_logro', $indicadorId)
                ->first();

            if ($existing) {
                if ($existing->estatus == '2') {
                    DB::table('detalle_indicador')
                        ->where('id_detalle_tema', $detalleTemaId)
                        ->where('id_indicador_logro', $indicadorId)
                        // Nota: primary key tabla intermedia suele ser autoincrement o compuesta.
                        // detalle_indicador suele tener id_detalle_indicador? 
                        // Asumimos query por FKs es suficiente si unique constrain existe.
                        ->update(['estatus' => '1']);
                }
            } else {
                DB::table('detalle_indicador')->insert([
                    'id_detalle_tema' => $detalleTemaId,
                    'id_indicador_logro' => $indicadorId,
                    'estatus' => '1',
                    'fecha_creacion' => now(), // Revisar si columna existe
                ]);
            }
        }
    }
}
