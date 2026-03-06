<?php

namespace App\Repositories\TipoEvaluacion;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TipoEvaluacion;

class TipoEvaluacionIndexRepo
{
    /**
     * Lista los tipos de evaluaciones con búsqueda y paginación.
     */
    public function listar($busqueda = '', $paginacion = 5)
    {
        return DB::table('tipo_evaluacion')
            ->select('id_tipo_evaluacion', 'nombre_tipo_evaluacion as nombre', 'estatus')
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_tipo_evaluacion', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('id_tipo_evaluacion', 'desc')
            ->paginate($paginacion);
    }

    /**
     * Inhabilita un tipo de evaluación.
     */
    public function inhabilitar($id)
    {
        $tipoEvaluacion = TipoEvaluacion::find($id);
        if ($tipoEvaluacion) {
            return $tipoEvaluacion->update([
                'estatus' => '3',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }

    /**
     * Restaura un tipo de evaluación.
     */
    public function restaurar($id)
    {
        $tipoEvaluacion = TipoEvaluacion::where('id_tipo_evaluacion', $id)->where('estatus', '3')->first();
        if ($tipoEvaluacion) {
            return $tipoEvaluacion->update([
                'estatus' => '1',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }
}
