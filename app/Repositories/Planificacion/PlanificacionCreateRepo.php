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
        return $this->select_tabla('tecnica_evaluacion', 'id_tecnica_evaluacion', 'nombre_tecnica_evaluacion', [['estatus', '1']]);
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
        return $this->select_tabla('tipo_evaluacion', 'id_tipo_evaluacion', 'nombre_tipo_evaluacion', [['estatus', '1']]);
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
            ->join('detalle_objetivo as do', 'c.id_contenido', '=', 'do.id_contenido')
            ->join('objetivo as o', 'do.id_objetivo', '=', 'o.id_objetivo')
            ->join('tema_unidad as t', 'o.id_tema_unidad', '=', 't.id_tema_unidad')
            ->where('c.estatus', '1')
            ->where('t.estatus', '1');

        if ($idUnidadCurricular) {
            $query->where('t.id_unidad_curricular', $idUnidadCurricular);
        }

        return $query->select(
            'c.id_contenido',
            'c.titulo_contenido',
            'do.id_objetivo',
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
        $query = DB::connection('emulacion_sogac_2')
            ->table('seccion_unidad_docente as sud')
            ->join('unidad_curricular as uc', 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
            ->join('seccion as s', 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->join('usuario as u', 'sud.sud_ced_docente', '=', 'u.usu_cedula')
            ->join('persona as p', 'u.usu_cedula', '=', 'p.per_cedula')
            ->where('sud.sud_estatus', 'A');

        if ($userId) {
            $query->where('u.usu_codigo', $userId);
        }

        return $query->select(
            'sud.sud_codigo as id_detalle_profesor_asignado',
            'uc.ucu_nombre as nombre_unidad_curricular',
            'uc.ucu_codigo as id_unidad_curricular',
            's.sec_nombre as nombre_seccion',
            'p.per_nombres as name',
            'p.per_apellidos as apellido'
        )
            ->get()
            ->map(function ($asignacion) {
                $docente = "{$asignacion->name} {$asignacion->apellido}";
                $asignacion->descripcion_completa = "{$asignacion->nombre_unidad_curricular} - Sección {$asignacion->nombre_seccion} | Docente: {$docente}";
                return $asignacion;
            });
    }

    public function getLapsoAcademicoByAsignacion($idAsignacion)
    {
        return DB::connection('emulacion_sogac_2')
            ->table('seccion_unidad_docente as sud')
            ->join('seccion as s', 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->join('lapso_academico as l', 's.sec_cod_lapso_academico', '=', 'l.lap_codigo')
            ->where('sud.sud_codigo', $idAsignacion)
            ->select('l.lap_nombre', 'l.lap_fecha_inicio', 'l.lap_fecha_fin', 'l.lap_codigo')
            ->first();
    }

    public function hasDocenteOrCoordinadorRole($userId)
    {
        // El rol_id para Coordinador es 11 y para Docente es 3 en emulacion_sogac_2
        return DB::connection('emulacion_sogac_2')
            ->table('usuario')
            ->where('usu_codigo', $userId)
            ->whereIn('usu_cod_rol', [3, 11])
            ->where('usu_estatus', 'A')
            ->exists();
    }

    public function isCoordinador($userId)
    {
        // El rol_id para Coordinador es 11 en emulacion_sogac_2
        return DB::connection('emulacion_sogac_2')
            ->table('usuario')
            ->where('usu_codigo', $userId)
            ->where('usu_cod_rol', 11)
            ->where('usu_estatus', 'A')
            ->exists();
    }

    public function getMallaByAsignacion($idAsignacion)
    {
        return DB::connection('emulacion_sogac_2')
            ->table('seccion_unidad_docente as sud')
            ->join('seccion as s', 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->join('malla as m', 's.sec_cod_malla', '=', 'm.mal_codigo')
            ->where('sud.sud_codigo', $idAsignacion)
            ->select('m.mal_nombre', 'm.mal_codigo')
            ->first();
    }

    public function getDetalleProfesorAsignado($id)
    {
        return DB::connection('emulacion_sogac_2')
            ->table('seccion_unidad_docente')
            ->where('sud_codigo', $id)
            ->select('sud_codigo as id_detalle_profesor_asignado', 'sud_cod_unidad as id_unidad_curricular', 'sud_cod_seccion as id_seccion')
            ->first();
    }

    public function getUnidadCurricular($id)
    {
        return DB::connection('emulacion_sogac_2')
            ->table('unidad_curricular')
            ->where('ucu_codigo', $id)
            ->select('ucu_codigo as id_unidad_curricular', 'ucu_nombre as nombre_unidad_curricular')
            ->first();
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
                            'id_tipo_evaluacion' => $evaluacion['evaluacion_id'],
                            'id_tecnica_evaluacion' => $evaluacion['tecnica_id'],
                            'id_instrumento' => null, // null for now as per schema
                            'ponderacion_detalle_evaluacion' => $evaluacion['ponderacion'],
                            'integrantes_detalle_evaluacion' => ($evaluacion['forma_participacion'] == '2') ? ($evaluacion['integrantes'] ?? null) : 1, // 1 if individual
                            'fecha_evaluacion_detalle_evaluacion' => $evaluacion['fecha_evaluacion'],
                            'forma_participacion_detalle_evaluacion' => $evaluacion['forma_participacion'],
                            'fecha_creacion' => now(),
                            'estatus' => '2',
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
