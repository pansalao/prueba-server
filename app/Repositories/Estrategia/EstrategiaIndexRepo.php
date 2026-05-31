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
        $user = auth()->user();
        $esCoordinadorOVicerrector = $user && $user->esCoordinadorOVicerrector();

        return DB::table('tecnica_actividad')
            ->select('id_tecnica_actividad as id_estrategia_pedagogica', 'nombre_tecnica_actividad as nombre', 'estatus')
            ->when(!$esCoordinadorOVicerrector, function ($consulta) {
                $consulta->where('estatus', '1');
            })
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_tecnica_actividad', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('id_tecnica_actividad', 'desc')
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
                'estatus' => '3'
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
                'estatus' => '1'
            ]);
        }
        return false;
    }
}
