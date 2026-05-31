<?php

namespace App\Repositories\Bibliografia;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BibliografiaIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        $user = auth()->user();
        $esCoordinadorOVicerrector = $user && $user->esCoordinadorOVicerrector();

        return DB::table('bibliografia')
            ->select('id_bibliografia', 'nombre_bibliografia as nombre', 'estatus')
            ->when(!$esCoordinadorOVicerrector, function ($consulta) {
                $consulta->where('estatus', '1');
            })
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('nombre_bibliografia', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('id_bibliografia', 'desc')
            ->paginate($paginacion);
    }

    public function inhabilitar($id)
    {
        $bibliografia = \App\Models\Bibliografia::find($id);
        if ($bibliografia) {
            return $bibliografia->update([
                'estatus' => '3'
            ]);
        }
        return false;
    }

    public function restaurar($id)
    {
        $bibliografia = \App\Models\Bibliografia::where('id_bibliografia', $id)->where('estatus', '3')->first();
        if ($bibliografia) {
            return $bibliografia->update([
                'estatus' => '1'
            ]);
        }
        return false;
    }
}
