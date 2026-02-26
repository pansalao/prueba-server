<?php

namespace App\Repositories\Evaluacion;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EvaluacionIndexRepo
{
    /**
     * Lista las evaluaciones con búsqueda y paginación.
     */
    public function listar($busqueda = '', $paginacion = 5)
    {
        return DB::table('evaluacion')
            ->select('id_evaluacion', 'nombre_evaluacion as nombre', 'estatus')
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_evaluacion', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('id_evaluacion', 'desc')
            ->paginate($paginacion);
    }

    /**
     * Inhabilita una evaluación.
     */
    public function inhabilitar($id)
    {
        $evaluacion = \App\Models\Evaluacion::find($id);
        if ($evaluacion) {
            return $evaluacion->update([
                'estatus' => '3',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }

    /**
     * Restaura una evaluación.
     */
    public function restaurar($id)
    {
        $evaluacion = \App\Models\Evaluacion::where('id_evaluacion', $id)->where('estatus', '3')->first();
        if ($evaluacion) {
            return $evaluacion->update([
                'estatus' => '1',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }
}
