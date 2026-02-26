<?php

namespace App\Repositories\Tecnica;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TecnicaIndexRepo
{
    /**
     * Lista las técnicas con búsqueda y paginación.
     */
    public function listar($busqueda = '', $paginacion = 5)
    {
        return DB::table('tecnica_evaluacion')
            ->select('id_tecnica', 'nombre_tecnica_evaluacion as nombre', 'estatus')
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_tecnica_evaluacion', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('fecha_creacion', 'desc')
            ->paginate($paginacion);
    }

    /**
     * Inhabilita una técnica.
     */
    public function inhabilitar($id)
    {
        $tecnica = \App\Models\Tecnica::find($id);
        if ($tecnica) {
            return $tecnica->update([
                'estatus' => '3',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }

    /**
     * Restaura una técnica.
     */
    public function restaurar($id)
    {
        $tecnica = \App\Models\Tecnica::where('id_tecnica', $id)->where('estatus', '3')->first();
        if ($tecnica) {
            return $tecnica->update([
                'estatus' => '1',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }
}
