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
        $query = DB::connection('external_db')
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
            ->distinct()
            ->get()
            ->map(function ($asignacion) {
                $asignacion->descripcion_completa = "{$asignacion->nombre_unidad_curricular} - Sección {$asignacion->nombre_seccion}";
                return $asignacion;
            });
    }

    public function getLapsoAcademicoByAsignacion($idAsignacion)
    {
        return DB::connection('external_db')
            ->table('seccion_unidad_docente as sud')
            ->join('seccion as s', 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->join('lapso_academico as l', 's.sec_cod_lapso_academico', '=', 'l.lap_codigo')
            ->where('sud.sud_codigo', $idAsignacion)
            ->select('l.lap_nombre', 'l.lap_fecha_inicio', 'l.lap_fecha_fin', 'l.lap_codigo')
            ->first();
    }

    public function hasDocenteOrCoordinadorRole($userId)
    {
        // El rol_id para Coordinador puede ser 1, 5 u 11; para Docente es 2 o 3 en external_db
        return DB::connection('external_db')
            ->table('usuario')
            ->where('usu_codigo', $userId)
            ->whereIn('usu_cod_rol', [1, 2, 3, 5, 11])
            ->where('usu_estatus', 'A')
            ->exists();
    }

    public function isCoordinador($userId)
    {
        // El rol_id para Coordinador puede ser 1, 5 u 11 en external_db
        return DB::connection('external_db')
            ->table('usuario')
            ->where('usu_codigo', $userId)
            ->whereIn('usu_cod_rol', [1, 5, 11])
            ->where('usu_estatus', 'A')
            ->exists();
    }

    public function getMallaByAsignacion($idAsignacion)
    {
        return DB::connection('external_db')
            ->table('seccion_unidad_docente as sud')
            ->join('seccion as s', 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->join('malla as m', 's.sec_cod_malla', '=', 'm.mal_codigo')
            ->where('sud.sud_codigo', $idAsignacion)
            ->select('m.mal_nombre', 'm.mal_codigo')
            ->first();
    }

    public function getDetalleProfesorAsignado($id)
    {
        return DB::connection('external_db')
            ->table('seccion_unidad_docente')
            ->where('sud_codigo', $id)
            ->select('sud_codigo as id_detalle_profesor_asignado', 'sud_cod_unidad as id_unidad_curricular', 'sud_cod_seccion as id_seccion')
            ->first();
    }

    public function getUnidadCurricular($id)
    {
        return DB::connection('external_db')
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
            'estatus' => '1'
        ]);
    }

    public function saveNuevaBibliografia($nombre)
    {
        return \App\Models\Bibliografia::firstOrCreate(
            ['nombre_bibliografia' => $nombre],
            ['estatus' => '1', 'fecha_creacion' => now()]
        );
    }

    public function findOrCreateTecnicaActividad($nombre)
    {
        return \App\Models\Estrategia::firstOrCreate(
            ['nombre_tecnica_actividad' => $nombre],
            ['estatus' => '1']
        )->id_tecnica_actividad;
    }

    public function findOrCreateRecurso($nombre)
    {
        return \App\Models\Recurso::firstOrCreate(
            ['nombre_recurso' => $nombre],
            ['estatus' => '1']
        )->id_recurso;
    }

    public function findOrCreateTipoEvaluacion($nombre)
    {
        return \App\Models\TipoEvaluacion::firstOrCreate(
            ['nombre_tipo_evaluacion' => $nombre],
            ['estatus' => '1']
        )->id_tipo_evaluacion;
    }

    public function findOrCreateTecnicaEvaluacion($nombre)
    {
        return \App\Models\TecnicaEvaluacion::firstOrCreate(
            ['nombre_tecnica_evaluacion' => $nombre],
            ['estatus' => '1']
        )->id_tecnica_evaluacion;
    }

    public function findOrCreateBibliografia($nombre)
    {
        return $this->saveNuevaBibliografia($nombre)->id_bibliografia;
    }

    public function savePlanificacionTransaccion($idProfesorAsignado, $unidades, $tiposSeccion = [], $estatus = '2', $proposito_unidad = null, $idFirmaDocente = null)
    {
        DB::beginTransaction();

        try {
            $planificacionData = [
                'id_profesor_asignado' => $idProfesorAsignado,
                'estatus' => $estatus,
                'tipo_planificacion' => json_encode($tiposSeccion),
                'proposito_unidad' => $proposito_unidad,
                'id_firma_docente' => $idFirmaDocente,
            ];

            $planificacion = \App\Models\Planificacion::create($planificacionData);
            $planificacionId = $planificacion->getKey();

            foreach ($unidades as $unidad) {
                $unidadCorte = \App\Models\UnidadCorte::create([
                    'id_planificacion' => $planificacionId,
                    'numero_unidad_corte' => $unidad['numero'],
                    'indicador_logro_unidad_corte' => $unidad['indicadores_logro'] ?? null,
                    'estatus' => '2',
                ]);
                $unidadId = $unidadCorte->getKey();

                $processedContenidos = [];
                foreach ($unidad['objetivos'] as $objetivo) {
                    foreach ($objetivo['contenidos'] as $contenido) {
                        if (!empty($contenido['contenido_id']) && !in_array($contenido['contenido_id'], $processedContenidos)) {
                            \App\Models\DetalleContenido::create([
                                'id_unidad_corte' => $unidadId,
                                'id_contenido' => $contenido['contenido_id'],
                                'estatus' => '1',
                            ]);
                            $processedContenidos[] = $contenido['contenido_id'];
                        }
                    }
                }

                // Guardar Estrategia (Técnica y Actividad) directamente en unidad_corte según el esquema de la DB
                if (!empty($unidad['estrategias'])) {
                    $estrategiaPrincipal = $unidad['estrategias'][0];
                    $tecnicaActividadId = !empty($estrategiaPrincipal['tecnica_actividad_id'])
                        ? $this->findOrCreateTecnicaActividad($estrategiaPrincipal['tecnica_actividad_id'])
                        : null;

                    $unidadCorte->update([
                        'id_tecnica_actividad' => $tecnicaActividadId,
                        'descripcion_actividad_unidad_corte' => $estrategiaPrincipal['actividad'] ?: null,
                    ]);

                    // Guardar Recursos asociados a la unidad en detalle_recurso
                    $processedRecursos = [];
                    foreach ($unidad['estrategias'] as $estrategia) {
                        foreach ($estrategia['recursos'] as $recurso) {
                            if (!empty($recurso['recurso_id']) && !in_array($recurso['recurso_id'], $processedRecursos)) {
                                $recursoId = $this->findOrCreateRecurso($recurso['recurso_id']);
                                DB::table('detalle_recurso')->insert([
                                    'id_unidad_corte' => $unidadId,
                                    'id_recurso' => $recursoId,
                                    'estatus' => '1',
                                ]);
                                $processedRecursos[] = $recurso['recurso_id'];
                            }
                        }
                    }
                }

                foreach ($unidad['evaluaciones'] as $evaluacion) {
                    if (!empty($evaluacion['evaluacion_id'])) {
                        $tipoEvalId = $this->findOrCreateTipoEvaluacion($evaluacion['evaluacion_id']);
                        $tecnicaEvalId = $this->findOrCreateTecnicaEvaluacion($evaluacion['tecnica_id']);

                        \App\Models\DetalleEvaluacion::create([
                            'id_unidad_corte' => $unidadId,
                            'id_tipo_evaluacion' => $tipoEvalId,
                            'id_tecnica_evaluacion' => $tecnicaEvalId,
                            'id_instrumento' => null, // null for now as per schema
                            'ponderacion_detalle_evaluacion' => $evaluacion['ponderacion'],
                            'integrantes_detalle_evaluacion' => ($evaluacion['forma_participacion'] == '2') ? ($evaluacion['integrantes'] ?? null) : 1, // 1 if individual
                            'fecha_evaluacion_detalle_evaluacion' => $evaluacion['fecha_evaluacion'],
                            'forma_participacion_detalle_evaluacion' => $evaluacion['forma_participacion'],
                            'estatus' => '2',
                        ]);
                    }
                }

                // Save bibliographies for this unit
                foreach ($unidad['bibliografias'] as $bibliografia) {
                    if (!empty($bibliografia['bibliografia_id'])) {
                        $biblioId = $this->findOrCreateBibliografia($bibliografia['bibliografia_id']);
                        \App\Models\DetalleBibliografia::create([
                            'id_unidad_corte' => $unidadId,
                            'id_bibliografia' => $biblioId,
                            'estatus' => '1',
                        ]);
                    }
                }
            }

            DB::commit();
            return $planificacionId;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
