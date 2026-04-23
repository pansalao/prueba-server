<?php

namespace App\Repositories\Calendario;

use Illuminate\Support\Facades\DB;
use App\Models\CalendarioAcademico;

class CalendarioViewRepo
{
    public function mostrar($id)
    {
        CalendarioAcademico::inactivarVencidos();

        $calendario = CalendarioAcademico::with(['detalles.evento'])
            ->where('id_calendario_academico', $id)
            ->first();

        if ($calendario) {
            CalendarioAcademico::logMostrar($calendario);
        }

        return $calendario;
    }
}
