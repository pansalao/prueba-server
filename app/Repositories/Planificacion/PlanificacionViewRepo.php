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
        // 1. Obtener datos principales de la planificación + Docente + Sección + Unidad + Lapso
        $planificacion = DB::table('planificacion as p')
            ->join('detalle_profesor_asignado as dpa', 'p.id_profesor_asignado', '=', 'dpa.id_detalle_profesor_asignado')
            ->join('users as u', 'dpa.id_users', '=', 'u.id')
            ->join('unidad_curricular as uc', 'dpa.id_unidad_curricular', '=', 'uc.id_unidad_curricular')
            ->leftJoin('malla_academica as ma', 'uc.id_malla_academica', '=', 'ma.id_malla_academica')
            ->leftJoin('pnf as pnf', 'ma.id_pnf', '=', 'pnf.id_pnf')
            ->join('seccion as s', 'dpa.id_seccion', '=', 's.id_seccion')
            ->join('lapso_academico as la', 's.id_lapso_academico', '=', 'la.id_lapso_academico')
            ->select(
                'p.id_planificacion as planificacion_id',
                'p.estatus',
                'u.id as docente_id',
                'u.name as docente_nombre',
                'u.apellido as docente_apellido',
                'u.cedula',
                'u.telefono',
                'uc.id_unidad_curricular',
                'uc.nombre_unidad_curricular',
                'uc.unidades_credito_unidad_curricular',
                'uc.trayecto_unidad_curricular',
                'uc.duracion_unidad_curricular',
                'uc.horas_semanales_unidad_curricular',
                'uc.proposito_unidad_curricular',
                'pnf.nombre_pnf',
                's.nombre_seccion',
                'la.id_lapso_academico',
                'la.nombre_lapso_academico as nombre_lapso',
                'la.fecha_inicio_lapso_academico as lapso_fecha_inicio',
                'la.fecha_fin_lapso_academico as lapso_fecha_fin'
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

        // 2. Bibliografías (se obtienen a través de unidad_corte)
        $resultado['bibliografias'] = DB::table('detalle_bibliografia as db')
            ->join('bibliografia as b', 'db.id_bibliografia', '=', 'b.id_bibliografia')
            ->join('unidad_corte as uc_bib', 'db.id_unidad_corte', '=', 'uc_bib.id_unidad_corte')
            ->where('uc_bib.id_planificacion', $planificacionId)
            ->where('db.estatus', '1')
            ->select('b.id_bibliografia as bibliografia_id', 'b.nombre_bibliografia as bibliografia')
            ->distinct()
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        // 3. Cortes
        $resultado['cortes'] = DB::table('unidad_corte as c')
            ->where('c.id_planificacion', $planificacionId)
            // ->where('c.estatus', '!=', '3') // Comentado para mostrar rechazados
            ->select('c.id_unidad_corte as detalle_id', 'c.numero_unidad_corte as corte', 'c.estatus', 'c.indicador_logro_unidad_corte as indicadores_logros')
            ->orderBy('c.numero_unidad_corte')
            ->get()
            ->map(function ($corte) {
                $corteArray = (array) $corte;

                // 3.1 Motivo Rechazo (Último)
                $ultimoMotivoRechazo = DB::table('unidad_corte')
                    ->where('id_unidad_corte', $corte->detalle_id)
                    ->select('descripcion_motivo_rechazo_unidad_corte as motivo')
                    ->first();

                $corteArray['ultimo_motivo_rechazo'] = $ultimoMotivoRechazo ? $ultimoMotivoRechazo->motivo : null;

                // 3.2 Recursos
                $resultadoDetalleRecurso = DB::table('detalle_estrategia_recurso as der')
                    ->join('detalle_estrategia as de', 'der.id_detalle_estrategia', '=', 'de.id_detalle_estrategia')
                    ->join('recurso as r', 'der.id_recurso', '=', 'r.id_recurso')
                    ->where('de.id_unidad_corte', $corte->detalle_id)
                    ->where('der.estatus', '1')
                    ->select('r.id_recurso as recurso_id', 'r.nombre_recurso as recurso')
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->toArray();
                $corteArray['recursos'] = $resultadoDetalleRecurso;

                // 3.3 Estrategias
                $corteArray['estrategias'] = DB::table('detalle_estrategia as de')
                    ->join('tema_unidad as tu', 'de.id_tema_unidad', '=', 'tu.id_tema_unidad')
                    ->where('de.id_unidad_corte', $corte->detalle_id)
                    ->where('de.estatus', '1')
                    ->select('de.id_tema_unidad as tema_id', 'tu.titulo_tema', 'de.actividad')
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->toArray();

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
                    ->select(
                        'c.id_contenido as contenido_id',
                        'dc.id_detalle_contenido as detalle_contenido_id',
                        'c.titulo_contenido as titulo_contenido',
                        'do.id_objetivo',
                        'o.titulo_objetivo',
                        'tu.id_tema_unidad as tema_id',
                        'tu.titulo_tema as titulo_tema'
                    )
                    ->get()
                    ->map(function ($contenidoItem) { // Rename $tema to $contenidoItem
                        $contenidoArray = (array) $contenidoItem;

                        // 3.4.1 Indicadores - No hay tabla detalle_indicador en este esquema, se usa el campo en unidad_corte o se omite si no aplica.
                        $indicadores = []; // Schema uses a text field in unidad_corte for indicators
        
                        $contenidoArray['indicadores_logros'] = $indicadores;
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
                    ->toArray();

                return $corteArray;
            })
            ->toArray();

        // 4. Coordinador (Datos ficticios o reales dependiendo de permisos, 
        // lo dejamos igual pero ajustando nombres de tablas si fuera necesario)
        $coordinador = DB::table('users as u')
            ->join('usuario_rol as ur', 'u.id', '=', 'ur.id_users')
            ->where('ur.id_rol', 1) // 1 = Coordinador
            ->select('u.name', 'u.apellido', 'u.cedula')
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
