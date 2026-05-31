<?php

namespace App\Repositories\IndicadorLogro;

use Illuminate\Support\Facades\DB;

class IndicadorLogroIndexRepo
{
    public function obtener_indicadores($busqueda = null, $paginacion = 5)
    {
        $user = auth()->user();
        $esCoordinadorOVicerrector = $user && $user->esCoordinadorOVicerrector();

        $query = DB::table('indicador_logro')
            ->select('id_indicador_logro', 'nombre_indicador_logro', 'estatus')
            ->where('estatus', '!=', '3') // No traer eliminados
            ->when(!$esCoordinadorOVicerrector, function ($q) {
                $q->where('estatus', '1');
            });

        if ($busqueda) {
            $query->where('nombre_indicador_logro', 'like', '%' . $busqueda . '%');
        }

        return $query->orderBy('id_indicador_logro', 'desc')->paginate($paginacion);
    }

    public function inhabilitar($id)
    {
        $indicador = \App\Models\IndicadorLogro::find($id);
        if ($indicador) {
            return $indicador->update(['estatus' => '2']);
        }
        return false;
    }

    public function restaurar($id)
    {
        $indicador = \App\Models\IndicadorLogro::find($id);
        if ($indicador) {
            return $indicador->update(['estatus' => '1']);
        }
        return false;
    }
}
