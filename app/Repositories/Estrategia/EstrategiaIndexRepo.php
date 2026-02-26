<?php

namespace App\Repositories\Estrategia;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstrategiaIndexRepo
{
    /**
     * Lista las estrategias pedagógicas con búsqueda y paginación.
     */
    public function listar($busqueda = '', $paginacion = 5)
    {
        return DB::table('tecnica_actividad')
            ->select('id_tecnica_actividad as id_estrategia_pedagogica', 'nombre_tecnica_actividad as nombre', 'estatus')
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_tecnica_actividad', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('fecha_creacion', 'desc')
            ->paginate($paginacion);
    }

    /**
     * Inhabilita una estrategia pedagógica.
     */
    public function inhabilitar($id)
    {
        $estrategia = \App\Models\Estrategia::find($id);
        if ($estrategia) {
            return $estrategia->update([
                'estatus' => '3',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }

    /**
     * Restaura una estrategia pedagógica.
     */
    public function restaurar($id)
    {
        $estrategia = \App\Models\Estrategia::where('id_tecnica_actividad', $id)->where('estatus', '3')->first();
        if ($estrategia) {
            return $estrategia->update([
                'estatus' => '1',
                'fecha_actualizacion' => Carbon::now()
            ]);
        }
        return false;
    }
}
