<?php

namespace App\Repositories\Recurso;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecursoIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        $user = auth()->user();
        $esCoordinadorOVicerrector = $user && $user->esCoordinadorOVicerrector();

        return DB::table('recurso')
            ->select('id_recurso', 'nombre_recurso as nombre', 'estatus')
            ->when(!$esCoordinadorOVicerrector, function ($consulta) {
                $consulta->where('estatus', '1');
            })
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_recurso', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('id_recurso', 'desc')
            ->paginate($paginacion);
    }

    public function inhabilitar($id)
    {
        $recurso = \App\Models\Recurso::find($id);
        if ($recurso) {
            return $recurso->update([
                'estatus' => '3'
            ]);
        }
        return false;
    }

    public function restaurar($id)
    {
        $recurso = \App\Models\Recurso::where('id_recurso', $id)->where('estatus', '3')->first();
        if ($recurso) {
            return $recurso->update([
                'estatus' => '1'
            ]);
        }
        return false;
    }
}
