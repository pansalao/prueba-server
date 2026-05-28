<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarioAcademico extends Model
{
    use \App\Traits\Auditable;

    protected $table = 'calendario_academico';
    protected $primaryKey = 'id_calendario_academico';
    public $timestamps = false;
    protected $guarded = [];

    public static function inactivarVencidos()
    {
        $cantidadModificada = 0;

        // Obtener el calendario activo actual
        $calendarioActivo = self::where('estatus', '1')->first();

        if ($calendarioActivo) {
            // Verificar cuál es la fecha fin del último evento de este calendario
            $ultimoEvento = $calendarioActivo->detalles()->orderBy('dia_fin_detalle_evento', 'desc')->first();

            $fechaCorte = $ultimoEvento ? $ultimoEvento->dia_fin_detalle_evento : $calendarioActivo->dia_fin_calendario_academico;

            if (now()->startOfDay()->gt(\Carbon\Carbon::parse($fechaCorte)->startOfDay())) {
                // El último evento ya pasó, inactivar el calendario
                $calendarioActivo->update(['estatus' => '3']);
                $calendarioActivo = null; // Para que pase a buscar si hay uno en espera
                $cantidadModificada++;
            }
        }

        // Si no hay calendario activo, buscar si hay uno en espera
        if (!$calendarioActivo) {
            $calendarioEnEspera = self::where('estatus', '4')
                ->orderBy('id_calendario_academico', 'asc') // El más antiguo en espera
                ->first();

            if ($calendarioEnEspera) {
                // Verificar si ya debe estar activo (por ejemplo, si su fecha de inicio ya pasó o simplemente porque ya no hay otro activo)
                $calendarioEnEspera->update(['estatus' => '1']);
                $cantidadModificada++;
            }
        }

        // Adicionalmente, inactivar cualquier otro calendario que se haya quedado huérfano y vencido
        $cantidadModificada += self::where('estatus', '1')
            ->whereDate('dia_fin_calendario_academico', '<', now())
            ->update(['estatus' => '3']);

        return $cantidadModificada;
    }

    public function detalles()
    {
        return $this->hasMany(DetalleEvento::class, 'id_calendario_academico');
    }
}
