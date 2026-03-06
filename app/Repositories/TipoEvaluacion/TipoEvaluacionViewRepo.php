<?php

namespace App\Repositories\TipoEvaluacion;

use App\Models\TipoEvaluacion;

class TipoEvaluacionViewRepo
{
    /**
     * Obtiene los detalles de un tipo de evaluación.
     */
    public function mostrar($id)
    {
        $tipoEvaluacion = TipoEvaluacion::find($id);
        if ($tipoEvaluacion) {
            TipoEvaluacion::logMostrar($tipoEvaluacion);
        }
        return $tipoEvaluacion;
    }
}
