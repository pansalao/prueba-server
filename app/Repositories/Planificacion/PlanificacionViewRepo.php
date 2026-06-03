<?php

namespace App\Repositories\Planificacion;

use Illuminate\Support\{Facades\DB, Facades\Log};

class PlanificacionViewRepo
{
    /**
     * Obtiene todos los detalles de una planificación específica.
     */
    public function getDetallesPlanificacion(int $planificacionId): ?array
    {
        $dbSogc = DB::connection('external_db')->getDatabaseName();

        // 1. Obtener datos principales de la planificación + Docente + Sección + Unidad + Lapso
        $planificacion = DB::table('planificacion as p')
            ->leftJoin("$dbSogc.seccion_unidad_docente as sud", 'p.id_profesor_asignado', '=', 'sud.sud_codigo')
            ->leftJoin("$dbSogc.usuario as u", 'sud.sud_ced_docente', '=', 'u.usu_cedula')
            ->leftJoin("$dbSogc.persona as per", 'u.usu_cedula', '=', 'per.per_cedula')
            ->leftJoin("$dbSogc.unidad_curricular as uc", 'sud.sud_cod_unidad', '=', 'uc.ucu_codigo')
            ->leftJoin("$dbSogc.seccion as s", 'sud.sud_cod_seccion', '=', 's.sec_codigo')
            ->leftJoin("$dbSogc.lapso_academico as la", 's.sec_cod_lapso_academico', '=', 'la.lap_codigo')
            ->leftJoin("$dbSogc.malla as ma", 'uc.ucu_cod_malla', '=', 'ma.mal_codigo')
            ->leftJoin("$dbSogc.programa as pr", 'ma.mal_cod_programa', '=', 'pr.pro_codigo')
            ->leftJoin("$dbSogc.semestre as sem", 's.sec_cod_semestre', '=', 'sem.sem_codigo')
            ->leftJoin("$dbSogc.trayecto as tr", 'sem.sem_cod_trayecto', '=', 'tr.tra_codigo')
            ->leftJoin("$dbSogc.rol as r", 'u.usu_cod_rol', '=', 'r.rol_codigo')
            ->select(
                'p.id_planificacion as planificacion_id',
                'p.estatus',
                'p.proposito_unidad',
                'u.usu_codigo as docente_id',
                'per.per_nombres as docente_nombre',
                'per.per_apellidos as docente_apellido',
                'r.rol_nombre as docente_rol',
                'u.usu_cedula as cedula',
                'uc.ucu_codigo as id_unidad_curricular',
                'uc.ucu_nombre as nombre_unidad_curricular',
                'uc.ucu_unidad_credito as unidades_credito_unidad_curricular',
                'uc.ucu_thte as horas_semanales_unidad_curricular',
                's.sec_nombre as nombre_seccion',
                'la.lap_codigo as id_lapso_academico',
                'la.lap_nombre as nombre_lapso',
                'la.lap_fecha_inicio as lapso_fecha_inicio',
                'la.lap_fecha_fin as lapso_fecha_fin',
                'pr.pro_nombre as nombre_pnf',
                'tr.tra_nombre as trayecto_unidad_curricular',
                'sem.sem_nombre as duracion_unidad_curricular',
                's.sec_codigo as seccion_id',
                'p.motivo_rechazo_vocero',
                'p.archivo_contrato',
                'p.id_firma_docente',
                'p.id_firma_vocero',
                'p.id_firma_coordinador'
            )
            ->where('p.id_planificacion', $planificacionId)
            ->first();

        if (!$planificacion) {
            return null;
        }

        // Auditar visualización
        $planificacionModel = \App\Models\Planificacion::find($planificacionId);
        if ($planificacionModel) {
            \App\Models\Planificacion::logMostrar($planificacionModel);
        }

        $resultado = (array) $planificacion;

        // Obtener fotos de firmas como base64
        $resultado['firma_docente_b64'] = null;
        $resultado['firma_vocero_b64'] = null;
        $resultado['firma_coordinador_b64'] = null;

        $firmasIds = [
            'docente' => $planificacion->id_firma_docente,
            'vocero' => $planificacion->id_firma_vocero,
            'coordinador' => $planificacion->id_firma_coordinador,
        ];

        foreach ($firmasIds as $rol => $idFirma) {
            if ($idFirma) {
                $fotoFirma = DB::table('firma')->where('id_firma', $idFirma)->value('foto_firma');
                if ($fotoFirma) {
                    // Determinar mime type simple o asumir png
                    $resultado["firma_{$rol}_b64"] = 'data:image/png;base64,' . base64_encode($fotoFirma);
                }
            }
        }

        // 3. Unidades
        $resultado['unidades'] = DB::table('unidad_corte as c')
            ->where('c.id_planificacion', $planificacionId)
            // ->where('c.estatus', '!=', '3') // Comentado para mostrar rechazados
            ->select('c.id_unidad_corte as detalle_id', 'c.numero_unidad_corte as numero', 'c.estatus', 'c.indicador_logro_unidad_corte as indicadores_logro')
            ->orderBy('c.numero_unidad_corte')
            ->get()
            ->map(function ($corte) use ($resultado) {
                $corteArray = (array) $corte;

                // 3.1 Motivo Rechazo (Último)
                $ultimoMotivoRechazo = DB::table('unidad_corte')
                    ->where('id_unidad_corte', $corte->detalle_id)
                    ->select('descripcion_motivo_rechazo_unidad_corte as motivo')
                    ->first();

                $corteArray['ultimo_motivo_rechazo'] = $ultimoMotivoRechazo ? $ultimoMotivoRechazo->motivo : null;

                // 3.2 Recursos (ahora vinculados directamente a unidad_corte)
                $resultadoDetalleRecurso = DB::table('detalle_recurso as dr')
                    ->join('recurso as r', 'dr.id_recurso', '=', 'r.id_recurso')
                    ->where('dr.id_unidad_corte', $corte->detalle_id)
                    ->where('dr.estatus', '1')
                    ->select('r.id_recurso as recurso_id', 'r.nombre_recurso as recurso')
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->toArray();
                $corteArray['recursos'] = $resultadoDetalleRecurso;

                // 3.3 Estrategias (ahora almacenada una sola en unidad_corte según esquema DB)
                $estrategiaDirecta = DB::table('unidad_corte as uc')
                    ->leftJoin('tecnica_actividad as ta', 'uc.id_tecnica_actividad', '=', 'ta.id_tecnica_actividad')
                    ->where('uc.id_unidad_corte', $corte->detalle_id)
                    ->select('uc.id_tecnica_actividad as tecnica_actividad_id', 'ta.nombre_tecnica_actividad', 'uc.descripcion_actividad_unidad_corte as actividad')
                    ->first();

                $corteArray['estrategias'] = $estrategiaDirecta && $estrategiaDirecta->tecnica_actividad_id 
                    ? [['tema_id' => $estrategiaDirecta->tecnica_actividad_id, 'titulo_tema' => $estrategiaDirecta->nombre_tecnica_actividad, 'actividad' => $estrategiaDirecta->actividad]]
                    : [];

                // 3.4 Contenidos (Temas -> Indicadores)
                // OJO: En Create usamos detalle_tema, donde tema se vincula a contenido. 
                // Pero en Show, queremos ver que temas se seleccionaron. 
                // La estructura del array de salida espera 'contenidos' y dentro 'indicadores_logros'.
                // Adaptamos para que 'titulo_contenido' sea el titulo del tema.
    
                $corteArray['contenidos'] = DB::table('detalle_contenido as dc')
                    ->join('contenido as c', 'dc.id_contenido', '=', 'c.id_contenido')
                    ->join('detalle_objetivo as do', 'c.id_contenido', '=', 'do.id_contenido')
                    ->join('objetivo as o', 'do.id_objetivo', '=', 'o.id_objetivo')
                    ->join('tema_unidad as tu', 'o.id_tema_unidad', '=', 'tu.id_tema_unidad')
                    ->where('dc.id_unidad_corte', $corte->detalle_id)
                    ->where('dc.estatus', '1')
                    ->where('tu.id_unidad_curricular', $resultado['id_unidad_curricular'])
                    ->select(
                        'c.id_contenido as contenido_id',
                        'c.titulo_contenido as titulo_contenido',
                        'o.id_objetivo as id_objetivo',
                        'o.titulo_objetivo as titulo_objetivo',
                        'tu.id_tema_unidad as tema_id',
                        'tu.titulo_tema as titulo_tema'
                    )
                    ->groupBy('c.id_contenido') // Agrupamos solo por contenido para que salga una sola vez
                    ->get()
                    ->map(function ($contenidoItem) {
                        $contenidoArray = (array) $contenidoItem;
                        $contenidoArray['indicadores_logros'] = [];
                        return $contenidoArray;
                    })
                    ->toArray();

                // 3.5 Evaluaciones
                $corteArray['evaluaciones'] = DB::table('detalle_evaluacion as dev')
                    ->leftJoin('tipo_evaluacion as eva', 'dev.id_tipo_evaluacion', '=', 'eva.id_tipo_evaluacion')
                    ->leftJoin('tecnica_evaluacion as tec', 'dev.id_tecnica_evaluacion', '=', 'tec.id_tecnica_evaluacion')
                    ->where('dev.id_unidad_corte', $corte->detalle_id)
                    ->where('dev.estatus', '!=', '3')
                    ->select(
                        'dev.id_detalle_evaluacion as detalle_evaluacion_id',
                        'dev.id_tipo_evaluacion as evaluacion_id',
                        'dev.id_tecnica_evaluacion as tecnica_id',
                        'eva.nombre_tipo_evaluacion as evaluacion',
                        'tec.nombre_tecnica_evaluacion as tecnica',
                        'dev.ponderacion_detalle_evaluacion as ponderacion',
                        'dev.fecha_evaluacion_detalle_evaluacion as fecha_evaluacion',
                        'dev.forma_participacion_detalle_evaluacion as forma_participacion',
                        'dev.integrantes_detalle_evaluacion as integrantes'
                    )
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->values()
                    ->toArray();

                // 3.6 Bibliografías
                $corteArray['bibliografias'] = DB::table('detalle_bibliografia as db')
                    ->join('bibliografia as b', 'db.id_bibliografia', '=', 'b.id_bibliografia')
                    ->where('db.id_unidad_corte', $corte->detalle_id)
                    ->where('db.estatus', '1')
                    ->select('b.id_bibliografia as bibliografia_id', 'b.nombre_bibliografia as bibliografia')
                    ->distinct()
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->toArray();

                return $corteArray;
            })
            ->toArray();

        // 4. Coordinador (Datos ficticios o reales dependiendo de permisos, 
        // lo dejamos igual pero ajustando nombres de tablas si fuera necesario)
        $coordinador = DB::table("$dbSogc.usuario as u")
            ->join("$dbSogc.persona as p", 'u.usu_cedula', '=', 'p.per_cedula')
            ->where('u.usu_cod_rol', 11) // 11 = Coordinador en SOGAC
            ->select('p.per_nombres as name', 'p.per_apellidos as apellido', 'u.usu_cedula as cedula')
            ->first();

        if ($coordinador) {
            $resultado['coordinador_nombre'] = $coordinador->name;
            $resultado['coordinador_apellido'] = $coordinador->apellido;
            $resultado['coordinador_cedula'] = $coordinador->cedula;
        } else {
            $resultado['coordinador_nombre'] = $resultado['coordinador_apellido'] = $resultado['coordinador_cedula'] = '';
        }

        return $resultado;
    }
}
