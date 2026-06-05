<?php

namespace App\Repositories\Calendario;

use Illuminate\Support\Facades\DB;

class CalendarioIndexRepo
{
    public function listar($busqueda = '', $paginacion = 5)
    {
        // Inactivar automáticamente los que han vencido
        \App\Models\CalendarioAcademico::inactivarVencidos();

        $calendarios = DB::table('calendario_academico')
            ->select('id_calendario_academico', 'semana_lapso_uno_calendario_academico', 'semana_lapso_dos_calendario_academico', 'dia_inicio_calendario_academico', 'dia_fin_calendario_academico', 'estatus', 'justificativo_calendario_academico')
            ->when($busqueda, function ($consulta, $busqueda) {
                $consulta->where('semana_lapso_uno_calendario_academico', 'LIKE', '%' . $busqueda . '%')
                         ->orWhere('semana_lapso_dos_calendario_academico', 'LIKE', '%' . $busqueda . '%');
            })
            ->orderBy('estatus', 'asc') // Activos (1) primero, Inactivos (3) después
            ->orderBy('id_calendario_academico', 'desc')
            ->paginate($paginacion);

        return $calendarios;
    }

    public function hayCalendarioActivo(): bool
    {
        return DB::table('calendario_academico')
            ->where('estatus', '1')
            ->exists();
    }
}
