<?php

namespace App\Repositories\TipoEvaluacion;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TipoEvaluacion;

class TipoEvaluacionEditRepo
{
    /**
     * Obtiene un tipo de evaluación por su ID.
     */
    public function obtenerPorId($id)
    {
        return DB::table('tipo_evaluacion')
            ->where('id_tipo_evaluacion', $id)
            ->first();
    }

    /**
     * Actualiza un tipo de evaluación existente.
     */
    public function actualizar($id, array $datos)
    {
        $tipoEvaluacion = TipoEvaluacion::find($id);
        if ($tipoEvaluacion) {
            return $tipoEvaluacion->update([
                'nombre_tipo_evaluacion' => $datos['nombre'],
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }
}
