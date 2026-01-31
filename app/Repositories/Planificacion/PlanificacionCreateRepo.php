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

    public function select_tecnicas()
    {
        return $this->select_tabla('tecnica', 'id_tecnica', 'nombre_tecnica', [['estatus', '1']]);
    }

    public function select_recursos()
    {
        return $this->select_tabla('recurso', 'id_recurso', 'nombre_recurso', [['estatus', '1']]);
    }

    public function select_estrategias()
    {
        return $this->select_tabla('estrategia_pedagogica', 'id_estrategia_pedagogica', 'nombre_estrategia_pedagogica', [['estatus', '1']]);
    }

    public function select_evaluaciones()
    {
        return $this->select_tabla('evaluacion', 'id_evaluacion', 'nombre_evaluacion', [['estatus', '1']]);
    }

    public function select_indicadores()
    {
        return $this->select_tabla('indicador_logro', 'id_indicador_logro', 'nombre_indicador_logro', [['estatus', '1']]);
    }

    public function select_bibliografias()
    {
        return $this->select_tabla('bibliografia', 'id_bibliografia', 'nombre_bibliografia', [['estatus', '1']]);
    }

    public function select_temas_por_unidad($idUnidadCurricular = null)
    {
        $query = DB::table('tema')
            ->where('estatus', '1');

        if ($idUnidadCurricular) {
            $query->where('id_unidad_curricular', $idUnidadCurricular);
        }

        return $query->select('id_tema', 'titulo_tema', 'unidad_tema')
            ->orderBy('id_tema')
            ->get();
    }

    public function select_contenidos($idUnidadCurricular = null)
    {
        $query = DB::table('contenido as c')
            ->join('tema as t', 'c.id_tema', '=', 't.id_tema')
            ->where('c.estatus', '1')
            ->where('t.estatus', '1');

        if ($idUnidadCurricular) {
            $query->where('t.id_unidad_curricular', $idUnidadCurricular);
        }

        return $query->select(
            'c.id_contenido',
            'c.titulo_contenido',
            'c.descripcion_contenido',
            'c.id_tema',
            't.unidad_tema'
        )
            ->orderBy('c.id_contenido')
            ->get();
    }

    // Nueva función vital: Obtener las asignaciones del docente logueado
    public function getAsignacionesDocente($userId)
    {
        return DB::table('detalle_profesor_asignado as dpa')
            ->join('unidad_curricular as uc', 'dpa.id_unidad_curricular', '=', 'uc.id_unidad_curricular')
            ->join('seccion as s', 'dpa.id_seccion', '=', 's.id_seccion')
            ->leftJoin('malla_academica as ma', 'uc.id_malla_academica', '=', 'ma.id_malla_academica')
            ->leftJoin('pnf', 'ma.id_pnf', '=', 'pnf.id_pnf')
            ->where('dpa.id_users', $userId)
            ->where('dpa.estatus', '1')
            ->select(
                'dpa.id_detalle_profesor_asignado',
                'uc.nombre_unidad_curricular',
                'uc.trayecto_unidad_curricular',
                's.nombre_seccion',
                'pnf.nombre_pnf'
            )
            ->get()
            ->map(function ($asignacion) {
                // Crear un nombre legible para el select
                $pnf = $asignacion->nombre_pnf ?? 'Sin PNF';
                $trayecto = $asignacion->trayecto_unidad_curricular ? "T{$asignacion->trayecto_unidad_curricular}" : 'S/T';
                $asignacion->descripcion_completa = "{$pnf} - {$trayecto} - {$asignacion->nombre_unidad_curricular} (Sección {$asignacion->nombre_seccion})";
                return $asignacion;
            });
    }
}
