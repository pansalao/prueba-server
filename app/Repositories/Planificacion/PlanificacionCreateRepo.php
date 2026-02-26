<?php

namespace App\Repositories\Planificacion;

use Illuminate\Support\{Facades\DB, Facades\Log, Facades\Auth};

class PlanificacionCreateRepo
{
    public function select_tabla($tableName, $idColumnName, $displayColumnName, $whereConditions = [], $orderByColumn = null, $orderByDirection = 'asc')
    {
        try {
            $query = DB::table($tableName)->select($idColumnName, $displayColumnName);

            foreach ($whereConditions as $condition) {
                $query->where(...$condition);
            }

            return $orderByColumn ? $query->orderBy($orderByColumn, $orderByDirection)->get() : $query->get();
        } catch (\Exception $e) {
            Log::error("Error en select_tabla para {$tableName}: {$e->getMessage()}");
            throw $e;
        }
    }

    public function select_tecnica()
    {
        return $this->select_tabla('tecnica_evaluacion', 'id_tecnica', 'nombre_tecnica_evaluacion', [['estatus', '1']]);
    }

    public function select_tecnica_actividad()
    {
        return $this->select_tabla('tecnica_actividad', 'id_tecnica_actividad', 'nombre_tecnica_actividad', [['estatus', '1']]);
    }

    public function select_recursos()
    {
        return $this->select_tabla('recurso', 'id_recurso', 'nombre_recurso', [['estatus', '1']]);
    }


    public function select_evaluaciones()
    {
        return $this->select_tabla('evaluacion', 'id_evaluacion', 'nombre_evaluacion', [['estatus', '1']]);
    }

    public function select_bibliografias()
    {
        return $this->select_tabla('bibliografia', 'id_bibliografia', 'nombre_bibliografia', [['estatus', '1']]);
    }

    public function select_temas_por_unidad($idUnidadCurricular = null)
    {
        $query = DB::table('tema_unidad')
            ->where('estatus', '1');

        if ($idUnidadCurricular) {
            $query->where('id_unidad_curricular', $idUnidadCurricular);
        }

        return $query->select('id_tema_unidad', 'titulo_tema', 'unidad_tema')
            ->orderBy('id_tema_unidad')
            ->get();
    }

    public function select_contenidos($idUnidadCurricular = null)
    {
        $query = DB::table('contenido as c')
            ->join('objetivo as o', 'c.id_objetivo', '=', 'o.id_objetivo')
            ->join('tema_unidad as t', 'o.id_tema_unidad', '=', 't.id_tema_unidad')
            ->where('c.estatus', '1')
            ->where('t.estatus', '1');

        if ($idUnidadCurricular) {
            $query->where('t.id_unidad_curricular', $idUnidadCurricular);
        }

        return $query->select(
            'c.id_contenido',
            'c.titulo_contenido',
            'c.id_objetivo',
            'o.id_tema_unidad',
            't.unidad_tema'
        )
            ->orderBy('c.id_contenido')
            ->get();
    }

    public function select_objetivos($idUnidadCurricular = null)
    {
        $query = DB::table('objetivo as o')
            ->join('tema_unidad as t', 'o.id_tema_unidad', '=', 't.id_tema_unidad')
            ->where('o.estatus', '1')
            ->where('t.estatus', '1');

        if ($idUnidadCurricular) {
            $query->where('t.id_unidad_curricular', $idUnidadCurricular);
        }

        return $query->select(
            'o.id_objetivo',
            'o.titulo_objetivo',
            'o.id_tema_unidad',
            't.unidad_tema'
        )
            ->orderBy('o.id_objetivo')
            ->get();
    }

    // Nueva función vital: Obtener las asignaciones del docente logueado
    public function getAsignacionesDocente($userId = null)
    {
        $query = DB::table('detalle_profesor_asignado as dpa')
            ->join('unidad_curricular as uc', 'dpa.id_unidad_curricular', '=', 'uc.id_unidad_curricular')
            ->join('seccion as s', 'dpa.id_seccion', '=', 's.id_seccion')
            ->join('users as u', 'dpa.id_users', '=', 'u.id')
            ->where('dpa.estatus', '1');

        if ($userId) {
            $query->where('dpa.id_users', $userId);
        }

        return $query->select(
            'dpa.id_detalle_profesor_asignado',
            'uc.nombre_unidad_curricular',
            'uc.trayecto_unidad_curricular',
            's.nombre_seccion',
            'u.name',
            'u.apellido'
        )
            ->get()
            ->map(function ($asignacion) {
                $trayecto = $asignacion->trayecto_unidad_curricular ? "T{$asignacion->trayecto_unidad_curricular}" : 'S/T';
                $docente = "{$asignacion->name} {$asignacion->apellido}";
                $asignacion->descripcion_completa = "{$asignacion->nombre_unidad_curricular} ({$trayecto}) - Sección {$asignacion->nombre_seccion} | Docente: {$docente}";
                return $asignacion;
            });
    }

    public function hasDocenteOrCoordinadorRole($userId)
    {
        return DB::table('usuario_rol')->where('id_users', $userId)->where('id_rol', 1)->exists() ||
            DB::table('usuario_rol')->where('id_users', $userId)->where('id_rol', 2)->exists();
    }

    public function getDetalleProfesorAsignado($id)
    {
        return DB::table('detalle_profesor_asignado')->where('id_detalle_profesor_asignado', $id)->first();
    }

    public function getUnidadCurricular($id)
    {
        return DB::table('unidad_curricular')->where('id_unidad_curricular', $id)->first();
    }

    public function saveNuevoObjetivo($titulo, $idTemaUnidad)
    {
        return \App\Models\Objetivo::create([
            'titulo_objetivo' => $titulo,
            'id_tema_unidad' => $idTemaUnidad,
            'estatus' => '1',
            'fecha_creacion' => now(),
        ]);
    }

    public function savePlanificacionTransaccion($idProfesorAsignado, $unidades)
    {
        DB::beginTransaction();

        try {
            $planificacionData = [
                'id_profesor_asignado' => $idProfesorAsignado,
                'fecha_creacion' => now(),
                'estatus' => '2', // Pendiente por defecto
            ];

            $planificacion = \App\Models\Planificacion::create($planificacionData);
            $planificacionId = $planificacion->getKey();

            foreach ($unidades as $unidad) {
                $unidadCorte = \App\Models\UnidadCorte::create([
                    'id_planificacion' => $planificacionId,
                    'numero_unidad_corte' => $unidad['numero'],
                    'indicador_logro_unidad_corte' => $unidad['indicadores_logro'] ?? null,
                    'fecha_creacion' => now(),
                    'estatus' => '2',
                ]);
                $unidadId = $unidadCorte->getKey();

                foreach ($unidad['objetivos'] as $objetivo) {
                    foreach ($objetivo['contenidos'] as $contenido) {
                        if (!empty($contenido['contenido_id'])) {
                            \App\Models\DetalleContenido::create([
                                'id_unidad_corte' => $unidadId,
                                'id_contenido' => $contenido['contenido_id'],
                                'fecha_creacion' => now(),
                                'estatus' => '1',
                            ]);
                        }
                    }
                }

                foreach ($unidad['estrategias'] as $estrategia) {
                    if (!empty($estrategia['tema_id']) && !empty($estrategia['actividad'])) {

                        $detalleEstrategia = \App\Models\DetalleEstrategia::create([
                            'id_unidad_corte' => $unidadId,
                            'id_tema_unidad' => $estrategia['tema_id'],
                            'actividad' => $estrategia['actividad'],
                            'fecha_creacion' => now(),
                            'estatus' => '1',
                        ]);
                        $estrategiaId = $detalleEstrategia->getKey();

                        foreach ($estrategia['recursos'] as $recurso) {
                            if (!empty($recurso['recurso_id'])) {
                                \App\Models\DetalleEstrategiaRecurso::create([
                                    'id_detalle_estrategia' => $estrategiaId,
                                    'id_recurso' => $recurso['recurso_id'],
                                    'fecha_creacion' => now(),
                                    'estatus' => '1',
                                ]);
                            }
                        }
                    }
                }

                foreach ($unidad['evaluaciones'] as $evaluacion) {
                    if (!empty($evaluacion['evaluacion_id'])) {
                        \App\Models\DetalleEvaluacion::create([
                            'id_unidad_corte' => $unidadId,
                            'id_evaluacion' => $evaluacion['evaluacion_id'],
                            'id_tecnica' => $evaluacion['tecnica_id'],
                            'id_instrumento' => null, // null for now as per schema
                            'ponderacion_detalle_evaluacion' => $evaluacion['ponderacion'],
                            'integrantes_detalle_evaluacion' => ($evaluacion['forma_participacion'] == '2') ? ($evaluacion['integrantes'] ?? null) : 1, // 1 if individual
                            'fecha_evaluacion_detalle_evaluacion' => $evaluacion['fecha_evaluacion'],
                            'forma_participacion_detalle_evaluacion' => $evaluacion['forma_participacion'],
                            'fecha_creacion' => now(),
                            'estatus' => '1',
                        ]);
                    }
                }

                // Save bibliographies for this unit
                foreach ($unidad['bibliografias'] as $bibliografia) {
                    if (!empty($bibliografia['bibliografia_id'])) {
                        \App\Models\DetalleBibliografia::create([
                            'id_unidad_corte' => $unidadId,
                            'id_bibliografia' => $bibliografia['bibliografia_id'],
                            'fecha_creacion' => now(),
                            'estatus' => '1',
                        ]);
                    }
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
