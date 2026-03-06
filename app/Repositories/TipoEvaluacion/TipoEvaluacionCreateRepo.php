<?php

namespace App\Repositories\TipoEvaluacion;

use Carbon\Carbon;
use App\Models\TipoEvaluacion;

class TipoEvaluacionCreateRepo
{
    /**
     * Crea un nuevo tipo de evaluación.
     */
    public function crear(array $datos)
    {
        $tipoEvaluacion = TipoEvaluacion::create([
            'nombre_tipo_evaluacion' => $datos['nombre'],
            'estatus' => '1',
            'fecha_creacion' => Carbon::now(),
            'fecha_actualizacion' => Carbon::now()
        ]);

        return $tipoEvaluacion->id_tipo_evaluacion;
    }
}
