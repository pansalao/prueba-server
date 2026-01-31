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
                's.nombre_seccion',
                'la.nombre_lapso_academico as nombre_lapso',
                'la.fecha_inicio_lapso_academico as lapso_fecha_inicio',
                'la.fecha_fin_lapso_academico as lapso_fecha_fin'
            )
            ->where('p.id_planificacion', $planificacionId)
            ->first();

        if (!$planificacion) {
            return null;
        }

        $resultado = (array) $planificacion;

        // 2. Bibliografías
        $resultado['bibliografias'] = DB::table('detalle_bibliografia as db')
            ->join('bibliografia as b', 'db.id_bibliografia', '=', 'b.id_bibliografia')
            ->where('db.id_planificacion', $planificacionId)
            ->where('db.estatus', '1')
            ->select('b.id_bibliografia as bibliografia_id', 'b.nombre_bibliografia as bibliografia')
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();

        // 3. Cortes
        $resultado['cortes'] = DB::table('unidad as c')
            ->where('c.id_planificacion', $planificacionId)
            // ->where('c.estatus', '!=', '3') // Comentado para mostrar rechazados
            ->select('c.id_unidad as detalle_id', 'c.numero_unidad as corte', 'c.estatus')
            ->orderBy('c.numero_unidad')
            ->get()
            ->map(function ($corte) {
                $corteArray = (array) $corte;

                // 3.1 Motivo Rechazo (Último)
                $ultimoMotivoRechazo = DB::table('motivo_rechazo')
                    ->where('id_unidad', $corte->detalle_id)
                    ->orderBy('fecha_creacion', 'desc')
                    ->select('descripcion_motivo_rechazo as motivo')
                    ->where('estatus', '1')
                    ->first();

                $corteArray['ultimo_motivo_rechazo'] = $ultimoMotivoRechazo ? $ultimoMotivoRechazo->motivo : null;

                // 3.2 Recursos
                $resultadoDetalleRecurso = DB::table('detalle_recurso as dr')
                    ->join('recurso as r', 'dr.id_recurso', '=', 'r.id_recurso')
                    ->where('dr.id_unidad', $corte->detalle_id)
                    ->where('dr.estatus', '1')
                    ->select('r.id_recurso as recurso_id', 'r.nombre_recurso as recurso')
                    ->get()
                    ->map(fn($item) => (array) $item)
                    ->toArray();
                $corteArray['recursos'] = $resultadoDetalleRecurso;

                // 3.3 Estrategias
                $corteArray['estrategias'] = DB::table('detalle_estrategia_pedagogica as de')
                    ->join('estrategia_pedagogica as e', 'de.id_estrategia_pedagogica', '=', 'e.id_estrategia_pedagogica')
                    ->where('de.id_unidad', $corte->detalle_id)
                    ->where('de.estatus', '1')
                    ->select('e.id_estrategia_pedagogica as estrategia_id', 'e.nombre_estrategia_pedagogica as estrategia')
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
                    ->where('dc.id_unidad', $corte->detalle_id)
                    ->where('dc.estatus', '1')
                    ->select(
                        'c.id_contenido as contenido_id',
                        'dc.id_detalle_contenido as detalle_contenido_id',
                        'c.titulo_contenido as titulo_contenido',
                        'c.descripcion_contenido as descripcion_contenido'
                    )
                    ->get()
                    ->map(function ($contenidoItem) { // Rename $tema to $contenidoItem
                        $contenidoArray = (array) $contenidoItem;

                        // 3.4.1 Indicadores (vinculados a detalle_contenido)
                        $indicadores = DB::table('detalle_indicador as di')
                            ->join('indicador_logro as il', 'di.id_indicador_logro', '=', 'il.id_indicador_logro')
                            ->where('di.id_detalle_contenido', $contenidoItem->detalle_contenido_id)
                            ->where('di.estatus', '1')
                            ->select('il.id_indicador_logro as indicador_id', 'il.nombre_indicador_logro as descripcion_indicador')
                            ->get()
                            ->map(fn($item) => (array) $item)
                            ->toArray();

                        $contenidoArray['indicadores_logros'] = array_values(array_unique($indicadores, SORT_REGULAR));
                        return $contenidoArray;
                    })
                    ->toArray();

                // 3.5 Evaluaciones
                $corteArray['evaluaciones'] = DB::table('detalle_evaluacion as dev')
                    ->leftJoin('evaluacion as eva', 'dev.id_evaluacion', '=', 'eva.id_evaluacion')
                    ->leftJoin('tecnica as tec', 'dev.id_tecnica', '=', 'tec.id_tecnica')
                    ->where('dev.id_unidad', $corte->detalle_id)
                    ->where('dev.estatus', '!=', '3')
                    ->select(
                        'dev.id_detalle_evaluacion as detalle_evaluacion_id',
                        'dev.id_evaluacion as evaluacion_id',
                        'dev.id_tecnica as tecnica_id',
                        'eva.nombre_evaluacion as evaluacion',
                        'tec.nombre_tecnica as tecnica',
                        'dev.ponderacion_detalle_evaluacion as ponderacion',
                        'dev.fecha_evaluacion_detalle_evaluacion as fecha_evaluacion',
                        'dev.forma_participacion_detalle_evaluacion as forma_participacion'
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
